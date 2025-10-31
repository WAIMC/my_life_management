declare module 'formidable' {
  import { IncomingMessage } from 'http'
  export type File = {
    filepath: string
    originalFilename?: string
    newFilename?: string
    size: number
  }
  export type Files = Record<string, File | File[]>
  export type Fields = Record<string, string | string[]>

  export interface Options {
    multiples?: boolean
    keepExtensions?: boolean
    uploadDir?: string
  }

  export default function formidable(opts?: Options): {
    parse(req: IncomingMessage | Request, cb: (err: Error | null, fields: Fields, files: Files) => void): void
  }
}
