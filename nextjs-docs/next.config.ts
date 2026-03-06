import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  assetPrefix: "/docs",
  images: {
    unoptimized: true,
  },
  // Silence turbopack/webpack warning
  turbopack: {},
  webpack: (config, { dev, isServer }) => {
    if (dev && !isServer) {
      if (process.env.WATCHPACK_POLLING === 'true') {
        config.watchOptions = {
          poll: 1000,
          aggregateTimeout: 300,
          ignored: /node_modules/,
        };
      }
    }
    return config;
  },
};

export default nextConfig;
