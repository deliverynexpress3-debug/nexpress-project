import type { Metadata } from "next";
import MainLayout from "@/components/layout/MainLayout";
import ProductDetailClient from "@/components/product/ProductDetailClient";
import { productService } from "@/services/productService";

interface Props { params: Promise<{ slug: string }> }

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  const { slug } = await params;
  try {
    const res = await productService.getItemDetails(slug);
    const name = res?.result?.[0]?.name;
    if (name?.trim()) {
      return { title: name };
    }
  } catch {
    // fall through
  }
  return { title: "Product" };
}

export default async function ProductPage({ params }: Props) {
  const { slug } = await params;
  return (
    <MainLayout>
      <div className="container-custom py-6">
        <ProductDetailClient itemId={slug} />
      </div>
    </MainLayout>
  );
}
