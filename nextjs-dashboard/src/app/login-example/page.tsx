'use client';

import { useState } from 'react';
import { loginApi, logoutApi } from '@/common/api/auth.api';
import { isApiSuccess, getErrorMessages } from '@/common/api/client';
import { AuthSchemas, validate } from '@/common/validation';
import { MESSAGES } from '@/common/constants/messages';
import type { LoginFormData } from '@/common/validation';

/**
 * Example: Login Page using Common API
 */
export default function LoginExample() {
  const [formData, setFormData] = useState<LoginFormData>({
    email: '',
    password: '',
    remember: false,
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value, type, checked } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? checked : value,
    }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    // Validate form data
    const validation = validate(AuthSchemas.login, formData);
    if (!validation.success) {
      setError(validation.errors?.join(', ') || 'Validation failed');
      return;
    }

    setLoading(true);

    try {
      // Call login API
      const response = await loginApi(formData);

      // Check if success
      if (isApiSuccess(response)) {
        alert(MESSAGES.SUCCESS.LOGIN);
        // Redirect to dashboard
        window.location.href = '/dashboard';
      } else {
        // Get error messages
        const errors = getErrorMessages(response);
        setError(errors.join(', '));
      }
    } catch (err: any) {
      setError(err.message || MESSAGES.ERROR.DEFAULT);
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = async () => {
    try {
      await logoutApi();
      alert(MESSAGES.SUCCESS.LOGOUT);
      window.location.href = '/login';
    } catch (err: any) {
      alert(err.message);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 className="text-2xl font-bold mb-6">Login Example</h1>

        {error && (
          <div className="mb-4 p-3 bg-red-100 text-red-700 rounded">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium mb-1">Email</label>
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
              placeholder="your@email.com"
              required
            />
          </div>

          <div>
            <label className="block text-sm font-medium mb-1">Password</label>
            <input
              type="password"
              name="password"
              value={formData.password}
              onChange={handleChange}
              className="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
              placeholder="••••••••"
              required
            />
          </div>

          <div className="flex items-center">
            <input
              type="checkbox"
              name="remember"
              checked={formData.remember}
              onChange={handleChange}
              className="mr-2"
            />
            <label className="text-sm">Remember me</label>
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 disabled:opacity-50"
          >
            {loading ? MESSAGES.LOADING.DEFAULT : 'Login'}
          </button>
        </form>

        <div className="mt-4">
          <button
            onClick={handleLogout}
            className="text-sm text-blue-500 hover:underline"
          >
            Test Logout
          </button>
        </div>
      </div>
    </div>
  );
}
