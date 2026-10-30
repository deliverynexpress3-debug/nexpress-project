import type { Metadata } from "next";
import MainLayout from "@/components/layout/MainLayout";
import AccountClient from "@/components/auth/AccountClient";

export const metadata: Metadata = { title: "My Account" };

export default function AccountPage() {
  return (
    <MainLayout bgImage="">
      <AccountClient />
    </MainLayout>
  );
}
