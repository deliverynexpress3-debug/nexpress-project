import type { Metadata } from "next";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";
import { TEXT } from "@/branding";

export const metadata: Metadata = {
  title: "Privacy Policy",
  description: "How Nexpress Delivery collects, uses, and protects your personal data.",
};

export default function PrivacyPolicyPage() {
  const co = TEXT.footer.companyLegal;

  return (
    <StaticPageShell
      title="Privacy Policy"
      subtitle="How we collect, use, and look after your personal information."
    >
      <Prose>
        <p>Last updated: {new Date().toLocaleDateString("en-GB", { year: "numeric", month: "long", day: "numeric" })}</p>

        <h2>1. Who we are</h2>
        <p>
          This website is operated by <strong>{co.name}</strong> (&quot;we&quot;, &quot;us&quot;), a
          private limited company registered in England and Wales under company number{" "}
          <strong>{co.companyNumber}</strong>.
        </p>
        <ul>
          <li>
            <strong>Registered office:</strong> {co.address}
          </li>
          <li>
            <strong>Email:</strong>{" "}
            <a href={`mailto:${co.email}`}>{co.email}</a>
          </li>
          <li>
            <strong>Telephone:</strong> {co.phones}
          </li>
        </ul>
        <p>
          We are the data controller for personal data collected through our website and related
          services.
        </p>

        <h2>2. ICO registration</h2>
        <p>{co.icoNote}</p>
        <p>
          You can also complain to the{" "}
          <a href="https://ico.org.uk/" target="_blank" rel="noopener noreferrer">
            Information Commissioner&apos;s Office (ICO)
          </a>{" "}
          if you are unhappy with how we have used your data.
        </p>

        <h2>3. Data we collect</h2>
        <ul>
          <li>Identity and contact details (name, email, phone, delivery address)</li>
          <li>Order and payment-related information (processed via secure providers)</li>
          <li>Account credentials and preferences</li>
          <li>Device, usage, and cookie data when you browse our site</li>
        </ul>

        <h2>4. How we use your data</h2>
        <ul>
          <li>To fulfil orders and provide customer support</li>
          <li>To improve the website, app, and delivery experience</li>
          <li>To send service updates and, where allowed, marketing</li>
          <li>To meet legal and fraud-prevention obligations</li>
        </ul>

        <h2>5. Sharing</h2>
        <p>
          We share data with delivery partners, payment processors, and IT providers only as needed
          to run the service. We do not sell your personal data.
        </p>

        <h2>6. Cookies</h2>
        <p>
          We use cookies and similar technologies for essential site functions and, where you
          consent, analytics. See our <Link href="/cookie-policy">Cookie Policy</Link> and the
          on-site cookie banner for details and choices.
        </p>

        <h2>7. Retention &amp; security</h2>
        <p>
          We keep data only as long as needed for the purposes above, then delete or anonymise it.
          We apply appropriate technical and organisational security measures.
        </p>

        <h2>8. Your rights</h2>
        <p>
          Under UK GDPR you may have rights to access, correct, delete, or restrict processing of
          your data, and to object to certain uses. Contact us using the details above to exercise
          these rights.
        </p>

        <h2>9. Contact</h2>
        <p>
          Privacy questions: email{" "}
          <a href={`mailto:${co.email}`}>{co.email}</a>, call {co.phones}, or use{" "}
          <Link href="/contact">Contact Us</Link>.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
