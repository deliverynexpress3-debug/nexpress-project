import type { Metadata } from "next";
import MainLayout from "@/components/layout/MainLayout";
import CategoryPageClient from "@/components/home/CategoryPageClient";
export const metadata: Metadata = { title: "Categories" };
export default function CategoriesPage() {
  return (
    <MainLayout>
      <div className="container-custom py-6 page-enter"><CategoryPageClient /></div>
    </MainLayout>
  );
}
