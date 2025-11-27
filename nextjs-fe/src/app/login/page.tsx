"use client";

import { useState, FormEvent, useEffect } from "react";
import { useRouter, useSearchParams } from "next/navigation";
import { useAuth } from "@/hooks/useAuth";
import { useAppSelector } from "@/redux/hooks";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card } from "@/components/ui/card";

export default function LoginPage() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const { login, isLoading } = useAuth();
  const { isAuthenticated, authInitialized } = useAppSelector(
    (state) => state.auth
  );
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  // DISABLED: Redirect if already authenticated
  useEffect(() => {
    // DISABLED: Auto-redirect logic
    return;

    /* ORIGINAL CODE - COMMENTED OUT
    if (authInitialized && isAuthenticated) {
      const redirectParam = searchParams.get("redirect");
      const redirectUrl =
        redirectParam && redirectParam.startsWith("/admin")
          ? redirectParam
          : "/admin";
      router.push(redirectUrl);
    } else {
    }
    */
  }, [authInitialized, isAuthenticated, searchParams, router]);

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError(""); // Clear previous errors

    try {
      // useAuth.login() calls syncAuthStateAcrossTabs which updates Redux
      await login(username, password);

      // DO NOT manually redirect here!
      // The useEffect (lines 24-35) will handle redirect when isAuthenticated becomes true
      // This prevents race condition where AdminLayout checks auth before state propagates
    } catch (error: any) {
      // Show error message to user
      const errorMessage = error.response?.data?.message || "Invalid username or password";
      setError(errorMessage);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900 p-4">
      <Card className="w-full max-w-md p-8 shadow-lg">
        <div className="text-center mb-8">
          <h1 className="text-3xl font-bold text-slate-900 dark:text-white">
            Welcome Back
          </h1>
          <p className="text-slate-600 dark:text-slate-400 mt-2">
            Sign in to your account to continue
          </p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Username Field */}
          <div className="space-y-2">
            <Label htmlFor="username">Username</Label>
            <Input
              id="username"
              type="text"
              placeholder="admin"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              required
              autoComplete="username"
              disabled={isLoading}
              className="w-full"
            />
          </div>

          {/* Password Field */}
          <div className="space-y-2">
            <Label htmlFor="password">Password</Label>
            <Input
              id="password"
              type="password"
              placeholder="Enter your password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              autoComplete="current-password"
              disabled={isLoading}
              className="w-full"
            />
          </div>

          {/* Error Message */}
          {error && (
            <div className="rounded-lg bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900 p-3">
              <p className="text-sm text-red-600 dark:text-red-400">{error}</p>
            </div>
          )}

          {/* Submit Button */}
          <Button
            type="submit"
            className="w-full"
            disabled={isLoading}
            size="lg"
          >
            {isLoading ? (
              <>
                <div className="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent" />
                Signing in...
              </>
            ) : (
              "Sign In"
            )}
          </Button>
        </form>

        {/* Footer */}
        <div className="mt-6 text-center text-sm text-slate-600 dark:text-slate-400">
          <p>Forgot your password? Contact your administrator.</p>
        </div>
      </Card>
    </div>
  );
}
