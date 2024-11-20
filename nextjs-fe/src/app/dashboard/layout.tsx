'use client';

import React from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import FooterMaster from '@/components/layout/FooterMaster';
import SidebarMaster from '@/components/layout/sidebarMaster';
import HeaderMaster from '@/components/layout/HeaderMaster';
import BreadcrumbMaster from '@/components/layout/BreadcrumbMaster';

export default function DashboardLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <Layout hasSider>
      <SidebarMaster />
      <Layout style={{ marginInlineStart: 200 }}>
        <HeaderMaster />
        <BreadcrumbMaster />
        <Content style={{ margin: '24px 16px 0', overflow: 'initial' }}>
          {children}
          {
            // indicates very long content
            Array.from({ length: 100 }, (_, index) => (
              <React.Fragment key={index}>
                {index % 20 === 0 && index ? 'more' : '...'}
                <br />
              </React.Fragment>
            ))
          }
        </Content>
        <FooterMaster />
      </Layout>
    </Layout>
  );
}
