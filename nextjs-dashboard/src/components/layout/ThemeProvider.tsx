"use client";

import { ThemeProvider as NextThemeProvider } from "next-themes";
import React from "react";

type Props = {
  children: React.ReactNode;
};

export default function ThemeProvider({ children }: Props) {
  return (
    <NextThemeProvider attribute="class" defaultTheme="light" enableSystem>
      {children}
    </NextThemeProvider>
  );
}
