'use client';

import { Provider } from 'react-redux';
import { makeStore } from '../redux/store';
import { useMemo } from 'react';

export function Providers({ children }: { children: React.ReactNode }) {
    const store = useMemo(() => makeStore(), []);
    return <Provider store={store}>{children}</Provider>;
}
