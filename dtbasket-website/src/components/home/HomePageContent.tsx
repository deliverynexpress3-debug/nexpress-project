import MainLayout from "@/components/layout/MainLayout";
import PromoBannerCards from "@/components/home/PromoCards";
import CategoryStrip from "@/components/home/CategoryStrip";
import HomeSectionsClient from "@/components/home/HomeSectionsClient";
import FeaturedProducts from "@/components/home/FeaturedProducts";
import TopRatedProducts from "@/components/home/TopRatedProducts";

/**
 * Home layout — hero full-bleed; app download banner hidden until real store URLs exist.
 */
export default function HomePageContent({ typeId }: { typeId?: string }) {
  return (
    <MainLayout>
      <div className="page-enter">
        {typeId == null && <PromoBannerCards />}

        <div className="container-custom space-y-4 py-4">
          {typeId == null && <CategoryStrip />}
        </div>

        {/* Admin sections — Bestsellers category_grid, product carousels, etc. */}
        {typeId == null && <HomeSectionsClient />}
        {typeId != null && <HomeSectionsClient typeId={typeId} />}

        <div className="container-custom space-y-4 py-4 pb-6">
          {/* BenefitsBanner hidden: “pay after receiving” conflicts with upfront payment — confirm with owner before restoring */}
          {typeId == null && <TopRatedProducts />}
          {typeId == null && <FeaturedProducts />}
        </div>
      </div>
    </MainLayout>
  );
}
