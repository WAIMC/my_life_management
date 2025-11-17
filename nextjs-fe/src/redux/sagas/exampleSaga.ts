import { takeEvery, put, delay } from "redux-saga/effects";
import { increment } from "../slices/exampleSlice";

function* handleIncrement() {
  yield delay(1000); // Giả lập xử lý bất đồng bộ
  console.log("Increment async action");
  yield put(increment());
}

export default function* exampleSaga() {
  yield takeEvery("example/incrementAsync", handleIncrement);
}
