"use client";
import Link from "next/link";
import Image from "next/image";
import { useCategories } from "@/hooks/useApi";
import { TEXT, IMAGES } from "@/branding";
import { Category } from "@/types";

/* Larger BigBasket-style category tiles */
function CategoryTile({ category }: { category: Category }) {
  return (
    <Link
      href={`/categories/${category.id}/0`}
      className="group flex flex-col items-center gap-2.5 flex-shrink-0 snap-start"
      style={{ width: "148px" }}
    >
      <div
        className="relative rounded-3xl overflow-hidden bg-white dark:bg-dm-surface2 border border-primary-100/80 shadow-card group-hover:shadow-card-hover group-hover:-translate-y-1 transition-all duration-300 ring-0 group-hover:ring-2 group-hover:ring-secondary-300"
        style={{ width: "148px", height: "148px" }}
      >
        <Image
          src={category.image || IMAGES.placeholder}
          alt={category.name}
          fill
          className="object-contain p-3.5 group-hover:scale-105 transition-transform duration-300"
          sizes="148px"
        />
      </div>
      <span className="text-sm font-bold text-center leading-snug line-clamp-2 text-gray-800 dark:text-dm-text group-hover:text-primary-700 transition-colors w-full px-1">
        {category.name}
      </span>
    </Link>
  );
}

export default function CategoryStrip() {
  const { data, isLoading, isError } = useCategories(0, 1);
  const categories: Category[] =
    data?.status === 200 && Array.isArray(data.result) ? data.result : [];
  const loading = isLoading && !data;

  return (
    <section className="w-full py-7 px-1 rounded-3xl shadow-card" style={{ backgroundColor: "#EEF5F6" }}>
      <div className="px-4 sm:px-6">
        <div className="flex items-center justify-between mb-5">
          <div>
            <h2 className="text-xl sm:text-2xl font-black text-gray-900 dark:text-dm-text font-display tracking-tight">
              Shop by category
            </h2>
            <p className="text-sm text-gray-500 mt-0.5">Fresh picks, delivered fast</p>
          </div>
          <Link
            href="/categories"
            className="text-sm font-bold text-secondary-600 hover:text-secondary-700 transition-colors whitespace-nowrap bg-secondary-50 hover:bg-secondary-100 px-3.5 py-2 rounded-full"
          >
            {TEXT.home.seeAll}
          </Link>
        </div>

        <div
          className="flex gap-5 overflow-x-auto snap-x snap-mandatory pb-2"
          style={{ scrollbarWidth: "none", WebkitOverflowScrolling: "touch" }}
        >
          {loading
            ? Array.from({ length: 7 }).map((_, i) => (
                <div key={i} className="flex-shrink-0" style={{ width: "148px" }}>
                  <div className="skeleton w-[148px] h-[148px] rounded-3xl" />
                  <div className="skeleton h-4 w-4/5 rounded mx-auto mt-3" />
                </div>
              ))
            : isError
              ? (
                <p className="text-sm text-red-500 py-6 max-w-xl">
                  Couldn’t reach the product API (<code className="text-xs">adminnexpress.tech</code>). 
                  Server may be down or blocked on this network — try again later or check the admin host.
                </p>
              )
              : categories.length === 0
                ? <p className="text-sm text-gray-400 py-6">{TEXT.home.noCategories}</p>
                : categories.map((cat) => (
                    <CategoryTile key={cat.id} category={cat} />
                  ))}
        </div>
      </div>
    </section>
  );
}
