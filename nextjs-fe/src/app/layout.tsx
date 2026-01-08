'use client';

import { useEffect } from 'react';
import "./globals.css";
import { Providers } from './providers';
import { useTranslations } from 'next-intl';

function MetadataManager() {
  const t = useTranslations('metadata');

  useEffect(() => {
    document.title = t('title');
    
    // Add or update meta description
    let metaDescription = document.querySelector('meta[name="description"]');
    if (!metaDescription) {
      metaDescription = document.createElement('meta');
      metaDescription.setAttribute('name', 'description');
      document.head.appendChild(metaDescription);
    }
    metaDescription.setAttribute('content', t('description'));
  }, [t]);

  return null;
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" suppressHydrationWarning>
      <body className="font-sans antialiased">
        <Providers>
          <MetadataManager />
          {children}
        </Providers>
      </body>
    </html>
  );
}
