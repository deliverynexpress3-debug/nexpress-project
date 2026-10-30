"use client";

import { FormEvent, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { FiPackage, FiSearch } from "react-icons/fi";
import { useAuthStore } from "@/store/authStore";

export default function TrackingClient() {
  const router = useRouter();
  const { isAuthenticated, openModal } = useAuthStore();
  const [orderNo, setOrderNo] = useState("");
  const [hint, setHint] = useState("");

  const onSubmit = (e: FormEvent) => {
    e.preventDefault();
    const q = orderNo.trim();
    if (!q) {
      setHint("Enter your order number to continue.");
      return;
    }
    if (!isAuthenticated) {
      setHint("Sign in to view live tracking for this order.");
      openModal();
      return;
    }
    router.push(`/account/orders?order=${encodeURIComponent(q)}`);
  };

  return (
    <div className="max-w-xl mx-auto space-y-8">
      <div className="flex items-start gap-4 rounded-2xl bg-primary-50 dark:bg-dm-surface2 border border-primary-100 dark:border-dm-border p-5">
        <div className="w-12 h-12 rounded-full bg-primary-700 text-white flex items-center justify-center flex-shrink-0">
          <FiPackage size={22} />
        </div>
        <div>
          <p className="font-bold text-gray-900 dark:text-dm-text">Track with your order number</p>
          <p className="text-sm text-gray-600 dark:text-dm-muted mt-1 leading-relaxed">
            Find the order ID in your confirmation email or under My Account → Orders.
          </p>
        </div>
      </div>

      <form onSubmit={onSubmit} className="space-y-3">
        <label htmlFor="order-no" className="block text-sm font-semibold text-gray-700 dark:text-dm-text">
          Order number
        </label>
        <div className="flex flex-col sm:flex-row gap-3">
          <input
            id="order-no"
            value={orderNo}
            onChange={(e) => {
              setOrderNo(e.target.value);
              setHint("");
            }}
            placeholder="e.g. NX-10245"
            className="flex-1 px-4 py-3 rounded-xl border border-gray-200 dark:border-dm-border bg-white dark:bg-dm-surface2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/40"
          />
          <button
            type="submit"
            className="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-secondary-500 hover:bg-secondary-600 text-white font-bold text-sm transition-colors"
          >
            <FiSearch size={16} />
            Track order
          </button>
        </div>
        {hint ? <p className="text-sm text-amber-700">{hint}</p> : null}
      </form>

      <div className="text-sm text-gray-600 dark:text-dm-muted space-y-2 border-t border-gray-100 dark:border-dm-border pt-6">
        <p>
          Already signed in? Open{" "}
          <Link href="/account/orders" className="text-primary-700 font-semibold hover:underline">
            My Orders
          </Link>{" "}
          for full status and delivery partner details.
        </p>
        <p>
          Need help?{" "}
          <Link href="/contact" className="text-primary-700 font-semibold hover:underline">
            Contact support
          </Link>{" "}
          or check the{" "}
          <Link href="/faq" className="text-primary-700 font-semibold hover:underline">
            FAQ
          </Link>
          .
        </p>
      </div>
    </div>
  );
}
