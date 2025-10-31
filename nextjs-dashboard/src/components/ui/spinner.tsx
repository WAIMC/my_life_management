"use client"

import React from 'react'

type Props = {
  size?: 'xs' | 'sm' | 'md' | 'lg'
  className?: string
  colorClass?: string
  ariaLabel?: string
  /** stroke width for the spinner border: 1, 2, or 4 (px equivalent) */
  strokeWidth?: 1 | 2 | 4
  /** animation variant */
  variant?: 'spin' | 'pulse' | 'bounce'
}

export default function Spinner({ size = 'sm', className = '', colorClass = 'border-slate-400', ariaLabel = 'loading', strokeWidth = 2, variant = 'spin' }: Props) {
  const sizes: Record<string, string> = {
    xs: 'w-3 h-3',
    sm: 'w-4 h-4',
    md: 'w-6 h-6',
    lg: 'w-8 h-8',
  }

  const strokeClass = strokeWidth === 1 ? 'border' : strokeWidth === 4 ? 'border-4' : 'border-2'
  const animClass = variant === 'pulse' ? 'animate-pulse' : variant === 'bounce' ? 'animate-bounce' : 'animate-spin'

  return (
    <span role="status" aria-label={ariaLabel} className={`inline-flex items-center justify-center ${className}`}>
      <span className={`rounded-full ${sizes[size]} ${strokeClass} border-t-transparent ${animClass} ${colorClass}`} />
    </span>
  )
}
