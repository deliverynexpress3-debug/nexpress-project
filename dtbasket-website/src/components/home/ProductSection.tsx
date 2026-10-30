"use client";
import { useRef } from "react";
import Link from "next/link";
import { FiChevronLeft, FiChevronRight } from "react-icons/fi";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, FreeMode } from "swiper/modules";
import { useItemsByCategory, useSectionDetails } from "@/hooks/useApi";
import ProductCard from "@/components/product/ProductCard";
import ProductCardSkeleton from "@/components/product/ProductCardSkeleton";
import { cn } from "@/lib/utils";

interface Props {
  title: string;
  sectionId?: number;           // use section_id from API
  categoryId?: number;          // or use category_id
  bgColor?: string;
  seeAllHref?: string;
  /** Hide the whole section when API returns no products (home feed) */
  hideWhenEmpty?: boolean;
}

export default function ProductSection({ title, sectionId, categoryId, bgColor, seeAllHref, hideWhenEmpty }: Props) {
  const prevRef = useRef<HTMLButtonElement>(null);
  const nextRef = useRef<HTMLButtonElement>(null);

  // Prefer section detail if sectionId provided, else items by category
  const sectionQuery = useSectionDetails(sectionId ?? 0, 1);
  const categoryQuery = useItemsByCategory(categoryId ?? 0, 0, 1);

  const query = sectionId ? sectionQuery : categoryQuery;
  // Flutter APIs return HTTP 200 with status:400 when empty — only use result on success
  const products =
    query.data?.status === 200 && Array.isArray(query.data.result) ? query.data.result : [];
  // Only block UI on the first load — not background refetch
  const isLoading = query.isLoading && !query.data;

  if (hideWhenEmpty && !isLoading && products.length === 0) {
    return null;
  }

  return (
    <section className={cn("rounded-3xl shadow-card p-4 sm:p-6 border border-gray-100/80", bgColor || "bg-white dark:bg-dm-surface")}>
      <div className="flex items-center justify-between mb-4">
        <h2 className="section-title text-lg sm:text-xl">{title}</h2>
        <div className="flex items-center gap-2">
          {seeAllHref && (
            <Link href={seeAllHref} className="text-sm font-bold text-secondary-600 hover:text-secondary-700 transition-colors mr-1">
              See All →
            </Link>
          )}
          <button ref={prevRef} className="w-8 h-8 border border-primary-100 rounded-full flex items-center justify-center hover:bg-secondary-50 text-primary-800 transition-colors disabled:opacity-40" aria-label="Previous">
            <FiChevronLeft size={15} />
          </button>
          <button ref={nextRef} className="w-8 h-8 border border-primary-100 rounded-full flex items-center justify-center hover:bg-secondary-50 text-primary-800 transition-colors disabled:opacity-40" aria-label="Next">
            <FiChevronRight size={15} />
          </button>
        </div>
      </div>

      {isLoading ? (
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
          {Array.from({ length: 6 }).map((_, i) => <ProductCardSkeleton key={i} />)}
        </div>
      ) : products.length === 0 ? (
        <p className="text-center text-gray-400 dark:text-dm-muted py-8 text-sm">No products found.</p>
      ) : (
        <Swiper
          modules={[Navigation, FreeMode]}
          navigation={{ prevEl: prevRef.current, nextEl: nextRef.current }}
          freeMode
          slidesPerView={2}
          spaceBetween={12}
          breakpoints={{ 480: { slidesPerView: 3 }, 768: { slidesPerView: 4 }, 1024: { slidesPerView: 5 }, 1280: { slidesPerView: 6 } }}
          onBeforeInit={(swiper) => {
            if (swiper.params.navigation && typeof swiper.params.navigation !== "boolean") {
              swiper.params.navigation.prevEl = prevRef.current;
              swiper.params.navigation.nextEl = nextRef.current;
            }
          }}
        >
          {products.map((product) => (
            <SwiperSlide key={product.id} className="h-auto">
              <ProductCard product={product} />
            </SwiperSlide>
          ))}
        </Swiper>
      )}
    </section>
  );
}
