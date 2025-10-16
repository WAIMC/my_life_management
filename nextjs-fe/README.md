# Next.js Frontend - My Life Management

## 📑 Giới thiệu

Frontend xây dựng bằng Next.js cho dự án My Life Management, cung cấp giao diện người dùng hiện đại, thân thiện và hiệu năng cao kết nối với Laravel API.

## 🛠️ Yêu cầu môi trường

- **Node.js**: Phiên bản 18.x trở lên
- **npm/yarn/pnpm**: Quản lý gói JavaScript
- **Trình duyệt hiện đại**: Chrome, Firefox, Edge, Safari

## 🚀 Hướng dẫn cài đặt

### 1. Thiết lập môi trường

#### Phát triển trực tiếp trên máy

```bash
# Clone dự án (nếu chưa có)
git clone https://github.com/WAIMC/my_life_management.git
cd my_life_management/nextjs-fe

# Cài đặt dependencies
npm install
# hoặc
yarn install
# hoặc
pnpm install
```

#### Phát triển với Docker (Tùy chọn)

```bash
# Cấu hình trong docker-compose.yml (Đang được comment)
# ml-nextjs:
#   container_name: ml-nextjs
#   build: ./docker/nextjs
#   volumes:
#     - ./nextjs-fe:/app
#   ports:
#     - "9003:3000"
```

### 2. Cấu hình môi trường

Tạo file `.env.local` từ `.env.example` (nếu có):

```bash
cp .env.example .env.local
```

Cập nhật các biến môi trường:

```
# API URL - Đường dẫn đến Laravel API
NEXT_PUBLIC_API_URL=http://localhost:81/api

# Authentication endpoints
NEXT_PUBLIC_AUTH_LOGIN=/auth/login
NEXT_PUBLIC_AUTH_REGISTER=/auth/register
```

### 3. Khởi chạy ứng dụng

#### Môi trường phát triển

```bash
npm run dev
# hoặc
yarn dev
# hoặc
pnpm dev
```

Truy cập ứng dụng tại [http://localhost:3000](http://localhost:3000)

#### Xây dựng cho môi trường production

```bash
# Build ứng dụng
npm run build
# hoặc
yarn build
# hoặc
pnpm build

# Khởi chạy phiên bản production
npm run start
# hoặc
yarn start
# hoặc
pnpm start
```

## 📋 Cấu trúc dự án

```
nextjs-fe/
├── public/               # Static assets
├── src/
│   ├── app/              # App router
│   │   ├── (auth)/       # Authentication routes
│   │   ├── (dashboard)/  # Dashboard routes
│   │   ├── api/          # API routes
│   │   └── layout.tsx    # Root layout
│   ├── components/       # Shared components
│   │   ├── ui/           # UI components
│   │   ├── forms/        # Form components
│   │   └── layout/       # Layout components
│   ├── hooks/            # Custom hooks
│   ├── lib/              # Utility functions
│   ├── services/         # API services
│   ├── store/            # State management
│   └── types/            # TypeScript types
├── .eslintrc.json       # ESLint configuration
├── .gitignore           # Git ignore file
├── next.config.ts       # Next.js configuration
├── package.json         # Project dependencies
├── tsconfig.json        # TypeScript configuration
└── README.md            # Project documentation
```

## 🔧 Tính năng chính

- **Xác thực người dùng**: Đăng nhập, đăng ký, phục hồi mật khẩu
- **Quản lý quyền**: Hiển thị UI dựa trên quyền của người dùng
- **Giao diện quản trị**: Dashboard và các trang quản lý
- **Giao diện người dùng**: Các trang dành cho người dùng cuối
- **Tương tác với API**: Kết nối với Laravel API backend
- **Đa ngôn ngữ**: Hỗ trợ nhiều ngôn ngữ
- **Responsive Design**: Tương thích với mọi kích thước màn hình

## 📱 Tương tác với API

Tất cả các tương tác với API được xử lý thông qua các service:

```typescript
// src/services/api.ts
import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:81/api';

export const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor cho authentication
apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});
```

## 🔐 Xác thực và Authorization

```typescript
// src/services/auth.service.ts
import { apiClient } from './api';

export const AuthService = {
  login: async (email: string, password: string) => {
    const response = await apiClient.post('/auth/login', { email, password });
    return response.data;
  },
  
  logout: async () => {
    const response = await apiClient.post('/auth/logout');
    return response.data;
  },
  
  getCurrentUser: async () => {
    const response = await apiClient.get('/auth/user');
    return response.data;
  },
};
```

## 🎨 UI Components

Dự án sử dụng một số thư viện UI:

- **Tailwind CSS**: Framework CSS tiện ích
- **shadcn/ui**: Các component UI có thể tùy chỉnh
- **React Icons**: Bộ icon đa dạng

```bash
# Cài đặt thêm components (ví dụ)
npx shadcn-ui@latest add button
npx shadcn-ui@latest add dialog
```

## 📝 Linting và Formatting

```bash
# Kiểm tra lỗi với ESLint
npm run lint
# hoặc
yarn lint
# hoặc
pnpm lint

# Format code với Prettier
npm run format
# hoặc
yarn format
# hoặc
pnpm format
```

## 🧪 Testing

```bash
# Chạy tests
npm run test
# hoặc
yarn test
# hoặc
pnpm test
```

## 🔍 SEO và Analytics

Next.js hỗ trợ SEO tốt với các tính năng:

- Metadata API
- Server-side rendering
- Static site generation

## 📱 Responsive Design

Dự án được thiết kế để hoạt động tốt trên mọi thiết bị:

- Mobile-first design
- Breakpoints cho tablet và desktop
- Testing trên nhiều kích thước màn hình

## 🌐 Internationalization

```bash
# Thêm ngôn ngữ mới
npm run add-locale -- --locale=vi
# hoặc
yarn add-locale --locale=vi
# hoặc
pnpm add-locale --locale=vi
```

## 🧩 State Management

Dự án sử dụng React Context API kết hợp với hooks:

```typescript
// src/store/auth-context.tsx
import { createContext, useContext, useState, useEffect } from 'react';
import { AuthService } from '@/services/auth.service';

export const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  // Implementation
}

export const useAuth = () => useContext(AuthContext);
```

## 📝 Tham khảo

- [Next.js Documentation](https://nextjs.org/docs)
- [React Documentation](https://reactjs.org/docs/getting-started.html)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [shadcn/ui Documentation](https://ui.shadcn.com)
- [Axios Documentation](https://axios-http.com/docs/intro)