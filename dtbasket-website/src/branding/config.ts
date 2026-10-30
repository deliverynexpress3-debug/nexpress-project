export const CONFIG = {
  // Pagination
  paginationLimit: 10,
  searchSuggestLimit: 6,

  // Date / locale
  dateFormat: "DD/MM/YYYY",
  currency: "GBP",
  currencySymbol: "£",

  // Delivery (UK) — matches nexpressdelivery.co.uk
  freeDeliveryThreshold: Number.POSITIVE_INFINITY, // flat fee always applies
  deliveryFee: 3.99,
  minOrderAmount: 40,

  // Auth / OTP
  otpLength: 6,
  otpExpirySeconds: 60,    // 1 min for web (Flutter uses 2 min but Firebase limits)

  // Splash
  splashDurationMs: 2400,

  // API
  apiTimeout: 20000,
  queryStaleTime: 5 * 60 * 1000, // 5 min

  // Branding
  themeColor: "#FAA62A",       // warm amber secondary
  countryCode: "+44",
  countryFlag: "🇬🇧",

  // Google Maps
  googleMapApiKey: process.env.NEXT_PUBLIC_GOOGLE_MAPS_API_KEY ?? "",

  // Demo mode (matches Flutter Constant.isDemo)
  isDemo: false,
  demoEmail: "user@user.com",
  demoPassword: "123456",
  demoPhone: "9898989898",
} as const;
