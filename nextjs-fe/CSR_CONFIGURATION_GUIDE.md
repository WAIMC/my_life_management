# Next.js CSR-Only Development Guide

## Tóm tắt cấu hình

Dự án Next.js này được cấu hình **hoàn toàn Client-Side Rendering (CSR)** - không sử dụng Server-Side Rendering (SSR).

## Các thay đổi đã thực hiện

### 1. Root Layout (`src/app/layout.tsx`)
- ✅ Thêm `'use client'` directive
- ✅ Loại bỏ `Metadata` export (SSR feature)
- ✅ Loại bỏ Next.js Font optimization
- ✅ Sử dụng `useEffect` để set metadata client-side

### 2. Global CSS (`src/app/globals.css`)
- ✅ Import Google Fonts trực tiếp qua CSS
- ✅ Update font family references

### 3. Next.js Config (`next.config.ts`)
- ✅ `output: 'export'` - Static export
- ✅ `images.unoptimized: true` - Disable image optimization
- ✅ Thêm comments chi tiết

## Best Practices

### ✅ PHẢI LÀM

1. **Luôn dùng `'use client'`** cho components có:
   - `useState`, `useEffect`, `useContext`
   - Event handlers (onClick, onChange, etc.)
   - Browser APIs (window, document, localStorage, etc.)

2. **Client-side data fetching**:
   ```typescript
   // ✅ Good - Use hooks
   const { data } = useApiData<Model>('/endpoint');
   
   // ✅ Good - useEffect
   useEffect(() => {
     fetchData();
   }, []);
   ```

3. **Environment variables**:
   ```env
   # Tất cả env vars phải có prefix NEXT_PUBLIC_
   NEXT_PUBLIC_API_URL=http://localhost:8000/api
   NEXT_PUBLIC_APP_URL=http://localhost:3000
   ```

4. **Routing**:
   ```typescript
   // ✅ Use client-side router
   import { useRouter } from 'next/navigation';
   const router = useRouter();
   router.push('/admin/users');
   ```

### ❌ KHÔNG ĐƯỢC LÀM

1. **KHÔNG dùng SSR features**:
   ```typescript
   // ❌ These DON'T work with output: 'export'
   export const metadata = { ... }
   export async function generateMetadata() { ... }
   export async function generateStaticParams() { ... }
   ```

2. **KHÔNG dùng Server Components** (components không có `'use client'`):
   ```typescript
   // ❌ Bad - Async component (server component)
   export default async function Page() {
     const data = await fetch(...);
     return <div>{data}</div>;
   }
   
   // ✅ Good - Client component with useEffect
   'use client';
   export default function Page() {
     const [data, setData] = useState(null);
     useEffect(() => {
       fetch(...).then(setData);
     }, []);
     return <div>{data}</div>;
   }
   ```

3. **KHÔNG dùng Next.js Font optimization**:
   ```typescript
   // ❌ Bad - Requires SSR
   import { Inter } from 'next/font/google';
   
   // ✅ Good - Direct CSS import
   // In globals.css:
   @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');
   ```

4. **KHÔNG dùng Next.js Image optimization**:
   ```typescript
   // Configuration đã set images.unoptimized: true
   // Dùng <Image> component bình thường, images sẽ không được optimize
   ```

## Build và Deploy

### Development
```bash
cd /home/vinhdv/projects/my_life_management/nextjs-fe
pnpm dev
```

### Production Build
```bash
# Build static files
pnpm build

# Output sẽ ở folder 'out/'
ls -la out/
```

### Local Test Production Build
```bash
# Install serve (one-time)
pnpm add -D serve

# Serve static files
npx serve out
```

### Deploy

Static files trong folder `out/` có thể deploy lên:
- **Netlify**: Drag & drop folder `out/`
- **Vercel**: Deploy as static site
- **AWS S3 + CloudFront**
- **Firebase Hosting**
- **GitHub Pages**
- **Nginx/Apache**

**Lưu ý**: Cần configure server để serve `index.html` cho mọi routes (SPA fallback).

#### Nginx Example
```nginx
location / {
  try_files $uri $uri/ /index.html;
}
```

#### Netlify (_redirects file)
```
/*    /index.html   200
```

## Routing trong SPA

Với `output: 'export'`, Next.js sẽ:
1. Pre-render tất cả pages thành static HTML
2. Client-side router handle navigation sau khi load
3. Mỗi route vẫn có HTML riêng (tốt cho SEO hơn pure SPA)

Example:
- `/` → `out/index.html`
- `/login` → `out/login.html`
- `/admin` → `out/admin.html`
- `/admin/users` → `out/admin/users.html`

## Kiểm tra build có đúng CSR không

```bash
# 1. Build
pnpm build

# 2. Check output folder
ls -R out/

# 3. Serve và test
npx serve out

# 4. Open browser và check:
# - Không có hydration errors
# - Routing hoạt động
# - API calls hoạt động
# - Redux state hoạt động
```

## Troubleshooting

### Error: "Metadata is not supported in Client Components"
- **Cause**: File có `'use client'` vẫn export metadata
- **Fix**: Xóa metadata export, dùng useEffect để set title

### Error: "Image Optimization using the default loader is not compatible with export"
- **Cause**: Chưa set `images.unoptimized`
- **Fix**: Đã fix trong `next.config.ts`

### Fonts không load
- **Cause**: Dùng next/font/google
- **Fix**: Đã chuyển sang CSS import trong `globals.css`

### Dynamic routes không work khi deploy
- **Cause**: Server không configure SPA fallback
- **Fix**: Configure server để serve index.html cho mọi routes

## Files quan trọng

- `next.config.ts` - CSR configuration
- `src/app/layout.tsx` - Client layout với metadata
- `src/app/globals.css` - Font imports
- `package.json` - Build scripts

## Tài liệu thêm

- [Next.js Static Exports](https://nextjs.org/docs/app/building-your-application/deploying/static-exports)
- [Client Components](https://nextjs.org/docs/app/building-your-application/rendering/client-components)
- Project docs: `INTEGRATION_PROGRESS.md`, `LARAVEL_API_INTEGRATION_PLAN.md`
