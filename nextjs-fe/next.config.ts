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
};

export default nextConfig;
