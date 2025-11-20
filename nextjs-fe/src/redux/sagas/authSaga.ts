import { call, put, takeEvery } from 'redux-saga/effects';
import * as API from '@/lib/apiMethod';
import { clearAuth, setRedirectUrl } from '@/redux/slices/authSlice';
import { setLoading } from '@/redux/slices/commonSlice';
import { LOGIN, LOGOUT } from '@/constants/apiUrl';
import toast from 'react-hot-toast';
import { SagaIterator } from 'redux-saga';
import { LoginPayload, LoginResponseData } from '@/types/authType';
import * as CLIENT_URL from '@/constants/clientUrl';
import { syncAuthStateAcrossTabs, clearAutoRefresh } from '@/lib/authManager';
import broadcastManager from '@/lib/broadcastChannelManager';

function* loginSaga(action: { type: string; payload: LoginPayload }): SagaIterator {
  try {
    console.log('Login payload:', action.payload);
    yield put(setLoading(true));
    
    // Call login API with proper typing - apiPost now returns unwrapped data
    const response: LoginResponseData = (yield call(
      API.apiPost<LoginResponseData>,
      LOGIN,
      action.payload
    )) as LoginResponseData;
    
    const accessToken = response.access_token;
    const ttl = response.ttl;

    // Logic 3: Đồng bộ trạng thái đăng nhập giữa các tab
    syncAuthStateAcrossTabs(accessToken, ttl);

    // Determine redirect URL
    let redirectUrl = CLIENT_URL.ADMIN;
    if (typeof window !== 'undefined') {
      const searchParams = new URLSearchParams(window.location.search);
      const redirectParam = searchParams.get('redirect');

      // Only redirect to admin URL if it starts with /admin
      if (redirectParam && redirectParam.startsWith(CLIENT_URL.ADMIN)) {
        redirectUrl = redirectParam;
      } else {
        redirectUrl = CLIENT_URL.ADMIN;
      }
    }

    yield put(setRedirectUrl(redirectUrl));

    // Redirect after successful login
    if (typeof window !== 'undefined') {
      window.location.href = redirectUrl;
    }
  } catch (error) {
    yield put(clearAuth());
    clearAutoRefresh();
    const errorMessage = error instanceof Error ? error.message : 'An unknown error occurred';
    toast.error('Đăng nhập thất bại: ' + errorMessage);
  } finally {
    yield put(setLoading(false));
  }
}

function* logoutSaga(): SagaIterator {
  try {
    yield put(setLoading(true));
    yield call(API.apiPost, LOGOUT, {});
    yield put(clearAuth());
    clearAutoRefresh();

    // Logic 5: Đăng xuất tất cả tab cùng origin
    // Broadcast logout message
    const tabId = broadcastManager.getTabId();
    broadcastManager.broadcastLogout(tabId);

    toast.success('Logout successful');

    if (typeof window !== 'undefined') {
      window.location.href = CLIENT_URL.LOGIN;
    }
  } catch (error) {
    yield put(clearAuth());
    clearAutoRefresh();
    const errorMessage = error instanceof Error ? error.message : 'An unknown error occurred';
    toast.error('Logout failed: ' + errorMessage);
  } finally {
    yield put(setLoading(false));
  }
}

export default function* authSaga() {
  yield takeEvery('auth/loginRequest', loginSaga);
  yield takeEvery('auth/logoutRequest', logoutSaga);
}
