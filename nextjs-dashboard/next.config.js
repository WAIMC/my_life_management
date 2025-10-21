/** @type {import('next').NextConfig} */
const nextConfig = {
  eslint: {
    // Re-enable ESLint during builds. Address lint issues shown below before merging.
    ignoreDuringBuilds: false,
  },
  async rewrites() {
    // Dev-time proxy: when NEXT_PUBLIC_API_PROXY is 'true', forward /api/* to NEXT_PUBLIC_API_URL
    if (process.env.NEXT_PUBLIC_API_PROXY === 'true' && process.env.NEXT_PUBLIC_API_URL) {
      return [
        {
          source: '/api/:path*',
          destination: `${process.env.NEXT_PUBLIC_API_URL.replace(/\/$/, '')}/:path*`,
        },
      ];
    }

    return [];
  },
};

module.exports = nextConfig;
