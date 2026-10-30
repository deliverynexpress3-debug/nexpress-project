import type { Metadata, Viewport } from "next";
import { headers } from "next/headers";
import { Nunito, Poppins } from "next/font/google";
import Providers from "./providers";
import { TEXT, CONFIG } from "@/branding";
import LocationProvider from "./LocationProvider";
import "./globals.css";

const nunito = Nunito({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700", "800", "900"],
  variable: "--font-nunito",
  display: "swap",
});

const poppins = Poppins({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700", "800"],
  variable: "--font-poppins",
  display: "swap",
});

export const viewport: Viewport = {
  themeColor: CONFIG.themeColor,
};

const BASE_PATH = process.env.NEXT_PUBLIC_BASE_PATH || "";

async function siteOrigin(): Promise<string> {
  if (process.env.NEXT_PUBLIC_SITE_URL) {
    return process.env.NEXT_PUBLIC_SITE_URL.replace(/\/$/, "");
  }
  const h = await headers();
  const host = h.get("x-forwarded-host") || h.get("host") || "localhost:3000";
  const proto =
    h.get("x-forwarded-proto") ||
    (host.includes("localhost") || host.startsWith("127.") ? "http" : "https");
  return `${proto}://${host}`;
}

export async function generateMetadata(): Promise<Metadata> {
  const origin = await siteOrigin();
  const ogImage = `${origin}${BASE_PATH}/og-image.jpg?v=nexpress3`;

  return {
    metadataBase: new URL(origin),
    title: { default: TEXT.meta.titleDefault, template: TEXT.meta.titleTemplate },
    description: TEXT.meta.description,
    applicationName: TEXT.appName,
    icons: {
      icon: [
        { url: `${BASE_PATH}/favicon.ico?v=nexpress3`, sizes: "any" },
        { url: `${BASE_PATH}/favicon-16x16.png?v=nexpress3`, sizes: "16x16", type: "image/png" },
        { url: `${BASE_PATH}/favicon-32x32.png?v=nexpress3`, sizes: "32x32", type: "image/png" },
        {
          url: `${BASE_PATH}/android-chrome-192x192.png?v=nexpress3`,
          sizes: "192x192",
          type: "image/png",
        },
      ],
      apple: [{ url: `${BASE_PATH}/apple-touch-icon.png?v=nexpress3`, sizes: "180x180" }],
      other: [
        {
          rel: "mask-icon",
          url: `${BASE_PATH}/safari-pinned-tab.svg`,
          color: "#176D78",
        },
      ],
    },
    manifest: "/site.webmanifest",
    openGraph: {
      title: TEXT.meta.titleDefault,
      description: TEXT.meta.description,
      type: "website",
      siteName: TEXT.appName,
      url: origin,
      images: [
        {
          url: ogImage,
          width: 1200,
          height: 630,
          alt: "Nexpress Delivery – Water & drinks delivered",
        },
      ],
    },
    twitter: {
      card: "summary_large_image",
      title: TEXT.meta.titleDefault,
      description: TEXT.meta.description,
      images: [ogImage],
    },
  };
}

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en" className={`${nunito.variable} ${poppins.variable}`}>
      <body className="font-sans" suppressHydrationWarning>
        <Providers>
          <LocationProvider>{children}</LocationProvider>
        </Providers>
      </body>
    </html>
  );
}
