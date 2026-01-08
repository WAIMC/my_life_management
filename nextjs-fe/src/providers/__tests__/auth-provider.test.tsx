import { render, screen, waitFor, act } from "@testing-library/react";
import { AuthProvider } from "../auth-provider";
import { authService } from "@/shared/services/modules/auth.service";
import { useAuth } from "@/shared/hooks/use-auth";
import { vi, describe, it, expect, beforeEach, afterEach } from "vitest";
import React from "react";

// Mock authService
vi.mock("@/services/auth.service", () => ({
  authService: {
    getMe: vi.fn(),
    login: vi.fn(),
    logout: vi.fn(),
    refreshToken: vi.fn(),
    acquireRefreshLock: vi.fn(),
    releaseRefreshLock: vi.fn(),
    isRefreshLocked: vi.fn(),
  },
}));

// Mock useRouter
const mockPush = vi.fn();
vi.mock("next/navigation", () => ({
  useRouter: () => ({
    push: mockPush,
  }),
}));

// Mock BroadcastChannel
class MockBroadcastChannel {
  name: string;
  onmessage: ((this: BroadcastChannel, ev: MessageEvent) => any) | null = null;

  constructor(name: string) {
    this.name = name;
    // Register instance to global map to simulate cross-tab communication
    if (!global.mockChannels[name]) {
      global.mockChannels[name] = [];
    }
    global.mockChannels[name].push(this);
  }

  postMessage(message: any) {
    const channels = global.mockChannels[this.name] || [];
    channels.forEach((channel: any) => {
      if (channel !== this && channel.onmessage) {
        channel.onmessage({ data: message } as MessageEvent);
      }
    });
  }

  close() {
    const channels = global.mockChannels[this.name] || [];
    const index = channels.indexOf(this);
    if (index > -1) {
      channels.splice(index, 1);
    }
  }
}

// Setup global mock channels
declare global {
  var mockChannels: Record<string, any[]>;
}
global.mockChannels = {};
global.BroadcastChannel = MockBroadcastChannel as any;

// Test Component
const TestComponent = () => {
  const { user, isAuthenticated, login, logout } = useAuth();
  return (
    <div>
      <div data-testid="auth-status">
        {isAuthenticated ? "Authenticated" : "Guest"}
      </div>
      <div data-testid="user-email">{user?.email}</div>
      <button
        onClick={() => login({ user_name: "test", password: "password" })}
      >
        Login
      </button>
      <button onClick={() => logout()}>Logout</button>
    </div>
  );
};

