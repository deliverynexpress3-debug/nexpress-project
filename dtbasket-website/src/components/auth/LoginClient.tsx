"use client";
import { useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";
import { FiMail, FiLock, FiEye, FiEyeOff } from "react-icons/fi";
import { authService } from "@/services/authService";
import { useAuthStore } from "@/store/authStore";
import toast from "react-hot-toast";
import { TEXT, IMAGES } from "@/branding";

const inputClass =
  "w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-dm-border dark:bg-dm-surface2 dark:text-dm-text rounded-xl text-sm bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-100 transition-all";

export default function LoginClient() {
  const router = useRouter();
  const { login } = useAuthStore();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [showPass, setShowPass] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (!email.trim()) { toast.error("Enter your email"); return; }
    if (!password.trim()) { toast.error("Enter your password"); return; }
    setLoading(true);
    try {
      const res = await authService.normalLogin(email.trim(), password);
      if (res.status === 200 && res.result?.[0]) {
        const user = authService.persistUser(res.result[0]);
        login(user, String(res.result[0].id));
        toast.success(TEXT.auth.loginSuccess);
        router.push("/");
      } else {
        toast.error(res.message || "Invalid email or password");
      }
    } catch {
      toast.error("Login failed. Please try again.");
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

      <div className="w-full max-w-4xl grid md:grid-cols-2 rounded-3xl overflow-hidden shadow-premium border border-white/20 bg-white/95 dark:bg-dm-surface backdrop-blur-sm animate-fade-in">
        <div className="relative hidden md:flex flex-col justify-between p-8 lg:p-10 text-white bg-gradient-to-br from-primary-700 to-primary-900">
          <div>
            <p className="text-secondary font-black text-sm tracking-wide uppercase mb-3">Nexpress Delivery</p>
            <h2 className="text-3xl font-black font-display leading-tight mb-3">
              Welcome back
            </h2>
            <p className="text-white/75 text-sm leading-relaxed">
              Sign in to continue ordering bottled water, soft drinks, tea, coffee and household essentials.
            </p>
          </div>
          <ul className="space-y-2.5 text-sm text-white/85 mt-8">
            <li className="flex items-center gap-2"><span className="w-1.5 h-1.5 rounded-full bg-secondary" /> Secure account access</li>
            <li className="flex items-center gap-2"><span className="w-1.5 h-1.5 rounded-full bg-secondary" /> Saved cart &amp; wishlist</li>
            <li className="flex items-center gap-2"><span className="w-1.5 h-1.5 rounded-full bg-secondary" /> Faster checkout</li>
          </ul>
        </div>

        <div className="flex flex-col justify-center p-8 sm:p-10">
          <h1 className="text-2xl font-black text-gray-900 dark:text-dm-text font-display mb-1">
            {TEXT.auth.welcomeBack}
          </h1>
          <p className="text-gray-500 dark:text-dm-muted text-sm mb-6">{TEXT.auth.loginSubtitle}</p>

          <div className="space-y-4">
            <div className="relative">
              <FiMail size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input type="email" placeholder={TEXT.auth.emailFieldPlaceholder} value={email} onChange={(e) => setEmail(e.target.value)}
                className={inputClass} autoFocus />
            </div>

            <div className="relative">
              <FiLock size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input type={showPass ? "text" : "password"} placeholder={TEXT.auth.passwordFieldPlaceholder} value={password} onChange={(e) => setPassword(e.target.value)}
                onKeyDown={(e) => e.key === "Enter" && handleLogin()}
                className={`${inputClass} pr-10`} />
              <button type="button" onClick={() => setShowPass(!showPass)} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                {showPass ? <FiEyeOff size={16} /> : <FiEye size={16} />}
              </button>
            </div>

            <div className="flex justify-end">
              <Link
                href="/auth/forgot-password"
                className="text-sm font-semibold text-primary-700 hover:text-primary-800 hover:underline"
              >
                {TEXT.auth.forgotPassword}
              </Link>
            </div>

            <button
              onClick={handleLogin}
              disabled={loading}
              className="w-full py-3.5 rounded-xl font-bold text-gray-900 text-sm bg-secondary hover:bg-secondary-600 shadow-sm transition-all hover:-translate-y-px disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {loading ? TEXT.auth.signingIn : TEXT.auth.signInBtn}
            </button>
          </div>

          <p className="text-center text-sm text-gray-500 dark:text-dm-muted mt-5">
            {TEXT.auth.noAccount}{" "}
            <Link href="/auth/register" className="text-primary-700 font-semibold hover:text-primary-800">
              {TEXT.auth.registerNow}
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
}
