import SafeImage from "@/components/ui/SafeImage";
import { IMAGES } from "@/branding";

export default function AppDownloadBanner() {
  return (
    <div className="w-full mt-10 rounded-2xl overflow-hidden">
      <SafeImage
        src={IMAGES.footerImage}
        alt="Nexpress delivery platform features"
        width={1200}
        height={600}
        className="w-full h-auto object-cover"
        priority
      />
    </div>
  );
}
