import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // ===================================================
  // FULL CLIENT-SIDE RENDERING (CSR) CONFIGURATION
  // ===================================================
  // This Next.js app is configured as a Single Page Application (SPA)
  // with full client-side rendering. No server-side features are used.
  
  // Export as static SPA - generates static HTML/CSS/JS files
  // All pages are pre-rendered at build time into the 'out/' directory
  // No Node.js server required - can be deployed to any static hosting
  output: 'export',
  
  // Disable Next.js image optimization (requires server)
  // Images will be served as-is without optimization
  images: {
    unoptimized: true,
  },
  
  // Disable server-side features
  experimental: {
    // Build optimizations can be added here
  },
  
  // Enable React Compiler for better performance
  reactCompiler: true,
  
  // Enable Turbopack for faster development builds
  turbopack: {},
  
  // Optional: Set base path if deploying to subdirectory
  // basePath: '/my-app',
  
  // Optional: Control trailing slashes
  // trailingSlash: true,
};

export default nextConfig;
