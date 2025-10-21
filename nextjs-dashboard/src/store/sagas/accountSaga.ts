import { call, put, takeLatest, all } from 'redux-saga/effects';
import { PayloadAction } from '@reduxjs/toolkit';
import {
  fetchAccountApi,
  fetchAccountsApi,
  createAccountApi,
  updateAccountApi,
  deleteAccountApi,
} from '@/common/api/account.api';
import { isApiSuccess, getErrorMessages } from '@/common/api/client';
import type {
  Account,
  FetchAccountPayload,
  FetchAccountsPayload,
  CreateAccountPayload,
  UpdateAccountPayload,
  DeleteAccountPayload,
} from '../types/accountTypes';
import {
  fetchAccountRequest,
  fetchAccountSuccess,
  fetchAccountFailure,
  fetchAccountsRequest,
  fetchAccountsSuccess,
  fetchAccountsFailure,
  createAccountRequest,
  createAccountSuccess,
  createAccountFailure,
  updateAccountRequest,
  updateAccountSuccess,
  updateAccountFailure,
  deleteAccountRequest,
  deleteAccountSuccess,
  deleteAccountFailure,
} from '../slices/accountSlice';

// Saga Workers
function* handleFetchAccount(action: PayloadAction<FetchAccountPayload>) {
  try {
    const response: Awaited<ReturnType<typeof fetchAccountApi>> = yield call(
      fetchAccountApi,
      action.payload.id
    );
    
    if (isApiSuccess(response)) {
      yield put(fetchAccountSuccess(response.data));
    } else {
      const errors = getErrorMessages(response);
      yield put(fetchAccountFailure(errors.join(', ')));
    }
  } catch (error: any) {
    yield put(fetchAccountFailure(error.message || 'Failed to fetch account'));
  }
}

function* handleFetchAccounts(action: PayloadAction<FetchAccountsPayload>) {
  try {
    const response: Awaited<ReturnType<typeof fetchAccountsApi>> = yield call(
      fetchAccountsApi,
      action.payload
    );
    
    if (isApiSuccess(response)) {
      yield put(fetchAccountsSuccess(response.data));
    } else {
      const errors = getErrorMessages(response);
      yield put(fetchAccountsFailure(errors.join(', ')));
    }
  } catch (error: any) {
    yield put(fetchAccountsFailure(error.message || 'Failed to fetch accounts'));
  }
}

function* handleCreateAccount(action: PayloadAction<CreateAccountPayload>) {
  try {
    const response: Awaited<ReturnType<typeof createAccountApi>> = yield call(
      createAccountApi,
      action.payload
    );
    
    if (isApiSuccess(response)) {
      yield put(createAccountSuccess(response.data));
    } else {
      const errors = getErrorMessages(response);
      yield put(createAccountFailure(errors.join(', ')));
    }
  } catch (error: any) {
    yield put(createAccountFailure(error.message || 'Failed to create account'));
  }
}

function* handleUpdateAccount(action: PayloadAction<UpdateAccountPayload>) {
  try {
    const { id, data } = action.payload;
    const response: Awaited<ReturnType<typeof updateAccountApi>> = yield call(
      updateAccountApi,
      id,
      data
    );
    
    if (isApiSuccess(response)) {
      yield put(updateAccountSuccess(response.data));
    } else {
      const errors = getErrorMessages(response);
      yield put(updateAccountFailure(errors.join(', ')));
    }
  } catch (error: any) {
    yield put(updateAccountFailure(error.message || 'Failed to update account'));
  }
}

function* handleDeleteAccount(action: PayloadAction<DeleteAccountPayload>) {
  try {
    const response: Awaited<ReturnType<typeof deleteAccountApi>> = yield call(
      deleteAccountApi,
      action.payload.id
    );
    
    if (isApiSuccess(response)) {
      yield put(deleteAccountSuccess(action.payload.id));
    } else {
      const errors = getErrorMessages(response);
      yield put(deleteAccountFailure(errors.join(', ')));
    }
  } catch (error: any) {
    yield put(deleteAccountFailure(error.message || 'Failed to delete account'));
  }
}

// Saga Watchers
function* watchFetchAccount() {
  yield takeLatest(fetchAccountRequest.type, handleFetchAccount);
}

function* watchFetchAccounts() {
  yield takeLatest(fetchAccountsRequest.type, handleFetchAccounts);
}

function* watchCreateAccount() {
  yield takeLatest(createAccountRequest.type, handleCreateAccount);
}

function* watchUpdateAccount() {
  yield takeLatest(updateAccountRequest.type, handleUpdateAccount);
}

function* watchDeleteAccount() {
  yield takeLatest(deleteAccountRequest.type, handleDeleteAccount);
}

// Root Account Saga
export default function* accountSaga() {
  yield all([
    call(watchFetchAccount),
    call(watchFetchAccounts),
    call(watchCreateAccount),
    call(watchUpdateAccount),
    call(watchDeleteAccount),
  ]);
}
