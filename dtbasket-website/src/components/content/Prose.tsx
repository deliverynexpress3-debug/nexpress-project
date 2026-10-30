import { cn } from "@/lib/utils";

export function Prose({
  children,
  className,
}: {
  children: React.ReactNode;
  className?: string;
}) {
  return (
    <div
      className={cn(
        "space-y-4 text-sm sm:text-base text-gray-600 dark:text-dm-muted leading-relaxed",
        "[&_h2]:text-xl [&_h2]:font-black [&_h2]:text-gray-900 [&_h2]:dark:text-dm-text [&_h2]:mt-8 [&_h2]:mb-3 [&_h2]:font-display",
        "[&_h3]:text-base [&_h3]:font-bold [&_h3]:text-gray-800 [&_h3]:dark:text-dm-text [&_h3]:mt-5 [&_h3]:mb-2",
        "[&_ul]:list-disc [&_ul]:pl-5 [&_ul]:space-y-1.5",
        "[&_ol]:list-decimal [&_ol]:pl-5 [&_ol]:space-y-1.5",
        "[&_a]:text-primary-700 [&_a]:font-semibold [&_a]:underline-offset-2 hover:[&_a]:underline",
        "[&_strong]:text-gray-800 [&_strong]:dark:text-dm-text",
        className
      )}
    >
      {children}
    </div>
  );
}
