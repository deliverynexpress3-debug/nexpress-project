import { notFound } from "next/navigation";
import HomePageContent from "@/components/home/HomePageContent";

/**
 * Optional home filter by catalog type id (digits only).
 * Any other single-segment path falls through to the real 404 page.
 */
export default async function TypePage({
  params,
}: {
  params: Promise<{ typeId: string }>;
}) {
  const { typeId } = await params;

  if (!/^\d+$/.test(typeId)) {
    notFound();
  }

  return <HomePageContent typeId={typeId} />;
}
