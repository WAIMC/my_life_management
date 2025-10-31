"use client"

import React, { useEffect, useRef, useState } from 'react'
import Tooltip from './tooltip'

type Props = {
  text: string
  className?: string
  maxWidthClass?: string
}

export default function TruncatedText({ text, className = '', maxWidthClass = '' }: Props) {
  const ref = useRef<HTMLDivElement | null>(null)
  const [truncated, setTruncated] = useState(false)

  useEffect(() => {
    function check() {
      const el = ref.current
      if (!el) return
      setTruncated(el.scrollWidth > el.clientWidth)
    }
    check()
    window.addEventListener('resize', check)
    return () => window.removeEventListener('resize', check)
  }, [text])

  const content = (
    <div
      ref={ref}
      className={`${className} ${maxWidthClass} truncate overflow-hidden whitespace-nowrap`}
      aria-label={text}
    >
      {text}
    </div>
  )

  return (
    <div className="flex items-center gap-1 min-w-0">
      {truncated ? (
        <Tooltip content={text}>
          {content}
        </Tooltip>
      ) : (
        content
      )}
      {truncated && (
        <span className="text-xs text-muted-foreground" aria-hidden>…</span>
      )}
    </div>
  )
}
