import type { Metadata } from "next";
import DeliveryContent from "@/components/content/DeliveryContent";

export const metadata: Metadata = {
  title: "Delivery",
  description:
    "Nexpress Delivery areas, £3.99 delivery fee, £40 minimum order, 3pm cut-off, and postcode coverage.",
};

export default function DeliveryPage() {
  return <DeliveryContent />;
}
