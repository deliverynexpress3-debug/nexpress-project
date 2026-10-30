"use client";
import { useState } from "react";
import Link from "next/link";
import { FiMail } from "react-icons/fi";
import { authService } from "@/services/authService";
import toast from "react-hot-toast";
import { TEXT, IMAGES } from "@/branding";

const inputClass =
  "w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-dm-border dark:bg-dm-surface2 dark:text-dm-text rounded-xl text-sm bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all";

export default function ForgotPasswordClient() {
  const [email, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [sent, setSent] = useState(false);

  const handleSubmit = async () => {
    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
    if (!email.trim() || !emailValid) {
      toast.error(TEXT.auth.enterValidEmail);
      return;
    }
    setLoading(true);
    try {
      const res = await authService.forgotPassword(email.trim());
      if (res.status === 200) {
        setSent(true);
        toast.success(TEXT.auth.emailSentTitle);
      } else {
        toast.error(res.message || TEXT.common.error);
      }
    } catch {
      toast.error(TEXT.common.error);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="relative min-h-[calc(100vh-8rem)] flex items-center justify-center px-4 py-10 overflow-hidden">
      <div
        className="absolute inset-0 -z-10 bg-cover bg-center"
        style={{ backgroundImage: `url('${IMAGES.authBg}')` }}
      />
      <div className="absolute inset-0 -z-10 bg-gradient-to-br from-primary-900/75 via-primary-800/70 to-primary-900/80" />

      <div className="w-full max-w-md rounded-3xl overflow-hidden shadow-premium border border-white/20 bg-white/95 dark:bg-dm-surface backdrop-blur-sm animate-fade-in p-8 sm:p-10">
        {!sent ? (
          <>
            <h1 className="text-2xl font-black text-gray-900 dark:text-dm-text font-display mb-1">
              {TEXT.auth.forgotPassword}
            </h1>
            <p className="text-gray-500 dark:text-dm-muted text-sm mb-6">
              {TEXT.auth.forgotPasswordSubtitle}
            </p>

            <div className="relative mb-4">
              <FiMail size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input
                type="email"
                placeholder={TEXT.auth.emailFieldPlaceholder}
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                onKeyDown={(e) => e.key === "Enter" && handleSubmit()}
                className={inputClass}
                autoFocus
              />
            </div>

            <button
              onClick={handleSubmit}
              disabled={loading}
              className="w-full py-3.5 rounded-xl font-bold text-gray-900 text-sm bg-secondary hover:bg-secondary-600 shadow-sm transition-all disabled:opacity-50"
            >
              {loading ? TEXT.common.loading : TEXT.auth.sendResetLink}
            </button>
          </>
        ) : (
          <>
            <h1 className="text-2xl font-black text-gray-900 dark:text-dm-text font-display mb-2">
              {TEXT.auth.emailSentTitle}
            </h1>
            <p className="text-gray-500 dark:text-dm-muted text-sm mb-6 leading-relaxed">
              {TEXT.auth.emailSentDesc(email)}
            </p>
            <button
              type="button"
              onClick={() => {
                setSent(false);
                setEmail("");
              }}
              className="text-sm text-primary-700 font-semibold hover:underline mb-4"
            >
              {TEXT.auth.tryDifferentEmail}
            </button>
          </>
        )}

        <p className="text-center text-sm text-gray-500 dark:text-dm-muted mt-6">
          <Link href="/auth/login" className="text-primary-700 font-semibold hover:underline">
            {TEXT.auth.backToSignIn}
          </Link>
        </p>
      </div>
    </div>
  );
}
