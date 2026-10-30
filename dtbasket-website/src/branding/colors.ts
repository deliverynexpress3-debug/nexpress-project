export const COLORS = {
  /* Reference palette — Nexpress Delivery */
  primary: {
    50: "#F0F9FA",
    100: "#D6EDF0",
    200: "#A8DDE5",
    300: "#6DC9D6",
    400: "#45B3C5",
    500: "#2A9DAD",
    600: "#1F7A8A",
    700: "#176D78",   // Aqua Teal (brand)
    800: "#15545C",   // Deep Teal
    900: "#0A2E33",
    DEFAULT: "#176D78",
  },
  /* Warm Amber — CTAs, Search, Cart accents (NOT grey) */
  secondary: {
    50: "#FFF8EB",
    100: "#FEEFC7",
    200: "#FDDD8A",
    300: "#FCC74D",
    400: "#FBB524",
    500: "#FAA62A",   // Warm Amber
    600: "#E08E12",
    700: "#B8710E",
    800: "#8A550C",
    900: "#5C3908",
    DEFAULT: "#FAA62A",
  },
  accent: "#FAA62A",
  splash: "#FAA62A",
  footerBar: "#176D78",
  footerBg: "#F3F6F7",
  background: "#F3F6F7",
  white: "#FFFFFF",
  black: "#000000",
  red: "#EF4444",
  textPrimary: "#172128",
  textSecondary: "#5A6770",

  dm: {
    primary: "#2A9DAD",
    secondary: "#FAA62A",
    background: "#0D0D0D",
    bg: "#0f1a1a",
    footerBg: "#111a1a",
    surface: "#171717",
    surface2: "#202020",
    border: "#2f2f2f",
    text: "#e8f5f5",
    muted: "#ffffff",
  },
} as const;
