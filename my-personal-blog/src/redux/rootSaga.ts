// src/redux/rootSaga.ts
import { all } from "redux-saga/effects";
import exampleSaga from "./sagas/exampleSaga";
import { todoSaga } from "./sagas/todoSaga";
import authSaga from "./sagas/authSaga";

export default function* rootSaga() {
  yield all([
    exampleSaga(),
    todoSaga(),
    authSaga(),
  ]);
}
