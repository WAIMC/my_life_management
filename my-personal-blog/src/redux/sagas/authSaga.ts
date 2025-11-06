import { call, put, takeEvery } from 'redux-saga/effects';
import api from '@/lib/api';
import { loginStart, loginFailure, loginSuccess, refreshSuccess, logout } from '@/redux/slices/authSlice';
import {setLoading} from '@/redux/slices/commonSlice';
import { LOGIN, REFRESH_TOKEN, LOGOUT } from '@/lib/endPoint'
import toast from 'react-hot-toast';
import { SagaIterator } from 'redux-saga';

interface LoginPayload {
  user_name: string;
  password: string;
}

function* loginSaga(action: { type: string; payload: LoginPayload }): SagaIterator {
  try {
    yield put(setLoading(true));
    yield put(loginStart());

    const response = yield call(api.post, LOGIN, action.payload);
    const accessToken = response?.data?.data?.access_token;

    yield put(loginSuccess(accessToken));
  } catch (error) {
    yield put(loginFailure());
    const errorMessage = error instanceof Error ? error.message : 'An unknown error occurred';
    toast.error('Login failed: ' + errorMessage);
  } finally {
    yield put(setLoading(false));
  }
}

function* refreshSaga(): SagaIterator {
  try {
    yield put(setLoading(true));
    yield put(loginStart());

    const response = yield call(api.post, REFRESH_TOKEN, []);
    const accessToken = response?.data?.data?.access_token;

    yield put(refreshSuccess(accessToken));
  } catch (error) {
    yield put(logout());
    toast.error('Session expired. Please login again.');

    if (typeof window !== 'undefined') {
      window.location.href = '/login'; // Redirect login if refresh fail
    }

    throw error;
  } finally {
    yield put(setLoading(false));
  }
}

function* logoutSaga(): SagaIterator {
  try {
    yield put(loginStart());
    yield call(api.post, LOGOUT, []);
    yield put(logout());
    window.location.reload();
  } catch (error) {
    toast.error('Logout fail. Please try again.');
    throw error;
  }
}

export default function* authSaga() {
  yield takeEvery('auth/loginSaga', loginSaga);
  yield takeEvery('auth/logoutSaga', logoutSaga);
}

export { refreshSaga };
