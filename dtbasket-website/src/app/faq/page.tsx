import type { Metadata } from "next";
import StaticPageShell from "@/components/layout/StaticPageShell";
import FaqAccordion from "@/components/content/FaqAccordion";

export const metadata: Metadata = {
  title: "FAQ",
  description: "Frequently asked questions about Nexpress Delivery orders and service.",
};

export default function FaqPage() {
  return (
    <StaticPageShell
      title="FAQ"
      subtitle="Quick answers about ordering, delivery, and your Nexpress account."
    >
      <FaqAccordion />
    </StaticPageShell>
  );
}
