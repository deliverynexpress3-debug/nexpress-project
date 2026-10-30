import Header from "./Header";
import Footer from "./Footer";
import TopBar from "./TopBar";
import CartDrawer from "@/components/cart/CartDrawer";
import { COLORS } from "@/branding";

interface Props {
  children: React.ReactNode;
  bgImage?: string;   // override per-page, defaults to global bg
}



export default function MainLayout({ children, bgImage }: Props) {
  /* Empty string disables pattern; undefined uses clean brand wash (no vegetable wallpaper). */
  const usePattern = bgImage === undefined ? false : Boolean(bgImage);
  const bg = bgImage || undefined;

  return (
    <div className="flex flex-col min-h-screen dark:bg-dm-background relative">
      <div
        className="absolute inset-0 -z-10 dark:hidden"
        style={{
          backgroundImage: usePattern && bg ? `url('${bg}')` : undefined,
          backgroundRepeat: usePattern ? "repeat" : undefined,
          backgroundSize: usePattern ? "1200px 800px" : undefined,
          backgroundColor: COLORS.background,
        }}
      />

      <TopBar />
      <Header />
      <main className="flex-1">{children}</main>
      <Footer />
      <CartDrawer />
    </div>
  );
}