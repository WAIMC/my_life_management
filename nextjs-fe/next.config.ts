import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  output: 'standalone',           // Quan trọng nhất để image nhỏ
  experimental: {
    // Tùy chọn tăng tốc build thêm (Next 16+)
    // buildCache: true,          // Next 16.0+ hỗ trợ cache build
    // turbotrace: true,
  },
  /* config options here */
  reactCompiler: true,
  
  // Enable Turbopack with default settings (Next.js 16+)
  // Turbopack automatically handles file watching, including in Docker
  turbopack: {},
};

export default nextConfig;
