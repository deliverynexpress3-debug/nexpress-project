"use client";
import Link from "next/link";
import Image from "next/image";
import { FiFacebook, FiTwitter } from "react-icons/fi";
import { TEXT } from "@/branding";
import { useAppSettings } from "@/lib/settingsContext";

/** Always-on brand socials (shown even if admin social API is empty). */
const FALLBACK_SOCIALS = [
  {
    id: "facebook",
    name: "Facebook",
    url: TEXT.social.facebook,
    Icon: FiFacebook,
  },
  {
    id: "twitter",
    name: "Twitter / X",
    url: TEXT.social.twitter,
    Icon: FiTwitter,
  },
] as const;

export default function TopBar() {
  const { socialLinks } = useAppSettings();

  return (
    <div className="hidden md:block text-white text-xs py-2 bg-primary-700 dark:bg-dm-primary">
      <div className="container-custom flex items-center justify-between">
        {/* Left — nav links */}
        <div className="flex items-center gap-5">
          <Link href="/account" className="hover:text-green-200 transition-colors font-medium">
            {TEXT.nav.myAccount}
          </Link>
          <Link href="/wishlist" className="hover:text-green-200 transition-colors font-medium">
            {TEXT.nav.wishlist}
          </Link>
          <Link href="/tracking" className="hover:text-green-200 transition-colors font-medium">
            {TEXT.nav.orderTracking}
          </Link>
        </div>

        {/* Center */}
        <p className="font-medium text-white/90 hidden md:block">{TEXT.topBar.welcome}</p>

        {/* Right — Follow Us + icons */}
        <div className="flex items-center gap-2.5">
          <span className="font-medium">{TEXT.nav.followUs}</span>

          {/* Brand Facebook + Twitter (always) */}
          {FALLBACK_SOCIALS.map(({ id, name, url, Icon }) => (
            <a
              key={id}
              href={url}
              target="_blank"
              rel="noopener noreferrer"
              aria-label={name}
              className="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/35 transition-colors"
            >
              <Icon size={12} className="text-white" />
            </a>
          ))}

          {/* Extra links from admin (skip FB/X duplicates) */}
          {socialLinks
            .filter((data) => {
              const n = (data.name || "").toLowerCase();
              const u = (data.url || "").toLowerCase();
              // Skip brand socials we already show, and unverified LinkedIn / generic handles
              if (n.includes("facebook") || n.includes("twitter") || u.includes("facebook.com") || u.includes("x.com") || u.includes("twitter.com")) return false;
              if (n.includes("linkedin") || u.includes("linkedin.com")) return false;
              if (u.includes("instagram.com/nexpress") && !u.includes("nexpressdeliveryuk")) return false;
              if (u.includes("play.google.com") || u.includes("dtbasket")) return false;
              return Boolean(u);
            })
            .map((data) => (
              <a
                key={data.id}
                href={data.url}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={data.name}
                className="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/35 transition-colors"
              >
                {data.image ? (
                  <Image src={data.image} alt={data.name} width={20} height={20} className="w-3 h-3 object-contain" />
                ) : (
                  <span className="text-[9px] font-bold">{data.name.slice(0, 1)}</span>
                )}
              </a>
            ))}
        </div>
      </div>
    </div>
  );
}
