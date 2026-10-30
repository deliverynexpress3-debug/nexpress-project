import { NextRequest, NextResponse } from "next/server";

/**
 * Same-origin proxy for the Flutter customer API.
 * Browser → /api/customer/* → upstream (avoids CORS / flaky direct HTTPS).
 */
const UPSTREAM =
  process.env.API_BASE_URL ||
  process.env.NEXT_PUBLIC_API_BASE_URL ||
  "https://adminnexpress.tech/api/customer";

function upstreamBase(): string {
  // If client is already pointed at this proxy, use the real host.
  if (UPSTREAM.startsWith("/")) {
    return "https://adminnexpress.tech/api/customer";
  }
  return UPSTREAM.replace(/\/$/, "");
}

async function proxy(req: NextRequest, path: string[]) {
  const targetPath = path.join("/");
  const url = `${upstreamBase()}/${targetPath}${req.nextUrl.search}`;

  const headers: Record<string, string> = {
    Accept: "application/json",
    "Content-Type": "application/json",
  };

  const init: RequestInit = {
    method: req.method,
    headers,
    cache: "no-store",
  };

  if (req.method !== "GET" && req.method !== "HEAD") {
    try {
      init.body = await req.text();
    } catch {
      init.body = undefined;
    }
  }

  try {
    const upstream = await fetch(url, init);
    const text = await upstream.text();
    return new NextResponse(text, {
      status: upstream.status,
      headers: {
        "Content-Type": upstream.headers.get("Content-Type") || "application/json",
        "Cache-Control": "no-store",
      },
    });
  } catch (err) {
    const message = err instanceof Error ? err.message : "Upstream request failed";
    return NextResponse.json(
      { status: 502, message: `API proxy error: ${message}`, result: null },
      { status: 502 }
    );
  }
}

type Ctx = { params: Promise<{ path: string[] }> };

export async function GET(req: NextRequest, ctx: Ctx) {
  const { path } = await ctx.params;
  return proxy(req, path);
}

export async function POST(req: NextRequest, ctx: Ctx) {
  const { path } = await ctx.params;
  return proxy(req, path);
}

export async function PUT(req: NextRequest, ctx: Ctx) {
  const { path } = await ctx.params;
  return proxy(req, path);
}

export async function PATCH(req: NextRequest, ctx: Ctx) {
  const { path } = await ctx.params;
  return proxy(req, path);
}

export async function DELETE(req: NextRequest, ctx: Ctx) {
  const { path } = await ctx.params;
  return proxy(req, path);
}
