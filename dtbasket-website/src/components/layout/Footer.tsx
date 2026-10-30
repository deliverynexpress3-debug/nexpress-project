"use client";
import Link from "next/link";
import { useState } from "react";
import {
  FiMail, FiPhone, FiMapPin,
  FiFacebook, FiLinkedin, FiTwitter, FiInstagram,
} from "react-icons/fi";
import { TEXT, IMAGES } from "@/branding";
import SafeImage from "@/components/ui/SafeImage";

const paymentIcons = ["VISA", "Mastercard", "Amex"];

const COMPANY_LINKS = [
  { label: "Home", href: "/" },
  { label: "My Account", href: "/account" },
  { label: "About Us", href: "/about" },
  { label: "Contact Us", href: "/contact" },
  { label: "Delivery", href: "/delivery" },
  { label: "FAQ", href: "/faq" },
  { label: "Order Tracking", href: "/tracking" },
  { label: "Cookie Policy", href: "/cookie-policy" },
  { label: "Terms & Conditions", href: "/terms" },
  { label: "Privacy Policy", href: "/privacy-policy" },
] as const;

export default function Footer() {
  const [email, setEmail] = useState("");
  const [subscribed, setSubscribed] = useState(false);

  const handleSubscribe = (e: React.FormEvent) => {
    e.preventDefault();
    if (email.trim()) {
      setSubscribed(true);
      setEmail("");
      setTimeout(() => setSubscribed(false), 2500);
    }
  };

  return (
    <footer>
      <div className="py-12 bg-gray-800 dark:bg-dm-footerBg text-white">
        <div className="container-custom grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

          <div className="flex flex-col items-center">
            <div className="dark:bg-secondary dark:rounded-lg dark:pl-2 dark:pr-2 dark:pt-1 dark:pb-1 mb-4">
              <SafeImage
                src={IMAGES.appLogo}
                alt="Nexpress logo"
                width={724}
                height={188}
                className="w-36 h-auto object-contain"
                priority
              />
            </div>
            <p className="text-sm text-white/60 leading-relaxed text-center max-w-[240px]">
              {TEXT.footer.tagline}
            </p>
          </div>

          <div>
            <h3 className="font-bold text-base mb-4">{TEXT.footer.company}</h3>
            <ul className="space-y-2.5">
              {COMPANY_LINKS.map((link) => (
                <li key={link.href}>
                  <Link href={link.href} className="text-sm text-white/70 hover:text-white transition-colors">
                    {link.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h3 className="font-bold text-base mb-4">{TEXT.footer.contactUs}</h3>
            <div className="space-y-4">
              <div className="flex items-start gap-3">
                <FiMapPin size={18} className="text-white/60 flex-shrink-0 mt-0.5" />
                <span className="text-sm text-white/70 leading-relaxed">{TEXT.footer.contact.address}</span>
              </div>
              <div className="flex items-start gap-3">
                <FiPhone size={18} className="text-white/60 flex-shrink-0 mt-0.5" />
                <span className="text-sm text-white/70 leading-relaxed">
                  <a href={`tel:${TEXT.footer.contact.phone.replace(/\s/g, "")}`} className="hover:text-white transition-colors">
                    {TEXT.footer.contact.phone}
                  </a>
                  <br />
                  <a href={`tel:${TEXT.footer.contact.phone2.replace(/\s/g, "")}`} className="hover:text-white transition-colors">
                    {TEXT.footer.contact.phone2}
                  </a>
                </span>
              </div>
              <div className="flex items-start gap-3">
                <FiMail size={18} className="text-white/60 flex-shrink-0 mt-0.5" />
                <a href={`mailto:${TEXT.footer.contact.email}`} className="text-sm text-white/70 hover:text-white transition-colors">
                  {TEXT.footer.contact.email}
                </a>
              </div>
            </div>
            <div className="mt-6">
              <p className="font-bold text-sm mb-2 text-white/90">Policies</p>
              <ul className="space-y-2">
                <li><Link href="/shipping-policy" className="text-sm text-white/70 hover:text-white transition-colors">Shipping Policy</Link></li>
                <li><Link href="/refund-policy" className="text-sm text-white/70 hover:text-white transition-colors">Refund Policy</Link></li>
              </ul>
            </div>
          </div>

          <div>
            <h3 className="font-bold text-base mb-4">{TEXT.footer.newsletter}</h3>
            <form onSubmit={handleSubscribe}>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder={TEXT.footer.emailPlaceholder}
                className="w-full px-3 py-2.5 bg-white/10 border border-white/20 rounded-lg text-sm text-white placeholder-white/50 focus:outline-none focus:border-white/40 mb-3"
              />
              <button type="submit"
                className="px-5 py-2 rounded-lg bg-primary-500 hover:bg-primary-400 text-white text-sm font-semibold transition-colors">
                {subscribed ? "Subscribed ✓" : TEXT.footer.subscribe}
              </button>
            </form>

            <div className="mt-8">
              <p className="font-bold text-base mb-4">{TEXT.footer.socialMedia}</p>
              <div className="flex flex-wrap gap-3">
                {[
                  { Icon: FiFacebook, href: TEXT.social.facebook, label: "Facebook" },
                  { Icon: FiTwitter, href: TEXT.social.twitter, label: "Twitter" },
                  { Icon: FiInstagram, href: TEXT.social.instagram, label: "Instagram" },
                  { Icon: FiLinkedin, href: TEXT.social.linkedin, label: "LinkedIn" },
                  { Icon: FiMail, href: `mailto:${TEXT.footer.contact.email}`, label: "Email" },
                ]
                  .filter((s) => Boolean(s.href))
                  .map(({ Icon, href, label }) => (
                  <a
                    key={label}
                    href={href}
                    target={href.startsWith("mailto:") ? undefined : "_blank"}
                    rel={href.startsWith("mailto:") ? undefined : "noopener noreferrer"}
                    aria-label={label}
                    className="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center text-white/80 hover:bg-white/10 hover:text-white transition-colors"
                  >
                    <Icon size={16} />
                  </a>
                ))}
              </div>

              <div className="mt-8">
                <p className="text-xs text-white/40 uppercase tracking-wider mb-3">We Accept</p>
                <div className="flex flex-wrap gap-2">
                  {paymentIcons.map((p) => (
                    <span key={p} className="px-2.5 py-1 rounded bg-white/10 text-[10px] font-bold text-white/70">
                      {p}
                    </span>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="py-4 bg-gray-800 dark:bg-dm-footerBg border-t border-white/10">
        <div className="container-custom text-center">
          <p className="text-xs text-white/50">{TEXT.footer.copyright}</p>
          <p className="text-xs text-white/40 mt-1">{TEXT.footer.poweredBy}</p>
        </div>
      </div>
    </footer>
  );
}
