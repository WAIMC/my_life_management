"use client";

import React from "react";
import { useDispatch, useSelector } from "react-redux";
import type { RootState } from "../store/rootReducer";
import {
  increment,
  decrement,
  incrementAsync,
  incrementBy,
} from "../store/slices/counterSlice";

export default function CounterClient() {
  const dispatch = useDispatch();
  const value = useSelector((s: RootState) => s.counter.value);

  return (
    <div className="p-4 border rounded">
      <div>Counter: {value}</div>
      <div className="flex gap-2 mt-2">
        <button onClick={() => dispatch(decrement())}>-</button>
        <button onClick={() => dispatch(increment())}>+</button>
        <button onClick={() => dispatch(incrementBy(5))}>+5</button>
        <button onClick={() => dispatch(incrementAsync())}>+ async</button>
      </div>
    </div>
  );
}
