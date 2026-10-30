import type { Metadata } from "next";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";
import { TEXT } from "@/branding";

export const metadata: Metadata = {
  title: "About Us",
  description: "Learn about Nexpress Delivery — bottled water and drinks delivered to your door.",
};

export default function AboutPage() {
  return (
    <StaticPageShell
      title="About Us"
      subtitle="London’s trusted home & business drinks delivery — quality beverages, reliable service."
    >
      <Prose>
        <p>
          <strong>Nexpress Delivery</strong> brings chilled bottled water, soft drinks, and
          everyday beverages straight to homes and workplaces. We focus on fast fulfilment,
          carefully handled stock, and a simple ordering experience on web and app.
        </p>

        <h2>What we stand for</h2>
        <ul>
          <li>Fresh, quality drinks from brands you know</li>
          <li>Reliable delivery slots for home and business</li>
          <li>Clear pricing and easy reordering</li>
          <li>Friendly support when you need it</li>
        </ul>

        <h2>Our promise</h2>
        <p>
          From mineral water to soft drinks and hot beverage staples, we keep popular lines in
          stock and deliver with care. Whether you are stocking an office or topping up at home,
          Nexpress is built to make drinks delivery effortless.
        </p>

        <h2>Get in touch</h2>
        <p>
          Questions about an order or wholesale supply? Visit our{" "}
          <Link href="/contact">Contact</Link> page, email{" "}
          <a href={`mailto:${TEXT.footer.contact.email}`}>{TEXT.footer.contact.email}</a>, or call{" "}
          {TEXT.footer.contact.phone} / {TEXT.footer.contact.phone2}.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
