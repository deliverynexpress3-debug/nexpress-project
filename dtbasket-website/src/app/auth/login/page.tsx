import type { Metadata } from "next";
import MainLayout from "@/components/layout/MainLayout";
import LoginClient from "@/components/auth/LoginClient";

export const metadata: Metadata = { title: "Sign In" };

export default function LoginPage() {
  return (
    <MainLayout bgImage="">
      <LoginClient />
    </MainLayout>
  );
}
