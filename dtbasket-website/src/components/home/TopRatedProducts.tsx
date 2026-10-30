"use client";
import { useRef } from "react";
import Link from "next/link";
import { FiChevronLeft, FiChevronRight } from "react-icons/fi";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, FreeMode } from "swiper/modules";
import { useTopRatingItems } from "@/hooks/useApi";
import ProductCard from "@/components/product/ProductCard";
import ProductCardSkeleton from "@/components/product/ProductCardSkeleton";
import { TEXT } from "@/branding";

/**
 * Top-rated products row — shown above the footer (after App Banner).
 * Uses /get_top_rating_items so it still shows when category feeds are empty.
 */
export default function TopRatedProducts() {
  const prevRef = useRef<HTMLButtonElement>(null);
  const nextRef = useRef<HTMLButtonElement>(null);
  const { data, isLoading, isError } = useTopRatingItems(1);
  const products =
    data?.status === 200 && Array.isArray(data.result) ? data.result : [];
  const loading = isLoading && !data;

  if (!loading && (isError || products.length === 0)) {
    return null;
  }

  return (
    <section className="rounded-3xl shadow-card p-4 sm:p-6 border border-gray-100/80 bg-white dark:bg-dm-surface">
      <div className="flex items-center justify-between mb-4">
        <h2 className="section-title text-lg sm:text-xl">{TEXT.home.bestSellers}</h2>
        <div className="flex items-center gap-2">
          <Link
            href="/shop"
            className="text-sm font-bold text-secondary-600 hover:text-secondary-700 transition-colors mr-1"
          >
            {TEXT.home.seeAll}
          </Link>
          <button
            ref={prevRef}
            className="w-8 h-8 border border-primary-100 rounded-full flex items-center justify-center hover:bg-secondary-50 text-primary-800 transition-colors"
            aria-label="Previous"
          >
            <FiChevronLeft size={15} />
          </button>
          <button
            ref={nextRef}
            className="w-8 h-8 border border-primary-100 rounded-full flex items-center justify-center hover:bg-secondary-50 text-primary-800 transition-colors"
            aria-label="Next"
          >
            <FiChevronRight size={15} />
          </button>
        </div>
      </div>

      {loading ? (
        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
          {Array.from({ length: 6 }).map((_, i) => (
            <ProductCardSkeleton key={i} />
          ))}
        </div>
      ) : (
        <Swiper
          modules={[Navigation, FreeMode]}
          navigation={{ prevEl: prevRef.current, nextEl: nextRef.current }}
          freeMode
          slidesPerView={2}
          spaceBetween={12}
          breakpoints={{
            480: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            1024: { slidesPerView: 5 },
            1280: { slidesPerView: 6 },
          }}
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
