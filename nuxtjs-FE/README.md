# Nuxt.js Frontend - My Life Management

## 📑 Giới thiệu

Frontend thay thế xây dựng bằng Nuxt.js cho dự án My Life Management, cung cấp giao diện người dùng hiện đại với các tính năng Vue.js và hiệu suất tối ưu kết nối với Laravel API.

## 🛠️ Yêu cầu môi trường

- **Node.js**: Phiên bản 16.x trở lên
- **npm/yarn/pnpm**: Quản lý gói JavaScript
- **Trình duyệt hiện đại**: Chrome, Firefox, Edge, Safari

## 🚀 Hướng dẫn cài đặt

### 1. Thiết lập môi trường

```bash
# Clone dự án (nếu chưa có)
git clone https://github.com/WAIMC/my_life_management.git
cd my_life_management/nuxtjs-FE

# Cài đặt dependencies
npm install
# hoặc
yarn install
# hoặc
pnpm install
```

### 2. Cấu hình môi trường

Tạo file `.env` từ `.env.example` (nếu có):

```bash
cp .env.example .env
```

Cập nhật các biến môi trường:

```
# API URL - Đường dẫn đến Laravel API
NUXT_PUBLIC_API_URL=http://localhost:81/api

# Authentication endpoints
NUXT_PUBLIC_AUTH_LOGIN=/auth/login
NUXT_PUBLIC_AUTH_REGISTER=/auth/register
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

### 4. Preview ứng dụng đã build

```bash
npm run preview
# hoặc
yarn preview
# hoặc
pnpm preview
```

## 📋 Cấu trúc dự án

```
nuxtjs-FE/
├── public/              # Static assets
├── server/              # Server-side code
│   └── tsconfig.json    # TypeScript config for server
├── app.vue              # Root Vue component
├── components/          # Vue components
├── composables/         # Vue composables
├── layouts/             # Layout components
├── middleware/          # Nuxt middleware
├── pages/               # Vue pages
│   ├── index.vue        # Home page
│   └── auth/            # Authentication pages
├── plugins/             # Nuxt plugins
├── stores/              # Pinia stores
├── assets/              # Uncompiled assets
├── utils/               # Utility functions
├── .gitignore           # Git ignore file
├── nuxt.config.ts       # Nuxt configuration
├── package.json         # Project dependencies
└── tsconfig.json        # TypeScript configuration
```

## 🔧 Tính năng chính

- **Xác thực người dùng**: Đăng nhập, đăng ký, quên mật khẩu
- **Quản lý quyền**: Hiển thị UI dựa trên quyền của người dùng
- **Giao diện quản trị**: Dashboard và các trang quản lý
- **Giao diện người dùng**: Các trang dành cho người dùng cuối
- **Tương tác với API**: Kết nối với Laravel API backend
- **Đa ngôn ngữ**: Hỗ trợ nhiều ngôn ngữ
- **Responsive Design**: Tương thích với mọi kích thước màn hình

## 📱 Tương tác với API

Tất cả các tương tác với API được xử lý thông qua Nuxt 3 useFetch/useAsyncData:

```typescript
// composables/useApi.ts
import { UseFetchOptions } from 'nuxt/app'

export function useApi<T>(endpoint: string, options?: UseFetchOptions<T>) {
  const config = useRuntimeConfig()
  
  return useFetch(`${config.public.apiUrl}${endpoint}`, {
    ...options,
    headers: {
      ...options?.headers,
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
  })
}
```

## 🔐 Xác thực và Authorization

```typescript
// composables/useAuth.ts
import { useRouter } from 'vue-router'
import { useUserStore } from '~/stores/user'

export function useAuth() {
  const userStore = useUserStore()
  const router = useRouter()
  
  const login = async (email: string, password: string) => {
    try {
      const { data, error } = await useApi('/auth/login', {
        method: 'POST',
        body: { email, password }
      })
      
      if (error.value) throw error.value
      
      userStore.setUser(data.value.user)
      userStore.setToken(data.value.access_token)
      
      router.push('/dashboard')
    } catch (err) {
      // Handle error
    }
  }
  
  const logout = async () => {
    try {
      await useApi('/auth/logout', {
        method: 'POST',
      })
      
      userStore.clearUser()
      userStore.clearToken()
      
      router.push('/login')
    } catch (err) {
      // Handle error
    }
  }
  
  // Additional auth methods...
  
  return {
    login,
    logout,
    // Additional methods...
  }
}
```

## 🎨 UI Components

Dự án có thể được tích hợp với các thư viện UI phổ biến:

- **Tailwind CSS**: Utility-first CSS framework
- **Nuxt UI**: UI Component library for Nuxt.js
- **Primevue**: Vue UI component library

```bash
# Cài đặt Tailwind CSS
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init
```

## 📝 Linting và Formatting

```bash
# Kiểm tra lỗi với ESLint
npm run lint
# hoặc
yarn lint
# hoặc
pnpm lint
```

## 🧪 Testing

```bash
# Cài đặt dependencies cho testing
npm install -D vitest @vue/test-utils happy-dom

# Chạy tests
npm run test
# hoặc
yarn test
# hoặc
pnpm test
```

## 🔍 SEO và Analytics

Nuxt.js hỗ trợ SEO tốt với tính năng:

- Server-side rendering
- Auto-import meta tags
- Sitemap generation

```typescript
// pages/about.vue
<script setup>
useHead({
  title: 'About Us',
  meta: [
    { name: 'description', content: 'Learn more about My Life Management' }
  ]
})
</script>
```

## 📱 Responsive Design

Dự án được thiết kế để hoạt động tốt trên mọi thiết bị:

- Mobile-first design
- Breakpoints cho tablet và desktop
- Testing trên nhiều kích thước màn hình

## 🌐 Internationalization

```bash
# Cài đặt Nuxt i18n module
npm install @nuxtjs/i18n

# Cấu hình trong nuxt.config.ts
export default defineNuxtConfig({
  modules: ['@nuxtjs/i18n'],
  i18n: {
    // Configuration
  }
})
```

## 🧩 State Management

Dự án sử dụng Pinia cho state management:

```typescript
// stores/user.ts
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    token: null
  }),
  
  actions: {
    setUser(user) {
      this.user = user
    },
    setToken(token) {
      this.token = token
      localStorage.setItem('token', token)
    },
    clearUser() {
      this.user = null
    },
    clearToken() {
      this.token = null
      localStorage.removeItem('token')
    }
  },
  
  getters: {
    isAuthenticated: (state) => !!state.token,
    userRole: (state) => state.user?.role
  }
})
```

## 📦 Production Deployment

```bash
# Build ứng dụng
npm run build

# Khởi chạy ứng dụng cho production
node .output/server/index.mjs
```

## 📝 Tham khảo

- [Nuxt.js Documentation](https://nuxt.com/docs/getting-started/introduction)
- [Vue.js Documentation](https://vuejs.org/guide/introduction.html)
- [Pinia Documentation](https://pinia.vuejs.org/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Nuxt UI Documentation](https://ui.nuxt.com/)