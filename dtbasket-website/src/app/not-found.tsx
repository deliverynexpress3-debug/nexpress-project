import Link from "next/link";
import MainLayout from "@/components/layout/MainLayout";
import { TEXT } from "@/branding";

export default function NotFound() {
  return (
    <MainLayout>
      <div className="container-custom py-16 sm:py-24 text-center max-w-lg mx-auto">
        <p className="text-sm font-bold uppercase tracking-widest text-primary-700 mb-3">
          Error 404
        </p>
        <h1 className="text-7xl sm:text-8xl font-black text-primary-700 font-display mb-4 leading-none">
          404
        </h1>
        <h2 className="text-2xl font-bold text-gray-900 dark:text-dm-text mb-3">
          {TEXT.notFound.title}
        </h2>
        <p className="text-gray-500 dark:text-dm-muted mb-8 leading-relaxed">
          {TEXT.notFound.message} Check the address, or go back to the home page to keep shopping for
          water and drinks.
        </p>
        <div className="flex flex-col sm:flex-row items-center justify-center gap-3">
          <Link href="/" className="btn-primary min-w-[160px] justify-center">
            {TEXT.notFound.cta}
          </Link>
          <Link
            href="/contact"
            className="btn-outline min-w-[160px] justify-center text-sm"
          >
            Contact Us
          </Link>
        </div>
      </div>
    </MainLayout>
  );
}
