import type { Metadata } from "next";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";
import { DELIVERY_RULES_COPY } from "@/lib/delivery";

export const metadata: Metadata = {
  title: "Shipping Policy",
  description: "Nexpress Delivery shipping fees, minimum order, cut-off times, and coverage.",
};

export default function ShippingPolicyPage() {
  return (
    <StaticPageShell
      title="Shipping Policy"
      subtitle="How we fulfil and deliver water and drinks orders."
    >
      <Prose>
        <h2>Charges &amp; minimums</h2>
        <ul>
          <li>{DELIVERY_RULES_COPY.fee}</li>
          <li>{DELIVERY_RULES_COPY.minimum}</li>
        </ul>

        <h2>Cut-off &amp; lead times</h2>
        <ul>
          <li>{DELIVERY_RULES_COPY.cutoff}</li>
          <li>{DELIVERY_RULES_COPY.friday}</li>
          <li>{DELIVERY_RULES_COPY.window}</li>
        </ul>

        <h2>Coverage</h2>
        <p>
          We deliver across Greater London and surrounding postcode districts listed on our{" "}
          <Link href="/delivery">Delivery</Link> page. Some districts are only partially covered —
          call 020 8445 0589 / 020 8445 0680 if you are unsure.
        </p>

        <h2>Fulfilment</h2>
        <p>
          Orders placed before cut-off are typically prepared for delivery within 1–2 working days
          as per the schedule. Peak periods or supplier lead times on a few lines may add delay; we
          will tell you if that applies.
        </p>

        <h2>Damages &amp; shortages</h2>
        <p>
          Please check deliveries on receipt. Shortages and damages must be notified within 24 hours
          for claims to be accepted. See our <Link href="/refund-policy">Refund Policy</Link>.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
