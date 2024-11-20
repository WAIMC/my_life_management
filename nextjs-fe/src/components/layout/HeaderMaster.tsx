import React from 'react';
import { Layout } from 'antd';

const { Header } = Layout;

export default function HeaderMaster(): React.JSX.Element {
  return <Header className='gray-3' style={{ padding: 0 }} />;
}
