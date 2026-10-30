"use client";
import Image from "next/image";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, Pagination, Navigation, EffectFade } from "swiper/modules";
import { FiChevronLeft, FiChevronRight } from "react-icons/fi";
import { IMAGES } from "@/branding";

/** Wide hero — balanced height (not too tall, not too short). */
const HERO_H = "h-[190px] sm:h-[230px] md:h-[280px] lg:h-[320px]";

/**
 * Full-bleed hero auto-slider — bright wide banners, short height.
 */
export default function PromoBannerCards() {
  const slides = IMAGES.heroSlides;

  return (
    <div className="w-full max-w-none overflow-hidden bg-[#F5F7F8] shadow-premium relative group/hero">
      <Swiper
        modules={[Autoplay, Pagination, Navigation, EffectFade]}
        effect="fade"
        fadeEffect={{ crossFade: true }}
        loop
        autoplay={{ delay: 4800, disableOnInteraction: false }}
        pagination={{ clickable: true }}
        navigation={{
          prevEl: ".hero-prev",
          nextEl: ".hero-next",
        }}
        className="hero-swiper w-full"
      >
        {slides.map((src, i) => (
          <SwiperSlide key={`${src}-v5-${i}`}>
            <div className={`relative w-full ${HERO_H} bg-[#F5F7F8]`}>
              <Image
                src={`${src}?v=5`}
                alt={`Nexpress Delivery campaign ${i + 1}`}
                fill
                className="object-cover object-center"
                sizes="100vw"
                priority={i === 0}
              />
            </div>
          </SwiperSlide>
        ))}
      </Swiper>

      <button
        type="button"
        aria-label="Previous slide"
        className="hero-prev absolute left-2 sm:left-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/90 hover:bg-white shadow flex items-center justify-center text-primary-800 opacity-0 group-hover/hero:opacity-100 transition-opacity"
      >
        <FiChevronLeft size={18} />
      </button>
      <button
        type="button"
        aria-label="Next slide"
        className="hero-next absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-white/90 hover:bg-white shadow flex items-center justify-center text-primary-800 opacity-0 group-hover/hero:opacity-100 transition-opacity"
      >
        <FiChevronRight size={18} />
      </button>
    </div>
  );
}
