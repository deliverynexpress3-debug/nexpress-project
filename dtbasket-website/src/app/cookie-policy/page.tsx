import type { Metadata } from "next";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";

export const metadata: Metadata = {
  title: "Cookie Policy",
  description: "How Nexpress Delivery uses cookies and similar technologies.",
};

export default function CookiePolicyPage() {
  return (
    <StaticPageShell
      title="Cookie Policy"
      subtitle="What cookies we use and how you can control them."
    >
      <Prose>
        <h2>What are cookies?</h2>
        <p>
          Cookies are small text files stored on your device. They help the site remember preferences,
          keep you signed in, and understand how pages are used.
        </p>

        <h2>Cookies we use</h2>
        <ul>
          <li>
            <strong>Essential</strong> — login session, cart, security, and load balancing
          </li>
          <li>
            <strong>Preferences</strong> — language, location hints, and UI choices
          </li>
          <li>
            <strong>Analytics</strong> — anonymised usage to improve performance (where enabled)
          </li>
        </ul>

        <h2>Your choices</h2>
        <p>
          When you first visit, a cookie banner lets you accept analytics cookies or continue with
          essential cookies only. You can clear site data in your browser to see the banner again.
          Blocking essential cookies may stop login or checkout from working. For how we handle
          personal data, see our <Link href="/privacy-policy">Privacy Policy</Link>.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
