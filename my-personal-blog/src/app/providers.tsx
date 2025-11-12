'use client';

import { Provider } from 'react-redux';
import { Toaster } from 'react-hot-toast';
import { makeStore } from '../redux/store';
import { setAppStore } from '@/lib/apiInstance';
import { useMemo } from 'react';

export function Providers({ children }: { children: React.ReactNode }) {
    const store = useMemo(() => {
        const newStore = makeStore();
        setAppStore(newStore);
        return newStore;
    }, []);
    
    return (
        <Provider store={store}>
            {children}
            <Toaster
                position="top-right"
                reverseOrder={false}
                gutter={8}
                toastOptions={{
                    duration: 4000,
                    style: {
                        background: '#fff',
                        color: '#000',
                    },
                    success: {
                        style: {
                            background: '#ecfdf5',
                            color: '#065f46',
                            border: '1px solid #86efac',
                        },
                    },
                    error: {
                        style: {
                            background: '#fef2f2',
                            color: '#7f1d1d',
                            border: '1px solid #fca5a5',
                        },
                    },
                }}
            />
        </Provider>
    );
}
