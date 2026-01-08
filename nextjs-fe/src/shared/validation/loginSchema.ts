import { z } from 'zod';

export const loginSchema = z.object({
  user_name: z
    .string()
    .min(1, 'Tên người dùng là bắt buộc')
    .min(3, 'Tên người dùng phải có ít nhất 3 ký tự'),
  password: z
    .string()
    .min(1, 'Mật khẩu là bắt buộc')
    .min(6, 'Mật khẩu phải có ít nhất 6 ký tự'),
});

export type LoginFormData = z.infer<typeof loginSchema>;
