import Link from "next/link";
import MainLayout from "@/components/layout/MainLayout";

interface Props {
  title: string;
  subtitle?: string;
  children: React.ReactNode;
}

/**
 * Shared chrome for About / FAQ / Terms / Privacy / Tracking style pages.
 */
export default function StaticPageShell({ title, subtitle, children }: Props) {
  return (
    <MainLayout bgImage="">
      <div className="page-enter">
        {/* Brand teal hero strip */}
        <div className="bg-primary-700 text-white">
          <div className="container-custom py-10 sm:py-14">
            <nav className="text-xs text-white/70 mb-3 flex items-center gap-1.5">
              <Link href="/" className="hover:text-white transition-colors">
                Home
              </Link>
              <span>/</span>
              <span className="text-white/90">{title}</span>
            </nav>
            <h1 className="text-3xl sm:text-4xl font-black font-display tracking-tight">
              {title}
            </h1>
            {subtitle ? (
              <p className="mt-2 text-sm sm:text-base text-white/80 max-w-2xl leading-relaxed">
                {subtitle}
              </p>
            ) : null}
          </div>
        </div>

        <div className="container-custom py-8 sm:py-12">
          <div className="rounded-3xl bg-white dark:bg-dm-surface shadow-card border border-gray-100 dark:border-dm-border p-6 sm:p-10">
            {children}
          </div>
        </div>
      </div>
    </MainLayout>
  );
}
