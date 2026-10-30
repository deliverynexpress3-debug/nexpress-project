/**
 * Nexpress Delivery UK rules — mirrored from nexpressdelivery.co.uk/shipping.html
 */

export const DELIVERY_FEE = 3.99;
export const MIN_ORDER_AMOUNT = 40;
export const ORDER_CUTOFF_HOUR = 15; // 3pm previous working day
export const DELIVERY_WINDOW = "12 noon–6pm";
export const DELIVERY_LEAD = "1–2 working days";

/** Outward postcode districts we deliver to (district part only, e.g. HA4, NW4). */
export const DELIVERY_POSTCODES = [
  "AL2",
  "BR1", "BR2", "BR3", "BR4", "BR5", "BR6", "BR7", "BR8",
  "CM13", "CM14", "CM16",
  "CR0", "CR2", "CR3", "CR4", "CR5", "CR6", "CR7", "CR8",
  "DA1", "DA2", "DA5", "DA6", "DA7", "DA8", "DA14", "DA15", "DA16", "DA17", "DA18",
  "E1", "E1W", "E2", "E3", "E4", "E5", "E6", "E7", "E8", "E9",
  "E10", "E11", "E12", "E13", "E14", "E15", "E16", "E17", "E18", "E20",
  "EC1", "EC2", "EC3", "EC4",
  "EN1", "EN2", "EN3", "EN4", "EN5", "EN6", "EN7", "EN8", "EN9",
  "HA0", "HA1", "HA2", "HA3", "HA4", "HA5", "HA6", "HA7", "HA8", "HA9",
  "IG1", "IG2", "IG3", "IG4", "IG5", "IG6", "IG7", "IG8", "IG9", "IG10", "IG11",
  "KT1", "KT2", "KT3", "KT4", "KT5", "KT6", "KT7", "KT8", "KT9",
  "KT10", "KT11", "KT12", "KT13", "KT14", "KT15", "KT16", "KT17", "KT18", "KT19",
  "KT20", "KT21", "KT22",
  "N1", "N2", "N3", "N4", "N5", "N6", "N7", "N8", "N9",
  "N10", "N11", "N12", "N13", "N14", "N15", "N16", "N17", "N18", "N19",
  "N20", "N21", "N22",
  "NW1", "NW2", "NW3", "NW4", "NW5", "NW6", "NW7", "NW8", "NW9", "NW10", "NW11",
  "RH1", "RH2", "RH8", "RH9",
  "RM1", "RM2", "RM3", "RM4", "RM5", "RM6", "RM7", "RM8", "RM9",
  "RM10", "RM11", "RM12", "RM13", "RM14", "RM15", "RM16", "RM17", "RM18", "RM19", "RM20",
  "SE1", "SE2", "SE3", "SE4", "SE5", "SE6", "SE7", "SE8", "SE9",
  "SE10", "SE11", "SE12", "SE13", "SE14", "SE15", "SE16", "SE17", "SE18", "SE19",
  "SE20", "SE21", "SE22", "SE23", "SE24", "SE25", "SE26", "SE27", "SE28",
  "SL0", "SL9",
  "SM1", "SM2", "SM3", "SM4", "SM5", "SM6", "SM7",
  "SW1", "SW2", "SW3", "SW4", "SW5", "SW6", "SW7", "SW8", "SW9",
  "SW10", "SW11", "SW12", "SW13", "SW14", "SW15", "SW16", "SW17", "SW18", "SW19", "SW20",
  "TN14", "TN16",
  "TW1", "TW2", "TW3", "TW4", "TW5", "TW6", "TW7", "TW8", "TW9",
  "TW10", "TW11", "TW12", "TW13", "TW14", "TW15", "TW16", "TW17", "TW18", "TW19", "TW20",
  "UB1", "UB2", "UB3", "UB4", "UB5", "UB6", "UB7", "UB8", "UB9", "UB10", "UB11",
  "W1", "W2", "W3", "W4", "W5", "W6", "W7", "W8", "W9",
  "W10", "W11", "W12", "W13", "W14",
  "WC1", "WC2",
  "WD3", "WD4", "WD5", "WD6", "WD7", "WD17", "WD18", "WD19", "WD23", "WD24", "WD25",
] as const;

const POSTCODE_SET = new Set(
  DELIVERY_POSTCODES.map((p) => p.toUpperCase())
);

/** Normalise "ha4 6sz" / "HA46SZ" → outward district "HA4" */
export function outwardPostcode(raw: string): string {
  const cleaned = raw.toUpperCase().replace(/[^A-Z0-9]/g, "");
  if (cleaned.length < 2) return "";
  // UK format: outward (2–4) + inward (3). Prefer last 3 as inward when long enough.
  if (cleaned.length > 3) {
    return cleaned.slice(0, cleaned.length - 3);
  }
  return cleaned;
}

export function normalizePostcodeDisplay(raw: string): string {
  const cleaned = raw.toUpperCase().replace(/[^A-Z0-9]/g, "");
  if (cleaned.length > 3) {
    return `${cleaned.slice(0, cleaned.length - 3)} ${cleaned.slice(-3)}`;
  }
  return cleaned;
}

export function isDeliverablePostcode(raw: string): boolean {
  const outward = outwardPostcode(raw);
  if (!outward) return false;
  if (POSTCODE_SET.has(outward)) return true;
  // Some districts listed as EC1 / W1 / SW1 cover sub-districts like EC1A, W1A, SW1A
  for (const district of POSTCODE_SET) {
    if (outward.startsWith(district) && outward.length <= district.length + 1) {
      return true;
    }
  }
  return false;
}

export const DELIVERY_RULES_COPY = {
  fee: `Flat delivery charge of £${DELIVERY_FEE.toFixed(2)} (inc. VAT) on every order.`,
  minimum: `Minimum order value £${MIN_ORDER_AMOUNT} (inc. VAT).`,
  cutoff: `Orders must be placed by ${ORDER_CUTOFF_HOUR > 12 ? `${ORDER_CUTOFF_HOUR - 12}pm` : `${ORDER_CUTOFF_HOUR}am`} the previous working day for delivery within ${DELIVERY_LEAD}.`,
  window: `Delivery times are ${DELIVERY_WINDOW}, Monday–Friday. No weekend or bank-holiday deliveries.`,
  friday: "Orders placed after 3pm on Friday are processed on Monday, then delivered within 1–2 working days.",
} as const;
