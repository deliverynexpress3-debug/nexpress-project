"use client";

import { useEffect, useState } from "react";
import Link from "next/link";

const STORAGE_KEY = "nexpress_cookie_consent";

type Consent = "accepted" | "essential";

/**
 * UK-style cookie banner for essential + optional analytics cookies.
 * Analytics scripts should only load when consent === "accepted".
 */
export default function CookieConsent() {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    try {
      const saved = localStorage.getItem(STORAGE_KEY) as Consent | null;
      if (!saved) setVisible(true);
    } catch {
      setVisible(true);
    }
  }, []);

  const save = (value: Consent) => {
    try {
      localStorage.setItem(STORAGE_KEY, value);
    } catch {
      /* ignore */
    }
    setVisible(false);
  };

  if (!visible) return null;

  return (
    <div
      role="dialog"
      aria-label="Cookie consent"
      className="fixed bottom-0 inset-x-0 z-[80] p-4 sm:p-6 pointer-events-none"
    >
      <div className="pointer-events-auto mx-auto max-w-3xl rounded-2xl bg-gray-900 text-white shadow-2xl border border-white/10 p-5 sm:p-6">
        <p className="text-sm font-bold mb-1">We use cookies</p>
        <p className="text-sm text-white/75 leading-relaxed mb-4">
          Essential cookies keep the site working (login, cart, preferences). With your OK we may
          also use analytics cookies to understand how the site is used. See our{" "}
          <Link href="/cookie-policy" className="underline text-secondary hover:text-white">
            Cookie Policy
          </Link>{" "}
          and{" "}
          <Link href="/privacy-policy" className="underline text-secondary hover:text-white">
            Privacy Policy
          </Link>
          .
        </p>
        <div className="flex flex-col sm:flex-row gap-2 sm:justify-end">
          <button
            type="button"
            onClick={() => save("essential")}
            className="px-4 py-2.5 rounded-xl text-sm font-semibold border border-white/25 hover:bg-white/10 transition-colors"
          >
            Essential only
          </button>
          <button
            type="button"
            onClick={() => save("accepted")}
            className="px-4 py-2.5 rounded-xl text-sm font-bold bg-secondary text-gray-900 hover:bg-secondary-600 transition-colors"
          >
            Accept all
          </button>
        </div>
      </div>
    </div>
  );
}
