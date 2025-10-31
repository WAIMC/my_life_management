"use client"

import React, { useEffect, useState, useMemo, useCallback } from "react"
import { Button } from "@/components/ui/button"
import {
  Dialog,
  DialogTrigger,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from "@/components/ui/dialog"
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card"
import { cn } from "@/lib/utils"
import * as ContextMenu from '@radix-ui/react-context-menu'
import TruncatedText from "@/components/ui/TruncatedText"
import Image from 'next/image'
import { useToast } from '@/components/ui/ToastProvider'
import Spinner from '@/components/ui/spinner'

type FileItem = {
  id: string
  name: string
  type: 'file' | 'folder'
  size?: number
  modified?: string
  url?: string
  parentId?: string
}

type OverlayMode = 'full' | 'container'

export default function FileManagerFixed({ overlayMode = 'full' }: { overlayMode?: OverlayMode }) {
  const [items, setItems] = useState<FileItem[]>([])
  const [path, setPath] = useState<{ id: string; name: string }[]>([{ id: 'root', name: 'root' }])
  const [currentFolderId, setCurrentFolderId] = useState<string>('root')
  const [showUpload, setShowUpload] = useState(false)
  const [showNewFolder, setShowNewFolder] = useState(false)
  const [newFolderName, setNewFolderName] = useState("")
  const [selectedIds, setSelectedIds] = useState<Set<string>>(new Set())
  const [renaming, setRenaming] = useState<{ id: string; name: string } | null>(null)
  const [propertiesItem, setPropertiesItem] = useState<FileItem | null>(null)
  const [propertiesEditingName, setPropertiesEditingName] = useState<string | null>(null)
  const [clipboard, setClipboard] = useState<{ ids: string[]; operation: 'copy' | 'move' | null }>({ ids: [], operation: null })
  const [loading, setLoading] = useState(false)
  const toast = useToast()

  // breadcrumb intentionally unused for now
  useMemo(() => path.map(p => p.name).join(" / "), [path])

  function navigateToPathIndex(index: number) {
    setPath(prev => {
      const next = prev.slice(0, index + 1)
      // set the current folder id to the selected breadcrumb
      if (next.length > 0) setCurrentFolderId(next[next.length - 1].id)
      return next
    })
    // fetchList will run via the effect that tracks currentFolderId
  }

  // load items for a folder id (defaults to currentFolderId)
  const lastLoadRef = React.useRef<{ parentId: string | null; ts: number; controller: AbortController | null }>({ parentId: null, ts: 0, controller: null })

  const loadItems = async (parentId?: string) => {
    const pid = parentId ?? currentFolderId

    // avoid rapid repeated fetches for the same folder
    const now = Date.now()
    if (lastLoadRef.current.parentId === pid && now - lastLoadRef.current.ts < 500) {
      return
    }

    // cancel previous in-flight request if any
    if (lastLoadRef.current.controller) {
      try { lastLoadRef.current.controller.abort() } catch { }
    }
    const controller = new AbortController()
    lastLoadRef.current = { parentId: pid, ts: now, controller }

    setLoading(true)
    try {
      const res = await fetch(`/api/drive?parentId=${encodeURIComponent(pid)}`, { signal: controller.signal })
      const data = await res.json()
      setItems(data.items || [])
    } catch (err) {
      const error = err as unknown
      if ((error as { name?: string }).name === 'AbortError') {
        // aborted - no need to show error
      } else {
        console.error(error)
        toast.push({ type: 'error', message: 'Failed to fetch items' })
      }
    } finally {
      setLoading(false)
      // clear controller if it's still the current one
      if (lastLoadRef.current.controller === controller) lastLoadRef.current.controller = null
    }
  }

  useEffect(() => {
    // load items whenever the current folder id changes
    loadItems()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [currentFolderId])
  

  function toggleSelect(id: string) {
    setSelectedIds(prev => {
      const next = new Set(prev)
      if (next.has(id)) next.delete(id)
      else next.add(id)
      return next
    })
  }

  async function handleUpload(files: FileList | null) {
    if (!files || files.length === 0) return
    const form = new FormData()
    for (const f of Array.from(files)) form.append('files', f)
    form.append('parentId', currentFolderId)
    setLoading(true)
    try {
      const res = await fetch(`/api/drive?parentId=${encodeURIComponent(currentFolderId)}`, { method: 'POST', body: form })
      const data = await res.json()
      setItems(data.items || [])
      toast.push({ type: 'success', message: `Uploaded ${Array.from(files).length} file(s)` })
    } catch (err) {
      console.error(err)
      toast.push({ type: 'error', message: 'Upload failed' })
    } finally {
      setShowUpload(false)
      setLoading(false)
    }
  }

  async function handleCreateFolder() {
    if (!newFolderName.trim()) return
    setLoading(true)
    try {
      const res = await fetch(`/api/drive?action=create-folder&parentId=${encodeURIComponent(currentFolderId)}`, { method: 'POST', body: JSON.stringify({ name: newFolderName.trim() }) })
      const data = await res.json()
      setItems(data.items || [])
      setNewFolderName("")
      setShowNewFolder(false)
      toast.push({ type: 'success', message: 'Folder created' })
    } catch (err) {
      console.error(err)
      toast.push({ type: 'error', message: 'Create folder failed' })
    } finally { setLoading(false) }
  }

  async function handleDelete(ids: string[]) {
    setLoading(true)
    try {
      const res = await fetch('/api/drive?action=delete', { method: 'POST', body: JSON.stringify({ ids }) })
      const data = await res.json()
      // refresh current folder
      const items = data.items.filter((i: FileItem) => (i.parentId || 'root') === currentFolderId)
      setItems(items || [])
      setSelectedIds(prev => {
        const next = new Set(prev)
        ids.forEach(id => next.delete(id))
        return next
      })
      toast.push({ type: 'success', message: `Deleted ${ids.length} item(s)` })
    } catch (err) {
      console.error(err)
      toast.push({ type: 'error', message: 'Delete failed' })
    } finally { setLoading(false) }
  }

  async function handleRename(id: string, name: string) {
    setLoading(true)
    try {
      const res = await fetch('/api/drive?action=rename', { method: 'POST', body: JSON.stringify({ id, name }) })
      const data = await res.json()
      const items = data.items.filter((i: FileItem) => (i.parentId || 'root') === currentFolderId)
      setItems(items || [])
      setRenaming(null)
      toast.push({ type: 'success', message: 'Renamed' })
    } catch (err) {
      console.error(err)
      toast.push({ type: 'error', message: 'Rename failed' })
    } finally { setLoading(false) }
  }

  const bulkSelected = selectedIds.size > 0

  // keyboard shortcuts
  const handleKey = useCallback((e: KeyboardEvent) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'c') {
      // copy
      if (selectedIds.size > 0) setClipboard({ ids: Array.from(selectedIds), operation: 'copy' })
    }
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'x') {
      if (selectedIds.size > 0) setClipboard({ ids: Array.from(selectedIds), operation: 'move' })
    }
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'v') {
      // paste
      if (clipboard.ids.length > 0) {
        ;(async () => {
          setLoading(true)
          try {
            const res = await fetch(`/api/drive?action=copy&parentId=${encodeURIComponent(currentFolderId)}`, { method: 'POST', headers: { 'content-type': 'application/json' }, body: JSON.stringify({ ids: clipboard.ids, operation: clipboard.operation }) })
            const data = await res.json()
            const items = data.items.filter((i: FileItem) => (i.parentId || 'root') === currentFolderId)
            setItems(items || [])
            toast.push({ type: 'success', message: clipboard.operation === 'move' ? 'Moved items' : 'Copied items' })
          } catch (err) {
            console.error(err)
            toast.push({ type: 'error', message: 'Paste failed' })
          } finally { setClipboard({ ids: [], operation: null }); setLoading(false) }
        })()
      }
    }
  }, [selectedIds, clipboard, currentFolderId, toast])

  useEffect(() => {
    window.addEventListener('keydown', handleKey)
    return () => window.removeEventListener('keydown', handleKey)
  }, [handleKey])

  return (
    <Card>
      <CardHeader>
        <CardTitle>File Manager</CardTitle>
      </CardHeader>
  <CardContent className="relative">
        {loading && (
          <div className={`${overlayMode === 'full' ? 'fixed inset-0' : 'absolute inset-0'} z-50 flex items-center justify-center bg-white/60 dark:bg-black/40 pointer-events-auto`}>
            <div className="flex flex-col items-center gap-2">
              <Spinner size="lg" colorClass="border-primary" ariaLabel="File manager loading" />
              <div className="text-sm text-slate-700 dark:text-slate-200">Working...</div>
            </div>
          </div>
        )}
        <div className="mb-3 flex items-center justify-between">
          <nav aria-label="Breadcrumb" className="text-sm text-muted-foreground">
            {path.map((p, i) => (
              <span key={p.id} className="inline-flex items-center">
                {i > 0 && <span className="mx-2">›</span>}
                <button className="text-sm text-primary hover:underline" onClick={() => navigateToPathIndex(i)}>{p.name}</button>
              </span>
            ))}
          </nav>

          <div>{bulkSelected && (
            <div className="ml-4 flex items-center gap-2">
              <Button variant="destructive" size="sm" onClick={() => handleDelete(Array.from(selectedIds))}>Delete ({selectedIds.size})</Button>
              <Button variant="outline" size="sm" onClick={() => setSelectedIds(new Set())}>Clear</Button>
            </div>
          )}</div>
        </div>

        <div className="mb-4 flex items-center gap-2">
          <Dialog open={showUpload} onOpenChange={setShowUpload}>
            <DialogTrigger asChild>
              <Button variant="default" size="sm">Upload</Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle>Upload files</DialogTitle>
                <DialogDescription>Select files to upload to the current folder.</DialogDescription>
              </DialogHeader>
              <div className="mt-4">
                <input type="file" multiple onChange={(e) => handleUpload(e.target.files)} />
              </div>
              <DialogFooter className="mt-4">
                <Button variant="outline" onClick={() => setShowUpload(false)}>Cancel</Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>

    <Dialog open={showNewFolder} onOpenChange={setShowNewFolder}>
            <DialogTrigger asChild>
              <Button variant="outline" size="sm">New Folder</Button>
            </DialogTrigger>
            <DialogContent>
              <DialogHeader>
                <DialogTitle>New folder</DialogTitle>
                <DialogDescription>Create a new folder in the current path.</DialogDescription>
              </DialogHeader>
              <div className="mt-4 grid gap-2">
                <input
                  className="rounded border px-2 py-1 w-full truncate"
                  placeholder="Folder name"
                  value={newFolderName}
                  onChange={(e) => setNewFolderName(e.target.value)}
                />
              </div>
              <DialogFooter className="mt-4">
                <Button variant="default" onClick={handleCreateFolder}>Create</Button>
                <Button variant="outline" onClick={() => setShowNewFolder(false)}>Cancel</Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>

          <Button variant="ghost" size="sm" onClick={() => loadItems()} disabled={loading}>Refresh</Button>
          {loading && (
            <div className="ml-2 flex items-center" aria-hidden>
              <Spinner size="sm" />
            </div>
          )}
          {selectedIds.size > 0 && (
            <div className="ml-2 flex items-center gap-2">
              <Button variant="outline" size="sm" onClick={() => setClipboard({ ids: Array.from(selectedIds), operation: 'copy' })}>Copy selected</Button>
              <Button variant="ghost" size="sm" onClick={() => setClipboard({ ids: Array.from(selectedIds), operation: 'move' })}>Cut selected</Button>
            </div>
          )}
          {clipboard.ids.length > 0 && (
            <div className="flex items-center gap-2">
              <Button variant="default" size="sm" disabled={loading} onClick={async () => {
                // paste
                if (clipboard.ids.length === 0) return
                setLoading(true)
                try {
                  const res = await fetch(`/api/drive?action=copy&parentId=${encodeURIComponent(currentFolderId)}`, { method: 'POST', headers: { 'content-type': 'application/json' }, body: JSON.stringify({ ids: clipboard.ids, operation: clipboard.operation }) })
                  const data = await res.json()
                  const items = data.items.filter((i: FileItem) => (i.parentId || 'root') === currentFolderId)
                  setItems(items || [])
                  toast.push({ type: 'success', message: clipboard.operation === 'move' ? 'Moved items' : 'Copied items' })
                } catch (err) {
                  console.error(err)
                  toast.push({ type: 'error', message: 'Paste failed' })
                } finally { setClipboard({ ids: [], operation: null }); setLoading(false) }
              }}>Paste</Button>
              <Button variant="outline" size="sm" disabled={loading} onClick={() => setClipboard({ ids: [], operation: null })}>Clear clipboard</Button>
            </div>
          )}
        </div>

        <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
          {items.map(it => (
            <ContextMenu.Root key={it.id}>
              <ContextMenu.Trigger asChild>
                <div className="block">
                  <div
                    className={cn("p-3 rounded border bg-card hover:shadow cursor-pointer min-w-0 overflow-hidden", selectedIds.has(it.id) ? 'ring-2 ring-primary' : '')}
                    onDoubleClick={() => {
                      if (it.type === 'folder') {
                        setPath(prev => [...prev, { id: it.id, name: it.name }])
                        setCurrentFolderId(it.id)
                      }
                    }}
                  >
                    <div className="flex items-center justify-between mb-2">
                      <div className="flex items-center gap-2 min-w-0">
                        <input type="checkbox" checked={selectedIds.has(it.id)} onChange={() => toggleSelect(it.id)} />
                        <div className="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded flex items-center justify-center text-sm font-medium">
                          {it.type === 'folder' ? '📁' : (it.url ? (
                            <div className="w-8 h-8 relative">
                              <Image src={it.url} alt={it.name} width={32} height={32} className="rounded object-cover" />
                            </div>
                          ) : '📄')}
                        </div>
                        <div className="ml-2 flex-1 min-w-0">
                          <TruncatedText text={it.name} className="text-sm font-medium" maxWidthClass="max-w-[16rem]" />
                        </div>
                      </div>
                      {/* modified time hidden per request */}
                    </div>
                    <div className="text-xs text-muted-foreground">{it.size ? `${Math.round(it.size/1024)} KB` : ''}</div>
                  </div>
                </div>
              </ContextMenu.Trigger>

              <ContextMenu.Portal>
                  <ContextMenu.Content className="rounded-md border bg-popover p-1 shadow data-[state=open]:pointer-events-auto data-[state=closed]:pointer-events-none" data-state="closed">
                  <ContextMenu.Item className="px-3 py-1 text-sm cursor-pointer" onSelect={() => setRenaming({ id: it.id, name: it.name })}>Rename</ContextMenu.Item>
                  <ContextMenu.Item className="px-3 py-1 text-sm cursor-pointer" onSelect={() => setPropertiesItem(it)}>Properties</ContextMenu.Item>
                  <ContextMenu.Item className="px-3 py-1 text-sm cursor-pointer" onSelect={() => {
                    setClipboard({ ids: [it.id], operation: 'copy' })
                  }}>Copy</ContextMenu.Item>
                  <ContextMenu.Item className="px-3 py-1 text-sm cursor-pointer" onSelect={() => {
                    setClipboard({ ids: [it.id], operation: 'move' })
                  }}>Cut</ContextMenu.Item>
                  <ContextMenu.Item className="px-3 py-1 text-sm cursor-pointer text-destructive" onSelect={() => handleDelete([it.id])}>Delete</ContextMenu.Item>
                  {it.type === 'folder' && (
                    <ContextMenu.Item className="px-3 py-1 text-sm cursor-pointer" onSelect={() => {
                      // open folder
                      setPath(prev => [...prev, { id: it.id, name: it.name }])
                      setCurrentFolderId(it.id)
                    }}>Open</ContextMenu.Item>
                  )}
                </ContextMenu.Content>
              </ContextMenu.Portal>
            </ContextMenu.Root>
          ))}
        </div>

  <Dialog open={!!renaming} onOpenChange={(v) => { if (!v) setRenaming(null) }}>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Rename</DialogTitle>
              <DialogDescription>Rename the selected item.</DialogDescription>
            </DialogHeader>
            <div className="mt-4 grid gap-2">
              <input
                className="rounded border px-2 py-1 w-full truncate"
                value={renaming?.name ?? ''}
                onChange={(e) => setRenaming(prev => prev ? { ...prev, name: e.target.value } : prev)}
              />
            </div>
            <DialogFooter className="mt-4">
              <Button variant="default" onClick={() => renaming && handleRename(renaming.id, renaming.name)}>Rename</Button>
              <Button variant="outline" onClick={() => setRenaming(null)}>Cancel</Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>

  <Dialog open={!!propertiesItem} onOpenChange={(v) => { if (!v) setPropertiesItem(null) }}>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Properties</DialogTitle>
              <DialogDescription>Details for the selected item.</DialogDescription>
            </DialogHeader>

            <div className="mt-4 grid gap-2 text-sm">
              <div className="flex items-center gap-4">
                <span className="text-muted-foreground">Name</span>
                <div className="flex-1">
                  {propertiesEditingName !== null ? (
                    <input className="w-full rounded border px-2 py-1" value={propertiesEditingName} onChange={(e) => setPropertiesEditingName(e.target.value)} />
                  ) : (
                    <span className="truncate block">{propertiesItem?.name}</span>
                  )}
                </div>
                <div>
                  {propertiesEditingName !== null ? (
                    <Button size="sm" variant="default" onClick={() => {
                      if (propertiesItem) handleRename(propertiesItem.id, propertiesEditingName)
                      setPropertiesEditingName(null)
                    }}>Save</Button>
                  ) : (
                    <Button size="sm" variant="outline" onClick={() => setPropertiesEditingName(propertiesItem?.name ?? '')}>Edit</Button>
                  )}
                </div>
              </div>
              <div className="flex justify-between"><span className="text-muted-foreground">Type</span><span>{propertiesItem?.type}</span></div>
              <div className="flex justify-between"><span className="text-muted-foreground">Size</span><span>{propertiesItem?.size ? `${Math.round(propertiesItem.size/1024)} KB` : '-'}</span></div>
              <div className="flex justify-between"><span className="text-muted-foreground">Modified</span><span>{propertiesItem?.modified ?? '-'}</span></div>
              <div className="flex justify-between"><span className="text-muted-foreground">Path</span><span>{path.join('/')}</span></div>
            </div>

            {propertiesItem?.url && propertiesItem.type === 'file' && (
              <div className="mt-4">
                <div className="text-sm text-muted-foreground">Preview</div>
                <div className="mt-2">
                  <div className="max-w-xs max-h-40">
                    <Image src={propertiesItem.url} alt={propertiesItem.name} width={320} height={160} className="object-contain rounded" />
                  </div>
                </div>
              </div>
            )}

            <DialogFooter className="mt-4">
              <Button variant="default" onClick={() => setPropertiesItem(null)}>Close</Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>
      </CardContent>
    </Card>
  )
}
