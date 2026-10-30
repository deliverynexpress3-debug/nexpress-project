"use client";
import { useEffect, useState, useCallback } from "react";
import {
  isDeliverablePostcode,
  normalizePostcodeDisplay,
  outwardPostcode,
} from "@/lib/delivery";

export interface UserLocation {
  lat: number;
  lng: number;
  city?: string;
  area?: string;
  postcode?: string;
  deliverable?: boolean;
}

interface NominatimAddress {
  city?: string;
  town?: string;
  village?: string;
  suburb?: string;
  neighbourhood?: string;
  county?: string;
  postcode?: string;
}
interface NominatimResponse { address?: NominatimAddress; }

interface GoogleGeocodeComponent { types: string[]; long_name: string; short_name: string; }
interface GoogleGeocodeResponse {
  status: string;
  results: { address_components: GoogleGeocodeComponent[] }[];
}

const STORAGE_KEY = "user_location";
export const LOCATION_PROMPT_KEY = "location_prompt_seen";

async function reverseGeocode(lat: number, lng: number, apiKey?: string): Promise<Partial<UserLocation>> {
  if (apiKey) {
    const res = await fetch(
      `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${apiKey}`
    );
    const data: GoogleGeocodeResponse = await res.json();
    if (data.status === "OK") {
      const components = data.results?.[0]?.address_components ?? [];
      const postcode =
        components.find((c) => c.types.includes("postal_code"))?.long_name ?? "";
      return {
        city: components.find((c) => c.types.includes("locality"))?.long_name ?? "",
        area: components.find((c) => c.types.includes("sublocality"))?.long_name ?? "",
        postcode: postcode ? normalizePostcodeDisplay(postcode) : undefined,
        deliverable: postcode ? isDeliverablePostcode(postcode) : undefined,
      };
    }
  }
  const res = await fetch(
    `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`,
    { headers: { "Accept-Language": "en" } }
  );
  const data: NominatimResponse = await res.json();
  const addr = data.address ?? {};
  const postcode = addr.postcode ?? "";
  return {
    city: addr.city ?? addr.town ?? addr.village ?? addr.county ?? "",
    area: addr.suburb ?? addr.neighbourhood ?? "",
    postcode: postcode ? normalizePostcodeDisplay(postcode) : undefined,
    deliverable: postcode ? isDeliverablePostcode(postcode) : undefined,
  };
}

export function useLocation() {
  const [location, setLocationState] = useState<UserLocation | null>(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) {
      try { setLocationState(JSON.parse(saved) as UserLocation); } catch { /* ignore */ }
    }
  }, []);

  const detectLocation = useCallback(async (apiKey?: string): Promise<{ ok: boolean; errorCode?: number }> => {
    if (!navigator.geolocation) return { ok: false, errorCode: 0 };
    setLoading(true);
    return new Promise((resolve) => {
      let resolved = false;
      let errorTimer: ReturnType<typeof setTimeout> | null = null;

      navigator.geolocation.getCurrentPosition(
        async (pos) => {
          if (errorTimer) { clearTimeout(errorTimer); errorTimer = null; }
          if (resolved) return;
          resolved = true;
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          resolve({ ok: true });
          try {
            const geo = await reverseGeocode(lat, lng, apiKey);
            const loc: UserLocation = { lat, lng, ...geo };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(loc));
            localStorage.setItem(LOCATION_PROMPT_KEY, "1");
            setLocationState(loc);
          } catch {
            const loc: UserLocation = { lat, lng };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(loc));
            localStorage.setItem(LOCATION_PROMPT_KEY, "1");
            setLocationState(loc);
          } finally {
            setLoading(false);
          }
        },
        (err) => {
          errorTimer = setTimeout(() => {
            if (resolved) return;
            resolved = true;
            setLoading(false);
            resolve({ ok: false, errorCode: err.code });
          }, 300);
        }
      );
    });
  }, []);

  const setManualLocation = useCallback((city: string, area: string, lat?: number, lng?: number) => {
    const loc: UserLocation = { lat: lat ?? 0, lng: lng ?? 0, city, area };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(loc));
    localStorage.setItem(LOCATION_PROMPT_KEY, "1");
    setLocationState(loc);
  }, []);

  /** Save a UK postcode (primary flow for Nexpress). */
  const setPostcode = useCallback((raw: string) => {
    const display = normalizePostcodeDisplay(raw);
    const deliverable = isDeliverablePostcode(raw);
    const loc: UserLocation = {
      lat: 0,
      lng: 0,
      postcode: display,
      city: outwardPostcode(raw) || display,
      area: "",
      deliverable,
    };
    localStorage.setItem(STORAGE_KEY, JSON.stringify(loc));
    localStorage.setItem(LOCATION_PROMPT_KEY, "1");
    setLocationState(loc);
    return { display, deliverable };
  }, []);

  const markPromptSeen = useCallback(() => {
    localStorage.setItem(LOCATION_PROMPT_KEY, "1");
  }, []);

  const clearLocation = useCallback(() => {
    localStorage.removeItem(STORAGE_KEY);
    setLocationState(null);
  }, []);

  return {
    location,
    loading,
    detectLocation,
    setManualLocation,
    setPostcode,
    markPromptSeen,
    clearLocation,
  };
}
