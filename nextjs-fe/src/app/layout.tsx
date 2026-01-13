import "./globals.css";
import { Providers } from './providers';
import { getMessages } from 'next-intl/server';
import { MetadataManager } from '@/components/common/metadata-manager';

export default async function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  // Providing all messages to the client
  // side is the easiest way to get started
  const messages = await getMessages();

  return (
    <html lang="en" suppressHydrationWarning>
      <body className="font-sans antialiased">
        <Providers messages={messages}>
          <MetadataManager />
          {children}
        </Providers>
      </body>
    </html>
  );
}
