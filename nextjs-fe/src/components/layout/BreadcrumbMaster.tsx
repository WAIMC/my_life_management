import React from 'react';
import { Breadcrumb } from 'antd';

export default function BreadcrumbMaster(): React.JSX.Element {
  return (
    <Breadcrumb
      items={[
        {
          title: 'Home',
        },
        {
          title: <a href=''>Application Center</a>,
        },
        {
          title: <a href=''>Application List</a>,
        },
        {
          title: 'An Application',
        },
      ]}
    />
  );
}
