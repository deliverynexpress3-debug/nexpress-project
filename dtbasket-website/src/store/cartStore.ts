"use client";
import { useAppDispatch, useAppSelector } from "./hooks";
import {
  addItem, removeItem, updateQty, clearCart,
  openCart, closeCart, toggleCart,
  selectCart, selectCartIsOpen,
} from "./cartSlice";
import type { CartStoreItem, ProductItem } from "@/types";
import { cartService } from "@/services/cartService";
import { queryClient } from "@/lib/queryClient";
import { QUERY_KEYS } from "@/lib/constants";
import toast from "react-hot-toast";

function invalidateCart() {
  queryClient.invalidateQueries({ queryKey: [QUERY_KEYS.CART] });
}

export function useCartStore() {
  const dispatch = useAppDispatch();
  const cart = useAppSelector(selectCart);
  const isOpen = useAppSelector(selectCartIsOpen);

  return {
    cart,
    isOpen,

    addItem: (product: ProductItem, quantity = 1) => {
      const price = product.final_price ?? product.price;
      const existing = (cart.items as CartStoreItem[]).find((i) => i.product_id === product.id);
      const prevQty = existing?.quantity ?? 0;
      dispatch(addItem({ product, quantity }));
      toast.success(`${product.name} added to cart`);

      const rollback = () => {
        if (prevQty > 0) {
          dispatch(updateQty({ productId: product.id, quantity: prevQty }));
        } else {
          dispatch(removeItem(product.id));
        }
      };

      cartService
        .addToCart(product.id, quantity, price)
        .then((res) => {
          if (res.status !== 200) {
            rollback();
            toast.error(res.message || "Failed to add to cart");
            return;
          }
          invalidateCart();
        })
        .catch(() => {
          rollback();
          toast.error("Failed to add to cart");
        });
    },

    removeItem: (productId: number) => {
      const removed = (cart.items as CartStoreItem[]).find((i) => i.product_id === productId);
      dispatch(removeItem(productId));
      cartService
        .removeFromCart(productId)
        .then((res) => {
          if (res.status !== 200) {
            if (removed) {
              dispatch(addItem({ product: removed.product, quantity: removed.quantity }));
            }
            toast.error(res.message || "Failed to remove from cart");
            return;
          }
          invalidateCart();
        })
        .catch(() => {
          if (removed) {
            dispatch(addItem({ product: removed.product, quantity: removed.quantity }));
          }
          toast.error("Failed to remove from cart");
        });
    },

    updateQuantity: (productId: number, quantity: number) => {
      const existing = (cart.items as CartStoreItem[]).find((i) => i.product_id === productId);
      const prevQty = existing?.quantity ?? 0;

      if (quantity < 1) {
        dispatch(removeItem(productId));
        cartService
          .removeFromCart(productId)
          .then((res) => {
            if (res.status !== 200) {
              if (existing) {
                dispatch(addItem({ product: existing.product, quantity: prevQty }));
              }
              toast.error(res.message || "Failed to update cart");
              return;
            }
            invalidateCart();
          })
          .catch(() => {
            if (existing) {
              dispatch(addItem({ product: existing.product, quantity: prevQty }));
            }
            toast.error("Failed to update cart");
          });
        return;
      }

      dispatch(updateQty({ productId, quantity }));
      cartService
        .updateQuantity(productId, quantity)
        .then((res) => {
          if (res.status !== 200) {
            dispatch(updateQty({ productId, quantity: prevQty }));
            toast.error(res.message || "Failed to update cart");
            return;
          }
          invalidateCart();
        })
        .catch(() => {
          dispatch(updateQty({ productId, quantity: prevQty }));
          toast.error("Failed to update cart");
        });
    },

    clearCart: () => {
      const itemIds = (cart.items as CartStoreItem[]).map((i) => i.product_id);
      dispatch(clearCart());
      Promise.all(itemIds.map((id) => cartService.removeFromCart(id).catch(() => null)))
        .then(() => invalidateCart());
    },
    openCart: () => dispatch(openCart()),
    closeCart: () => dispatch(closeCart()),
    toggleCart: () => dispatch(toggleCart()),
  };
}
