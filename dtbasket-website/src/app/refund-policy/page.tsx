import type { Metadata } from "next";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";

export const metadata: Metadata = {
  title: "Refund Policy",
  description: "Nexpress refund and replacement policy for damaged or incorrect orders.",
};

export default function RefundPolicyPage() {
  return (
    <StaticPageShell
      title="Refund Policy"
      subtitle="When and how we replace or refund drinks orders."
    >
      <Prose>
        <h2>Eligible issues</h2>
        <ul>
          <li>Items arrived damaged or leaking</li>
          <li>Wrong product or missing lines versus your invoice</li>
          <li>Order cancelled before dispatch (full refund of paid amount)</li>
        </ul>

        <h2>How to claim</h2>
        <p>
          Contact us within <strong>24 hours</strong> of delivery with your order number and photos
          where relevant via <Link href="/contact">Contact Us</Link>.
        </p>

        <h2>Resolution</h2>
        <p>
          We may offer a replacement on the next available slot or a refund to the original payment
          method. Approved refunds typically appear within 5–10 business days depending on your bank.
        </p>

        <h2>Non-refundable</h2>
        <p>
          Change-of-mind after successful delivery of correct, undamaged goods is generally not
          refundable for perishable / chilled beverages, except where required by law.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
