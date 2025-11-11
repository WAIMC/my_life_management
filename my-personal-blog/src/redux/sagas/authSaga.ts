import { call, put, takeEvery } from 'redux-saga/effects';
import * as API from '@/lib/apiMethod';
import { clearAuth, setAuth } from '@/redux/slices/authSlice';
import {setLoading} from '@/redux/slices/commonSlice';
import { LOGIN, LOGOUT } from '@/constants/apiUrl'
import toast from 'react-hot-toast';
import { SagaIterator } from 'redux-saga';
import { LoginPayload } from '@/types/authType';
import * as CLIENT_URL from '@/constants/clientUrl';

function* loginSaga(action: { type: string; payload: LoginPayload }): SagaIterator {
  try {
    yield put(setLoading(true));
    const response = yield call(API.apiPost, LOGIN, action.payload);
    const accessToken = response?.data?.data?.access_token;
    
    if (!accessToken) {
      throw new Error('No access token received');
    }
    
    yield put(setAuth(accessToken));
    toast.success('Login successful');
  } catch (error) {
    yield put(clearAuth());
    const errorMessage = error instanceof Error ? error.message : 'An unknown error occurred';
    toast.error('Login failed: ' + errorMessage);
  } finally {
    yield put(setLoading(false));
  }
}

function* logoutSaga(): SagaIterator {
  try {
    yield put(setLoading(true));
    yield call(API.apiPost, LOGOUT, {});
    yield put(clearAuth());
    toast.success('Logout successful');
    
    if (typeof window !== 'undefined') {
      window.location.href = CLIENT_URL.LOGIN;
    }
  } catch (error) {
    const errorMessage = error instanceof Error ? error.message : 'An unknown error occurred';
    toast.error('Logout failed: ' + errorMessage);
  } finally {
    yield put(setLoading(false));
  }
}

export default function* authSaga() {
  yield takeEvery('auth/loginSaga', loginSaga);
  yield takeEvery('auth/logoutSaga', logoutSaga);
}
