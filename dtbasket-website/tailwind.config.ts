import type { Config } from "tailwindcss";
import { COLORS } from "./src/branding/colors";

const config: Config = {
  content: ["./src/**/*.{js,ts,jsx,tsx,mdx}"],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        dm: COLORS.dm,
        primary: {
          50: COLORS.primary[50],
          100: COLORS.primary[100],
          200: COLORS.primary[200],
          300: COLORS.primary[300],
          400: COLORS.primary[400],
          500: COLORS.primary[500],
          600: COLORS.primary[600],
          700: COLORS.primary[700],
          800: COLORS.primary[800],
          900: COLORS.primary[900],
          DEFAULT: COLORS.primary.DEFAULT,
        },
        secondary: {
          50: COLORS.secondary[50],
          100: COLORS.secondary[100],
          200: COLORS.secondary[200],
          300: COLORS.secondary[300],
          400: COLORS.secondary[400],
          500: COLORS.secondary[500],
          600: COLORS.secondary[600],
          700: COLORS.secondary[700],
          800: COLORS.secondary[800],
          900: COLORS.secondary[900],
          DEFAULT: COLORS.secondary.DEFAULT,
        },
        accent: COLORS.accent,
        footerBar: COLORS.footerBar,
        footerBg: COLORS.footerBg,
      },
      fontFamily: {
        sans: ["Nunito", "system-ui", "sans-serif"],
        display: ["Poppins", "system-ui", "sans-serif"],
      },
      boxShadow: {
        card: "0 2px 8px rgba(23,109,120,0.06), 0 1px 2px rgba(0,0,0,0.04)",
        "card-hover": "0 10px 28px rgba(23,109,120,0.12)",
        nav: "0 2px 12px rgba(23,109,120,0.08)",
        premium: "0 8px 30px rgba(23,109,120,0.10)",
      },
      borderRadius: { xl: "0.75rem", "2xl": "1rem", "3xl": "1.25rem" },
    },
  },
  plugins: [],
};

export default config;
