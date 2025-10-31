"use client"

import React from 'react'

type TooltipProps = {
  content: React.ReactNode
  children: React.ReactNode
}

export default function Tooltip({ content, children }: TooltipProps) {
  return (
    <div className="relative inline-block group">
      {children}
      <div className="pointer-events-none invisible group-hover:visible group-hover:opacity-100 opacity-0 transition-opacity absolute z-50 left-1/2 -translate-x-1/2 mt-2 whitespace-nowrap rounded bg-neutral-900 text-white text-xs px-2 py-1">
        {content}
      </div>
    </div>
  )
}
