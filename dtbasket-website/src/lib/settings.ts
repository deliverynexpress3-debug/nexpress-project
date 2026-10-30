/**
 * settings.ts
 * Parses the flat key/value array from /general_setting API
 * into a strongly-typed object used across the web app.
 */

import { CONFIG } from "@/branding";
import { GeneralSettingItem } from "@/types";

export interface AppSettings {
    appName: string;
    appVersion: string;
    email: string;
    contact: string;
    website: string;
    appDescription: string;
    appLogo: string;
    currency: string;
    currencyCode: string;        // display symbol e.g. £
    companyName: string;
    companyLogo: string;
    deliveryCharges: number;
    handlingCharges: number;
    isDeliveryCharges: boolean;
    isHandlingCharges: boolean;
    googleMapApiKey: string;
    pageBgColor: string;
    pageTitleColor: string;
}

/** Converts flat [{key, value}] array → typed AppSettings object */
export function parseSettings(items: GeneralSettingItem[]): AppSettings {
    const map: Record<string, string> = {};
    items.forEach(i => { map[i.key] = i.value; });

    return {
        // Brand overrides — admin/API may still hold DTBasket / India demo values
        appName: "Nexpress Delivery",
        appVersion: map.app_version ?? "1.0.0",
        email: "info@nexpressdelivery.co.uk",
        contact: "020 8445 0589",
        website: map.website ?? "",
        appDescription:
          "Order bottled water, soft drinks and beverages online with Nexpress Delivery.",
        appLogo: map.app_logo ?? "",
        currency: CONFIG.currency,
        currencyCode: CONFIG.currencySymbol,
        companyName: "Nexpress Delivery Limited",
        companyLogo: map.company_logo ?? "",
        deliveryCharges: CONFIG.deliveryFee,
        handlingCharges: Number(map.handling_charges ?? 0),
        isDeliveryCharges: true,
        isHandlingCharges: map.is_handling_charges === "on",
        googleMapApiKey: map.google_map_api_key ?? "",
        pageBgColor: map.page_background_color ?? "",
        pageTitleColor: map.page_title_color ?? "",
    };
}

/** Default settings (used before API loads) */
export const DEFAULT_SETTINGS: AppSettings = {
    appName: "Nexpress Delivery",
    appVersion: "1.0.0",
    email: "info@nexpressdelivery.co.uk",
    contact: "020 8445 0589",
    website: "",
    appDescription: "",
    appLogo: "",
    currency: CONFIG.currency,
    currencyCode: CONFIG.currencySymbol,
    companyName: "Nexpress Delivery Limited",
    companyLogo: "",
    deliveryCharges: CONFIG.deliveryFee,
    handlingCharges: 0,
    isDeliveryCharges: true,
    isHandlingCharges: false,
    googleMapApiKey: "",
    pageBgColor: "",
    pageTitleColor: "",
};
