import { NextResponse } from 'next/server'
import fs from 'fs'
import path from 'path'
import formidable from 'formidable'

type FileItem = {
  id: string
  name: string
  type: 'file' | 'folder'
  size?: number
  modified?: string
  url?: string
  parentId?: string
}

// In-memory store (dev only)
let store: FileItem[] = [
  { id: '1', name: 'Photos', type: 'folder', modified: '2025-10-01', parentId: 'root' },
  { id: '2', name: 'Resume.pdf', type: 'file', size: 54321, modified: '2025-09-20', parentId: 'root' },
  { id: '3', name: 'Project.zip', type: 'file', size: 1234567, modified: '2025-08-11', parentId: 'root' },
]

// helper to ensure a unique name within a parent folder
function ensureUniqueName(parentId: string, name: string, type: 'file' | 'folder') {
  const siblings = store.filter(s => (s.parentId || 'root') === parentId && s.type === type).map(s => s.name)
  if (!siblings.includes(name)) return name
  // try suffixes: _copy, _copy2, _copy3...
  let idx = 1
  let base = name
  // if name already has (copy) or _copy style from previous copy operations, normalize base
  const copyMatch = name.match(/^(.*?)(?:\s*\(copy(?:([0-9]+))?\)|_copy(?:([0-9]+))?)$/)
  if (copyMatch) base = copyMatch[1]
  let candidate = `${base}_copy`
  while (siblings.includes(candidate)) {
    idx += 1
    candidate = `${base}_copy${idx}`
  }
  return candidate
}

export async function GET(req: Request) {
  const url = new URL(req.url)
  const parentId = url.searchParams.get('parentId') || 'root'
  const items = store.filter(i => (i.parentId || 'root') === parentId)
  return NextResponse.json({ items })
}

