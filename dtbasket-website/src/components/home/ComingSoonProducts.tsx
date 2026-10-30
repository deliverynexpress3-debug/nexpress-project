"use client";
import Image from "next/image";
import Link from "next/link";
import { FiHeart } from "react-icons/fi";
import { TEXT, COLORS, IMAGES, CONFIG } from "@/branding";

/**
 * Static placeholder products shown after "Explore Categories".
 * Replace these with real backend products once they're added.
 * Demo products use the Nexpress hero image as a stand-in.
 */
const demoProducts = [
  { id: 1, name: "Ferro Rischer Chocolate", unit: "16 pcs", price: 299 },
  { id: 2, name: "McVitie's Digestive", unit: "10 packs", price: 120 },
  { id: 3, name: "Flash Multi Surface Cleaner", unit: "500 ml", price: 120 },
  { id: 4, name: "Cif Cream Cleaner", unit: "500 ml", price: 145 },
  { id: 5, name: "Nature Valley Crunchy Bar", unit: "42 g", price: 90 },
  { id: 6, name: "Turmeric Immunity Shot", unit: "60 ml", price: 75 },
  { id: 7, name: "Lavazza Crema e Gusto", unit: "250 g", price: 320 },
  { id: 8, name: "Marigold Gloves Sensitive Skin", unit: "1 pair", price: 110 },
];

function ComingSoonCard({ product }: { product: (typeof demoProducts)[number] }) {
  const inWishlist = false;
  return (
    <Link href="/shop" className="group block flex-shrink-0 w-40">
      <div className="rounded-2xl bg-white dark:bg-dm-surface shadow-card hover:shadow-md transition-shadow duration-200 overflow-hidden">
        <div className="relative aspect-square bg-gray-50 dark:bg-dm-surface2 rounded-t-2xl overflow-hidden">
          <Image
            src={IMAGES.placeholder}
            alt={product.name}
            fill
            className="object-contain p-4 group-hover:scale-105 transition-transform duration-300"
            sizes="160px"
          />
          <span
            className="absolute top-2 left-2 text-[10px] font-black px-2 py-0.5 rounded-md bg-primary-700 text-white uppercase tracking-wide"
          >
            Coming Soon
          </span>
          <button
            aria-label="Wishlist"
            className="absolute top-2 right-2 w-7 h-7 rounded-full bg-white dark:bg-dm-surface shadow-sm flex items-center justify-center z-10">
            <FiHeart size={13} className={inWishlist ? "fill-red-500 text-red-500" : "text-gray-400"} />
          </button>
        </div>
        <div className="px-3 pt-2.5 pb-3 space-y-1">
          <div className="flex items-center justify-between gap-2">
            <span
              className="inline-flex items-center text-white text-sm font-black px-2.5 py-1 rounded-lg leading-none bg-primary-700 shadow-[3px_3px_0px_rgba(0,0,0,0.15)]">
              {CONFIG.currencySymbol}{product.price}
            </span>
            <button
              disabled
              className="cursor-not-allowed bg-gray-100 dark:bg-dm-surface2 text-gray-400 text-xs font-bold px-3 py-1.5 rounded-lg">
              {TEXT.product.add}
            </button>
          </div>
          <p className="text-sm font-semibold text-gray-900 dark:text-dm-text line-clamp-2 leading-snug">{product.name}</p>
          <p className="text-xs text-gray-400 dark:text-dm-muted">{product.unit}</p>
        </div>
      </div>
    </Link>
  );
}

export default function ComingSoonProducts() {
  return (
    <section className="w-full py-5">
      <div className="container-custom">
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-xl font-black text-gray-900 dark:text-dm-text font-display">
            {TEXT.home.featuredProducts}
          </h2>
          <Link href="/shop" className="text-sm font-semibold text-primary-700 hover:text-primary-800 transition-colors whitespace-nowrap">
            {TEXT.home.seeAll}
          </Link>
        </div>
        <div className="flex gap-3 overflow-x-auto pb-2" style={{ scrollbarWidth: "none" }}>
          {demoProducts.map((p) => <ComingSoonCard key={p.id} product={p} />)}
        </div>
      </div>
    </section>
  );
}
