import React from 'react';
import { Layout } from 'antd';

const { Footer } = Layout;

export default function FooterMaster(): React.JSX.Element {
  return (
    <Footer style={{ textAlign: 'center' }}>
      VINH ©{new Date().getFullYear()} Created by Ant UED
    </Footer>
  );
}
