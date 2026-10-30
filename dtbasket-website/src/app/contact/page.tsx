import type { Metadata } from "next";
import { FiMail, FiMapPin, FiPhone } from "react-icons/fi";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { TEXT } from "@/branding";

export const metadata: Metadata = {
  title: "Contact Us",
  description: "Contact Nexpress Delivery for orders, support, and business enquiries.",
};

export default function ContactPage() {
  const { phone, phone2, email, address } = TEXT.footer.contact;

  return (
    <StaticPageShell
      title="Contact Us"
      subtitle="We’re here to help with orders, deliveries, and business supply."
    >
      <div className="grid sm:grid-cols-3 gap-4 mb-10">
        {[
          {
            Icon: FiPhone,
            label: "Phone",
            value: (
              <>
                <a href={`tel:${phone.replace(/\s/g, "")}`} className="hover:underline block">
                  {phone}
                </a>
                <a href={`tel:${phone2.replace(/\s/g, "")}`} className="hover:underline block mt-1">
                  {phone2}
                </a>
              </>
            ),
          },
          { Icon: FiMail, label: "Email", value: email, href: `mailto:${email}` },
          { Icon: FiMapPin, label: "Address", value: address },
        ].map(({ Icon, label, value, href }) => (
          <div
            key={label}
            className="rounded-2xl border border-gray-100 dark:border-dm-border bg-primary-50/60 dark:bg-dm-surface2 p-5"
          >
            <div className="w-10 h-10 rounded-full bg-primary-700 text-white flex items-center justify-center mb-3">
              <Icon size={18} />
            </div>
            <p className="text-xs font-bold uppercase tracking-wider text-primary-700 mb-1">{label}</p>
            {href ? (
              <a href={href} className="text-sm font-semibold text-gray-800 dark:text-dm-text hover:underline leading-relaxed">
                {value}
              </a>
            ) : (
              <div className="text-sm font-semibold text-gray-800 dark:text-dm-text leading-relaxed">{value}</div>
            )}
          </div>
        ))}
      </div>

      <div className="rounded-2xl border border-dashed border-primary-200 dark:border-dm-border p-6 sm:p-8 bg-gray-50/80 dark:bg-dm-surface2">
        <h2 className="text-lg font-black text-gray-900 dark:text-dm-text font-display mb-2">
          Support hours
        </h2>
        <p className="text-sm text-gray-600 dark:text-dm-muted leading-relaxed">
          Monday–Thursday 9am–7pm · Friday 9am–5pm. For urgent delivery issues, include your order
          number in the subject line so we can help faster.
        </p>
      </div>
    </StaticPageShell>
  );
}
