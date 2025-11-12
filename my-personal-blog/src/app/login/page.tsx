'use client';

import { useState, useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useRouter } from 'next/navigation';
import { useAppDispatch, useAppSelector } from '@/redux/hooks';
import { setRedirectUrl } from '@/redux/slices/authSlice';
import { loginSchema, type LoginFormData } from '@/lib/validation/loginSchema';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card } from '@/components/ui/card';

export default function LoginPage() {
  const router = useRouter();
  const dispatch = useAppDispatch();
  const { isLoading } = useAppSelector((state) => state.common);
  const { isAuthenticated, accessToken, redirectUrl } = useAppSelector((state) => state.auth);
  const [submitError, setSubmitError] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<LoginFormData>({
    resolver: zodResolver(loginSchema),
    mode: 'onBlur',
  });

  const onSubmit = async (data: LoginFormData) => {
    setSubmitError(null);
    dispatch({
      type: 'auth/loginSaga',
      payload: data,
    });
  };

  // Redirect sau khi đăng nhập thành công
  useEffect(() => {
    if (isAuthenticated && accessToken) {
      const url = redirectUrl || '/admin';
      // Reset redirectUrl để tránh vòng lặp
      dispatch(setRedirectUrl(null));
      router.push(url);
    }
  }, [isAuthenticated, accessToken, redirectUrl, router, dispatch]);

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12 sm:px-6 lg:px-8">
      <Card className="w-full max-w-md space-y-8 p-8">
        <div className="text-center">
          <h2 className="text-3xl font-bold tracking-tight text-gray-900">
            Đăng nhập
          </h2>
          <p className="mt-2 text-sm text-gray-600">
            Nhập thông tin đăng nhập của bạn
          </p>
        </div>

        <form className="space-y-6" onSubmit={handleSubmit(onSubmit)}>
          {submitError && (
            <div className="rounded-md bg-red-50 p-4">
              <p className="text-sm font-medium text-red-800">{submitError}</p>
            </div>
          )}

          <div>
            <label
              htmlFor="user_name"
              className="block text-sm font-medium text-gray-700"
            >
              Tên người dùng
            </label>
            <Input
              id="user_name"
              type="text"
              {...register('user_name')}
              disabled={isLoading}
              className="mt-1"
              placeholder="Nhập tên người dùng"
            />
            {errors.user_name && (
              <p className="mt-1 text-sm text-red-600">
                {errors.user_name.message}
              </p>
            )}
          </div>

          <div>
            <label
              htmlFor="password"
              className="block text-sm font-medium text-gray-700"
            >
              Mật khẩu
            </label>
            <Input
              id="password"
              type="password"
              {...register('password')}
              disabled={isLoading}
              className="mt-1"
              placeholder="Nhập mật khẩu"
            />
            {errors.password && (
              <p className="mt-1 text-sm text-red-600">
                {errors.password.message}
              </p>
            )}
          </div>

          <Button
            type="submit"
            disabled={isLoading}
            className="w-full"
          >
            {isLoading ? 'Đang đăng nhập...' : 'Đăng nhập'}
          </Button>
        </form>
      </Card>
    </div>
  );
}
