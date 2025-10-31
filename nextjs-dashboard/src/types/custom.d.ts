// Minimal ambient module declarations for third-party libs without types

// Basic JSX global types if TS config doesn't include React types (safe no-op)
declare namespace JSX {
  interface IntrinsicElements { [elemName: string]: unknown; }
}
