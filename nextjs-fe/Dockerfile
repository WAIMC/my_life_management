# Sử dụng Node.js làm image cơ sở
FROM node:22-alpine

# Thiết lập thư mục làm việc trong container
WORKDIR /app

# Sao chép package.json và package-lock.json
COPY package*.json ./

# Cài đặt các dependencies
RUN npm install

# Sao chép toàn bộ mã nguồn vào container
COPY . .

# Build ứng dụng Next.js
RUN npm run build

# Mở cổng cho server của Next.js
EXPOSE 3001

# Command khởi chạy ứng dụng
CMD ["npm", "start"]