describe("AuthProvider", () => {
  beforeEach(() => {
    vi.clearAllMocks();
    global.mockChannels = {};
  });

  it("initializes with guest state if getMe fails", async () => {
    (authService.getMe as any).mockRejectedValue(new Error("Unauthorized"));

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await waitFor(() => {
      expect(screen.getByTestId("auth-status")).toHaveTextContent("Guest");
    });
  });

  it("initializes with user state if getMe succeeds", async () => {
    const mockUser = { email: "test@example.com" };
    (authService.getMe as any).mockResolvedValue({
      user: mockUser,
      expires_at: Date.now() / 1000 + 3600,
    });

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await waitFor(() => {
      expect(screen.getByTestId("auth-status")).toHaveTextContent(
        "Authenticated"
      );
      expect(screen.getByTestId("user-email")).toHaveTextContent(
        "test@example.com"
      );
    });
  });

  it("handles login flow correctly", async () => {
    (authService.getMe as any).mockRejectedValue(new Error("Unauthorized"));
    (authService.login as any).mockResolvedValue({
      user: { email: "login@example.com" },
      expires_at: Date.now() / 1000 + 3600,
    });

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    // Wait for init
    await waitFor(() =>
      expect(screen.getByTestId("auth-status")).toHaveTextContent("Guest")
    );

    // Click Login
    await act(async () => {
      screen.getByText("Login").click();
    });

    await waitFor(() => {
      // screen.debug();
      expect(screen.getByTestId("auth-status")).toHaveTextContent(
        "Authenticated"
      );
      expect(screen.getByTestId("user-email")).toHaveTextContent(
        "login@example.com"
      );
    });
  });

  it("schedules refresh and executes it (Leader)", async () => {
    const nowSec = Math.floor(Date.now() / 1000);
    // Expires in 30.2 seconds.
    // timeUntilCheck = (now + 30200) - now - 30000 = 200ms.
    const expiresAt = nowSec + 30.2;

    (authService.getMe as any).mockResolvedValue({
      user: { email: "test@example.com" },
      expires_at: expiresAt,
    });

    // Mock Lock: Leader
    (authService.isRefreshLocked as any).mockReturnValue(false);
    (authService.acquireRefreshLock as any).mockReturnValue(true);
    (authService.refreshToken as any).mockResolvedValue({
      expires_at: expiresAt + 3600,
    });

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await waitFor(() =>
      expect(screen.getByTestId("auth-status")).toHaveTextContent(
        "Authenticated"
      )
    );

    // Wait for timer (200ms + buffer)
    await act(async () => {
      await new Promise((resolve) => setTimeout(resolve, 500));
    });

    expect(authService.acquireRefreshLock).toHaveBeenCalled();
    expect(authService.refreshToken).toHaveBeenCalled();
    expect(authService.releaseRefreshLock).toHaveBeenCalled();
  });

  it("does not refresh if locked (Follower)", async () => {
    const nowSec = Math.floor(Date.now() / 1000);
    const expiresAt = nowSec + 30.2; // 200ms delay

    (authService.getMe as any).mockResolvedValue({
      user: { email: "test@example.com" },
      expires_at: expiresAt,
    });

    // Mock Lock: Follower
    (authService.isRefreshLocked as any).mockReturnValue(true);

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await waitFor(() =>
      expect(screen.getByTestId("auth-status")).toHaveTextContent(
        "Authenticated"
      )
    );

    await act(async () => {
      await new Promise((resolve) => setTimeout(resolve, 500));
    });

    expect(authService.acquireRefreshLock).not.toHaveBeenCalled();
    expect(authService.refreshToken).not.toHaveBeenCalled();
  });

  it("syncs state via broadcast (Login)", async () => {
    (authService.getMe as any).mockRejectedValue(new Error("Unauthorized"));

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await waitFor(() =>
      expect(screen.getByTestId("auth-status")).toHaveTextContent("Guest")
    );

    await act(async () => {
      const channel = new MockBroadcastChannel("auth_sync_channel");
      channel.postMessage({
        type: "LOGIN_SUCCESS",
        payload: {
          user: { email: "broadcast@example.com" },
          expiresAt: Date.now() / 1000 + 3600,
        },
      });
    });

    await waitFor(() => {
      expect(screen.getByTestId("auth-status")).toHaveTextContent(
        "Authenticated"
      );
      expect(screen.getByTestId("user-email")).toHaveTextContent(
        "broadcast@example.com"
      );
    });
  });

  it("syncs state via broadcast (Logout)", async () => {
    (authService.getMe as any).mockResolvedValue({
      user: { email: "test@example.com" },
      expires_at: Date.now() / 1000 + 3600,
    });

    render(
      <AuthProvider>
        <TestComponent />
      </AuthProvider>
    );

    await waitFor(() =>
      expect(screen.getByTestId("auth-status")).toHaveTextContent(
        "Authenticated"
      )
    );

    await act(async () => {
      const channel = new MockBroadcastChannel("auth_sync_channel");
      channel.postMessage({ type: "LOGOUT" });
    });

    await waitFor(() => {
      expect(screen.getByTestId("auth-status")).toHaveTextContent("Guest");
      expect(mockPush).toHaveBeenCalledWith("/login");
    });
  });
});