export async function POST(req: Request) {
  const url = new URL(req.url)
  const action = url.searchParams.get('action')
  const contentType = req.headers.get('content-type') || ''

  // If multipart form-data (real file upload), parse and save files
  if (contentType.includes('multipart/form-data')) {
  const uploadDir = path.join(process.cwd(), 'public', 'uploads')
    if (!fs.existsSync(uploadDir)) fs.mkdirSync(uploadDir, { recursive: true })

    const form = formidable({ multiples: true, keepExtensions: true, uploadDir })

    const parseForm = () => new Promise<{ fields: Record<string, string | string[]>; files: Record<string, unknown> }>((resolve, reject) => {
      form.parse(req, (err: Error | null, fields: Record<string, string | string[]>, files: Record<string, unknown>) => {
        if (err) return reject(err)
        resolve({ fields, files })
      })
    })

    try {
    const { files, fields } = await parseForm()
    const parentId = (fields.parentId && String(fields.parentId)) || 'root'
    const saved: FileItem[] = []
  const fileEntries = Object.values(files).flat() as Array<{ filepath: string; originalFilename?: string; newFilename?: string; size: number }>
      for (const f of fileEntries) {
        // f.filepath is where formidable saved the file
        const originalName = f.originalFilename || f.newFilename || 'file'
        const ext = path.extname(originalName)
        const destName = `${Date.now()}-${Math.round(Math.random()*1e6)}${ext}`
        const destPath = path.join(uploadDir, destName)
        // move file from temp path to destPath
        fs.renameSync(f.filepath, destPath)
        const uniqueName = ensureUniqueName(parentId, originalName, 'file')
        const item: FileItem = {
          id: String(Date.now()) + Math.random(),
          name: uniqueName,
          type: 'file',
          size: f.size,
          modified: new Date().toISOString().slice(0,10),
          url: `/uploads/${destName}`,
          parentId,
        }
        saved.push(item)
      }
      store = [...saved, ...store]
      const items = store.filter(i => (i.parentId || 'root') === parentId)
      return NextResponse.json({ items, added: saved })
    } catch (e) {
      return NextResponse.json({ error: String(e) }, { status: 500 })
    }
  }

  if (action === 'upload') {
    // note: we don't have multipart parsing here; accept a JSON body for demo
    const url = new URL(req.url)
    const parentId = url.searchParams.get('parentId') || 'root'
    const body: { files?: Array<{ name?: string; size?: number }> } = await req.json().catch(() => ({}))
    const files = Array.isArray(body.files) ? body.files as Array<{ name?: string; size?: number }> : []
    const added = files.map((f) => {
      const name = f.name || 'unnamed'
      return {
        id: String(Date.now()) + Math.random(),
        name: ensureUniqueName(parentId, name, 'file'),
        type: 'file' as const,
        size: f.size || 0,
        modified: new Date().toISOString().slice(0, 10),
        parentId,
      }
    })
    store = [...added, ...store]
    const items = store.filter(i => (i.parentId || 'root') === parentId)
    return NextResponse.json({ items, added })
  }

  if (action === 'create-folder') {
    const url = new URL(req.url)
    const parentId = url.searchParams.get('parentId') || 'root'
    const body = await req.json().catch(() => ({}))
    const name = body.name || 'New folder'
  const uniqueFolderName = ensureUniqueName(parentId, name, 'folder')
  const folder: FileItem = { id: String(Date.now()) + Math.random(), name: uniqueFolderName, type: 'folder', modified: new Date().toISOString().slice(0,10), parentId }
    store = [folder, ...store]
    const items = store.filter(i => (i.parentId || 'root') === parentId)
    return NextResponse.json({ items, created: folder })
  }

  if (action === 'delete') {
    const body = await req.json().catch(() => ({}))
    const ids: string[] = Array.isArray(body.ids) ? body.ids : []
    // remove items and any children recursively
    const toRemove = new Set(ids)
    const collectChildren = (parentIds: string[]) => {
      const found: string[] = []
      for (const p of parentIds) {
        for (const it of store) {
          if ((it.parentId || 'root') === p) {
            if (!toRemove.has(it.id)) {
              toRemove.add(it.id)
              found.push(it.id)
            }
          }
        }
      }
      if (found.length) collectChildren(found)
    }
    collectChildren(ids)
    store = store.filter(i => !toRemove.has(i.id))
    return NextResponse.json({ items: store })
  }

  if (action === 'copy') {
    const url = new URL(req.url)
    const targetParent = url.searchParams.get('parentId') || 'root'
    const body = await req.json().catch(() => ({}))
    const ids: string[] = Array.isArray(body.ids) ? body.ids : []
    const operation: 'copy' | 'move' | undefined = body.operation
    const added: FileItem[] = []

    // helper to find children recursively
    const collectSubtree = (rootId: string) => {
      const out: FileItem[] = []
      const stack = [rootId]
      while (stack.length) {
        const cur = stack.pop()!
        for (const it of store) {
          if ((it.parentId || 'root') === cur) {
            out.push(it)
            if (it.type === 'folder') stack.push(it.id)
          }
        }
      }
      return out
    }

    for (const id of ids) {
      const item = store.find(i => i.id === id)
      if (!item) continue

      if (item.type === 'file') {
        // For demo: duplicate metadata only. Re-using the same URL avoids extra disk writes
  const uniqueName = ensureUniqueName(targetParent, item.name, 'file')
  const copyItem: FileItem = { ...item, id: String(Date.now()) + Math.random(), name: uniqueName, modified: new Date().toISOString().slice(0,10), parentId: targetParent }
  added.push(copyItem)

      } else if (item.type === 'folder') {
        // copy folder and its subtree preserving structure
        const rootCopyId = String(Date.now()) + Math.random()
  const folderName = `${item.name}_copy`
  const uniqueFolderCopyName = ensureUniqueName(targetParent, folderName, 'folder')
  const folderCopy: FileItem = { ...item, id: rootCopyId, name: uniqueFolderCopyName, modified: new Date().toISOString().slice(0,10), parentId: targetParent }
        added.push(folderCopy)
        const subtree = collectSubtree(item.id)
        // map oldId -> newId
        const idMap = new Map<string, string>()
        idMap.set(item.id, rootCopyId)
        for (const child of subtree) {
          const newId = String(Date.now()) + Math.random()
          idMap.set(child.id, newId)
            if (child.type === 'file') {
            // demo: duplicate metadata only to avoid filesystem churn during dev
            const targetParentForChild = idMap.get(child.parentId || '') || targetParent
            const uniqueChildName = ensureUniqueName(targetParentForChild, child.name, 'file')
            added.push({ ...child, id: newId, name: uniqueChildName, parentId: targetParentForChild, modified: new Date().toISOString().slice(0,10) })
          } else if (child.type === 'folder') {
            const targetParentForChild = idMap.get(child.parentId || '') || targetParent
            const uniqueChildFolderName = ensureUniqueName(targetParentForChild, child.name, 'folder')
            added.push({ ...child, id: newId, name: uniqueChildFolderName, parentId: targetParentForChild, modified: new Date().toISOString().slice(0,10) })
          }
        }
      }

      if (operation === 'move') {
        // remove original and its subtree
        const toRemove = new Set<string>()
        toRemove.add(id)
        const subtreeIds = collectSubtree(id).map(x => x.id)
        for (const sId of subtreeIds) toRemove.add(sId)
        store = store.filter(s => !toRemove.has(s.id))
      }
    }
    store = [...added, ...store]
    return NextResponse.json({ items: store, added })
  }

  if (action === 'rename') {
    const body = await req.json().catch(() => ({}))
    const id = body.id
    const name = body.name
    store = store.map(i => i.id === id ? { ...i, name } : i)
    return NextResponse.json({ items: store })
  }

  return NextResponse.json({ error: 'unknown action' }, { status: 400 })
}
