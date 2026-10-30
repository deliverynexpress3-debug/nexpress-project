import type { Metadata } from "next";
import MainLayout from "@/components/layout/MainLayout";
import ForgotPasswordClient from "@/components/auth/ForgotPasswordClient";

export const metadata: Metadata = { title: "Forgot Password" };

export default function ForgotPasswordPage() {
  return (
    <MainLayout bgImage="">
      <ForgotPasswordClient />
    </MainLayout>
  );
}
