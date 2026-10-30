"use client";

import { useState } from "react";
import { FiChevronDown } from "react-icons/fi";
import { cn } from "@/lib/utils";

const FAQS = [
  {
    q: "What does Nexpress deliver?",
    a: "We specialise in bottled water, soft drinks, juices, and related beverages for homes and businesses. Browse Shop by category on the home page for the full range.",
  },
  {
    q: "How do I track my order?",
    a: "Use Order Tracking in the header or visit the Tracking page. Signed-in customers can follow live status under My Account → Orders.",
  },
  {
    q: "What are your delivery times?",
    a: "Orders placed by 3pm the previous working day are normally delivered within 1–2 working days, Monday–Friday between 12 noon and 6pm. No weekend or bank-holiday deliveries. See the Delivery page for your postcode.",
  },
  {
    q: "Can I cancel or change an order?",
    a: "Contact us as soon as possible with your order number. If the order has not left the warehouse, we can usually amend or cancel it.",
  },
  {
    q: "Do you deliver to businesses?",
    a: "Yes. Many offices and hospitality sites use Nexpress for regular water and soft-drink drops. Contact us for larger or recurring orders.",
  },
  {
    q: "How do refunds work?",
    a: "If an item arrives damaged or incorrect, tell us within 24 hours with photos. See our Refund Policy for full details.",
  },
  {
    q: "Is there a minimum order?",
    a: "Yes — £40 (inc. VAT) minimum. Delivery is a flat £3.99 (inc. VAT) regardless of order size.",
  },
  {
    q: "How do I create an account?",
    a: "Tap Account in the header to register with email or sign in with your mobile number. You can also shop as a guest where available.",
  },
];

function FaqItem({ q, a }: { q: string; a: string }) {
  const [open, setOpen] = useState(false);
  return (
    <div className="border border-gray-100 dark:border-dm-border rounded-2xl overflow-hidden bg-gray-50/80 dark:bg-dm-surface2">
      <button
        type="button"
        onClick={() => setOpen((v) => !v)}
        className="w-full flex items-center justify-between gap-3 px-5 py-4 text-left"
        aria-expanded={open}
      >
        <span className="font-bold text-gray-900 dark:text-dm-text text-sm sm:text-base">{q}</span>
        <FiChevronDown
          className={cn(
            "flex-shrink-0 text-primary-700 transition-transform duration-200",
            open && "rotate-180"
          )}
          size={18}
        />
      </button>
      {open ? (
        <div className="px-5 pb-4 text-sm text-gray-600 dark:text-dm-muted leading-relaxed border-t border-gray-100 dark:border-dm-border pt-3">
          {a}
        </div>
      ) : null}
    </div>
  );
}

export default function FaqAccordion() {
  return (
    <div className="space-y-3">
      {FAQS.map((item) => (
        <FaqItem key={item.q} q={item.q} a={item.a} />
      ))}
    </div>
  );
}
