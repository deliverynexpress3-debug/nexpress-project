import type { Metadata } from "next";
import StaticPageShell from "@/components/layout/StaticPageShell";
import TrackingClient from "./TrackingClient";

export const metadata: Metadata = {
  title: "Order Tracking",
  description: "Track your Nexpress Delivery order from warehouse to door.",
};

export default function TrackingPage() {
  return (
    <StaticPageShell
      title="Order Tracking"
      subtitle="Follow your Nexpress delivery from warehouse to your door."
    >
      <TrackingClient />
    </StaticPageShell>
  );
}
