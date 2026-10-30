import type { Metadata } from "next";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";

export const metadata: Metadata = {
  title: "Terms & Conditions",
  description: "Terms and conditions for using Nexpress Delivery website and services.",
};

export default function TermsPage() {
  return (
    <StaticPageShell
      title="Terms & Conditions"
      subtitle="Please read these terms carefully before using Nexpress Delivery."
    >
      <Prose>
        <p>Last updated: {new Date().toLocaleDateString("en-GB", { year: "numeric", month: "long", day: "numeric" })}</p>

        <h2>1. Agreement</h2>
        <p>
          By accessing nexpressdelivery.co.uk or the Nexpress app, you agree to these Terms &amp;
          Conditions and our <Link href="/privacy-policy">Privacy Policy</Link>. If you do not agree,
          please do not use our services.
        </p>

        <h2>2. Orders &amp; pricing</h2>
        <ul>
          <li>All orders are subject to availability and confirmation.</li>
          <li>Prices shown include applicable taxes unless stated otherwise.</li>
          <li>We may refuse or cancel an order if stock, payment, or delivery cannot be fulfilled.</li>
        </ul>

        <h2>3. Delivery</h2>
        <p>
          Delivery times are estimates. Risk in goods passes on delivery to the address you provide.
          Please ensure someone is available to receive chilled or bulky items. See our{" "}
          <Link href="/delivery">Delivery</Link> page for more detail.
        </p>

        <h2>4. Accounts</h2>
        <p>
          You are responsible for keeping login details secure and for activity under your account.
          Provide accurate contact and address information for successful delivery.
        </p>

        <h2>5. Returns &amp; refunds</h2>
        <p>
          Damaged, missing, or incorrect items should be reported promptly. Refunds and replacements
          are handled under our <Link href="/refund-policy">Refund Policy</Link>.
        </p>

        <h2>6. Acceptable use</h2>
        <p>
          You must not misuse the site, attempt unauthorised access, scrape content at scale, or use
          our services for unlawful purposes.
        </p>

        <h2>7. Liability</h2>
        <p>
          To the fullest extent permitted by law, Nexpress is not liable for indirect or consequential
          loss. Nothing in these terms limits liability for death or personal injury caused by
          negligence, or for fraud.
        </p>

        <h2>8. Changes</h2>
        <p>
          We may update these terms from time to time. Continued use of the service after changes
          means you accept the revised terms.
        </p>

        <h2>9. Contact</h2>
        <p>
          For terms-related questions, visit <Link href="/contact">Contact Us</Link>.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
