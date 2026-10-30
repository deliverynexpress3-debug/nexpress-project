"use client";

import { useMemo, useState } from "react";
import Link from "next/link";
import StaticPageShell from "@/components/layout/StaticPageShell";
import { Prose } from "@/components/content/Prose";
import {
  DELIVERY_FEE,
  DELIVERY_POSTCODES,
  DELIVERY_RULES_COPY,
  DELIVERY_WINDOW,
  isDeliverablePostcode,
  MIN_ORDER_AMOUNT,
  normalizePostcodeDisplay,
} from "@/lib/delivery";

export default function DeliveryContent() {
  const [query, setQuery] = useState("");
  const check = useMemo(() => {
    const q = query.trim();
    if (q.length < 2) return null;
    return {
      display: normalizePostcodeDisplay(q),
      ok: isDeliverablePostcode(q),
    };
  }, [query]);

  return (
    <StaticPageShell
      title="Delivery"
      subtitle="Areas, fees, cut-off times, and how drinks arrive at your door."
    >
      <Prose>
        <h2>Delivery rules</h2>
        <ul>
          <li>{DELIVERY_RULES_COPY.fee}</li>
          <li>{DELIVERY_RULES_COPY.minimum}</li>
          <li>{DELIVERY_RULES_COPY.cutoff}</li>
          <li>{DELIVERY_RULES_COPY.friday}</li>
          <li>{DELIVERY_RULES_COPY.window}</li>
        </ul>

        <h2>Check your postcode</h2>
        <p>Enter the first part of your postcode (e.g. NW4) or a full postcode (e.g. HA4 6SZ).</p>
      </Prose>

      <div className="my-6 max-w-md">
        <input
          type="text"
          value={query}
          onChange={(e) => setQuery(e.target.value.toUpperCase())}
          placeholder="e.g. HA4 6SZ"
          className="w-full px-4 py-3 rounded-xl border-2 border-gray-200 dark:border-dm-border dark:bg-dm-surface2 dark:text-dm-text text-sm uppercase tracking-wide focus:outline-none focus:border-primary-500"
        />
        {check && (
          <p
            className={`mt-3 text-sm font-semibold ${
              check.ok ? "text-green-700" : "text-amber-800"
            }`}
          >
            {check.ok
              ? `Yes — we deliver to ${check.display}. Delivery £${DELIVERY_FEE.toFixed(2)}, £${MIN_ORDER_AMOUNT} minimum.`
              : `${check.display} is not on our standard list. Call 020 8445 0589 / 020 8445 0680 — coverage is expanding.`}
          </p>
        )}
      </div>

      <Prose>
        <h2>Timing notes</h2>
        <ul>
          <li>Delivery window for all areas: {DELIVERY_WINDOW}</li>
          <li>No Saturday, Sunday, or bank/public holiday deliveries</li>
          <li>
            For a two-hour window on the day, call{" "}
            <a href="tel:02084450589">020 8445 0589</a> or{" "}
            <a href="tel:02084450680">020 8445 0680</a>
          </li>
          <li>
            Track progress on the <Link href="/tracking">Tracking</Link> page
          </li>
        </ul>

        <h2>Postcode districts we cover</h2>
        <p>
          Some districts are only partially covered — please call before ordering if unsure. Areas
          expand over time.
        </p>
      </Prose>

      <div className="mt-4 grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
        {DELIVERY_POSTCODES.map((pc) => (
          <span
            key={pc}
            className="text-center text-xs font-semibold text-gray-700 dark:text-dm-text bg-gray-50 dark:bg-dm-surface2 border border-gray-100 dark:border-dm-border rounded-lg py-1.5"
          >
            {pc}
          </span>
        ))}
      </div>

      <Prose>
        <h2 className="!mt-10">Missed delivery</h2>
        <p>
          If we cannot complete a delivery, our team will contact you to rearrange. Goods may be
          left in your absence at the driver’s discretion unless you instruct otherwise when
          ordering.
        </p>
      </Prose>
    </StaticPageShell>
  );
}
