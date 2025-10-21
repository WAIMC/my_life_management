import { all, call, delay, put, takeEvery } from "redux-saga/effects";
import { incrementBy, incrementAsync } from "./slices/counterSlice";
import accountSaga from "./sagas/accountSaga";

function* handleIncrementAsync() {
  // simulate async work
  yield delay(500);
  // add 1 when done
  yield put(incrementBy(1));
}

function* watchIncrementAsync() {
  yield takeEvery(incrementAsync.type, handleIncrementAsync);
}

export default function* rootSaga() {
  yield all([
    call(watchIncrementAsync),
    call(accountSaga),
  ]);
}
