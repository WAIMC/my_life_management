import { call, put, takeEvery } from 'redux-saga/effects';
import axios from 'axios';
import { fetchTodosStart, fetchTodosSuccess, fetchTodosFailure } from '../slices/todoSlice';

import { SagaIterator } from 'redux-saga';

function* fetchTodosSaga(): SagaIterator {
  try {
    console.log('go to fetch to do saga');
    const response = yield call(axios.get, 'https://jsonplaceholder.typicode.com/todos'); // Ví dụ API thật
    yield put(fetchTodosSuccess(response.data.slice(0, 5))); // Lấy 5 items
  } catch (error) {
    yield put(fetchTodosFailure((error as Error).message));
  }
}

export function* todoSaga() {
  yield takeEvery(fetchTodosStart.type, fetchTodosSaga);
}
