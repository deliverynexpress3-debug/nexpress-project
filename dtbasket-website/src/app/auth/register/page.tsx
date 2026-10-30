import type { Metadata } from "next";
import MainLayout from "@/components/layout/MainLayout";
import RegisterClient from "@/components/auth/RegisterClient";

export const metadata: Metadata = { title: "Create Account" };

export default function RegisterPage() {
  return (
    <MainLayout bgImage="">
      <RegisterClient />
    </MainLayout>
  );
}
