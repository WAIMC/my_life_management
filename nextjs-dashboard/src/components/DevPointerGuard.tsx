"use client"

import { useEffect } from "react"

// Dev-only guard: detect if the documentElement or body get pointer-events: none
// and restore them to 'auto'. This is non-destructive and only runs in development
// (localhost or NODE_ENV !== 'production'). It logs fixes to the console.
export default function DevPointerGuard() {
  useEffect(() => {
    if (typeof window === 'undefined') return
    try {
      const isDev = window.location.hostname === 'localhost' || process.env.NODE_ENV !== 'production'
      if (!isDev) return

      // Dedupe and throttle logs to avoid console flooding from repeated restores
      const recentLogs = new Set<string>()
      function logOnce(key: string, ...args: unknown[]) {
        if (recentLogs.has(key)) return
        recentLogs.add(key)
        try {
          // print safely without assuming types
          console.warn(...(args as unknown[]))
        } catch {
          try { console.warn(String(args)) } catch {}
        }
        setTimeout(() => recentLogs.delete(key), 5000)
      }

      const fixIfBlocked = () => {
        try {
          const html = document.documentElement
          const body = document.body
          const htmlPE = getComputedStyle(html).pointerEvents
          const bodyPE = getComputedStyle(body).pointerEvents
          if (htmlPE === 'none') {
            logOnce('[restore-html]', '[DevPointerGuard] html had pointer-events:none — restoring to auto')
            html.style.pointerEvents = 'auto'
          }
          if (bodyPE === 'none') {
            logOnce('[restore-body]', '[DevPointerGuard] body had pointer-events:none — restoring to auto')
            body.style.pointerEvents = 'auto'
          }
        } catch {
          // ignore
        }
      }

      // Run once and then watch for attribute/style changes
      fixIfBlocked()

      const obs = new MutationObserver((mutations) => {
        for (const m of mutations) {
          if (m.type === 'attributes' && (m.attributeName === 'style' || m.attributeName === 'class')) {
            fixIfBlocked()
            break
          }
        }
      })

      obs.observe(document.documentElement, { attributes: true, attributeFilter: ['style', 'class'] })
      obs.observe(document.body, { attributes: true, attributeFilter: ['style', 'class'] })

      // Instrument classList and setAttribute to capture who toggles classes/attributes on body/html
      const originalAdd = DOMTokenList.prototype.add
      const originalRemove = DOMTokenList.prototype.remove
      const originalToggle = DOMTokenList.prototype.toggle
      const originalSetAttribute = Element.prototype.setAttribute

      function makeStack() {
        try {
          const err = new Error()
          return err.stack || ''
        } catch {
          return ''
        }
      }

      DOMTokenList.prototype.add = function (...tokens: string[]) {
        try {
          const host = this as unknown as { ownerElement?: Element }
          const owner = host && host.ownerElement ? host.ownerElement : null
          if (owner === document.body || owner === document.documentElement) {
            if (tokens.some(t => /pointer-events|modal-open|overlay|open|show/.test(t))) {
              logOnce('[classList.add] ' + owner.tagName + ' ' + tokens.join(' '), '[DevPointerGuard] classList.add on', owner.tagName, tokens, '\nStack:', makeStack())
            }
          }
        } catch {
          // ignore
        }
        return originalAdd.apply(this, tokens)
      }

      DOMTokenList.prototype.remove = function (...tokens: string[]) {
        try {
          const host = this as unknown as { ownerElement?: Element }
          const owner = host && host.ownerElement ? host.ownerElement : null
          if (owner === document.body || owner === document.documentElement) {
            if (tokens.some(t => /pointer-events|modal-open|overlay|open|show/.test(t))) {
              logOnce('[classList.remove] ' + owner.tagName + ' ' + tokens.join(' '), '[DevPointerGuard] classList.remove on', owner.tagName, tokens, '\nStack:', makeStack())
            }
          }
        } catch {
          // ignore
        }
        return originalRemove.apply(this, tokens)
      }

      DOMTokenList.prototype.toggle = function (token: string, force?: boolean) {
        try {
          const host = this as unknown as { ownerElement?: Element }
          const owner = host && host.ownerElement ? host.ownerElement : null
          if (owner === document.body || owner === document.documentElement) {
            if (/pointer-events|modal-open|overlay|open|show/.test(token)) {
              logOnce('[classList.toggle] ' + owner.tagName + ' ' + token, '[DevPointerGuard] classList.toggle on', owner.tagName, token, 'force=', force, '\nStack:', makeStack())
            }
          }
        } catch {
          // ignore
        }
        return originalToggle.apply(this, [token, force])
      }

      Element.prototype.setAttribute = function (name: string, value: string) {
        try {
          const owner = this as Element
          if ((owner === document.body || owner === document.documentElement) && (name === 'style' || name === 'class' || name === 'aria-hidden')) {
            if ((value && value.includes('pointer-events')) || name === 'aria-hidden') {
              logOnce('[setAttribute] ' + owner.tagName + ' ' + name, '[DevPointerGuard] setAttribute on', owner.tagName, name, value, '\nStack:', makeStack())
            }
          }
        } catch {
          // ignore
        }
        return originalSetAttribute.apply(this, [name, value])
      }

      const id = setInterval(fixIfBlocked, 1000)
      return () => {
        obs.disconnect()
        clearInterval(id)
        // restore prototypes
        try {
          DOMTokenList.prototype.add = originalAdd
          DOMTokenList.prototype.remove = originalRemove
          DOMTokenList.prototype.toggle = originalToggle
          Element.prototype.setAttribute = originalSetAttribute
        } catch {}
      }
    } catch {
      // ignore
    }
  }, [])

  return null
}
