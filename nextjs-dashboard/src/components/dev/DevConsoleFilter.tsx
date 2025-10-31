"use client"

import { useEffect } from "react"

export default function DevConsoleFilter() {
  useEffect(() => {
    if (process.env.NODE_ENV !== 'development') return

    const originalError = console.error.bind(console)

    console.error = (...args: unknown[]) => {
      try {
        const first = args[0]
        const text = typeof first === 'string' ? first : (first && String(first))
        // Filter React 19 deprecation about element.ref
        if (typeof text === 'string' && (
          text.includes('Accessing element.ref was removed in React 19') ||
          text.includes('ref is now a regular prop') ||
          (text.includes('element.ref') && text.includes('React 19'))
        )) {
          return
        }
      } catch {
        // swallow
      }
  originalError(...(args as unknown[]))
    }

    return () => { console.error = originalError }
  }, [])

  return null
}
