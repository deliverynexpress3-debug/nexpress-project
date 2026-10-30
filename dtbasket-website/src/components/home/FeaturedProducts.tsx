"use client";
import { useMemo } from "react";
import { useCategories } from "@/hooks/useApi";
import ProductSection from "@/components/home/ProductSection";
import ProductCardSkeleton from "@/components/product/ProductCardSkeleton";
import { TEXT } from "@/branding";
import { Category } from "@/types";

/** Max category product rows on home — rest via “See All” / category pages. */
const MAX_HOME_SECTIONS = 6;

/**
 * Home product carousels by category — placed between App Banner and Footer.
 */
export default function FeaturedProducts() {
  const { data, isLoading } = useCategories(0, 1);
  const categories: Category[] =
    data?.status === 200 && Array.isArray(data.result) ? data.result : [];

  const homeCategories = useMemo(
    () => categories.slice(0, MAX_HOME_SECTIONS),
    [categories]
  );

  if (isLoading) {
    return (
      <section className="w-full py-2">
        <div className="skeleton h-7 w-48 rounded mb-4" />
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
          {Array.from({ length: 6 }).map((_, i) => (
            <ProductCardSkeleton key={i} />
          ))}
        </div>
      </section>
    );
  }

  if (homeCategories.length === 0) {
    return null;
  }

  return (
    <div className="space-y-4">
      {homeCategories.map((cat) => (
        <ProductSection
          key={cat.id}
          title={cat.name}
          categoryId={cat.id}
          seeAllHref={`/categories/${cat.id}/0`}
          hideWhenEmpty
        />
      ))}
    </div>
  );
}
