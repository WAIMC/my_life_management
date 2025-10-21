import { createSlice, PayloadAction } from "@reduxjs/toolkit";

interface CounterState {
  value: number;
}

const initialState: CounterState = { value: 0 };

const counterSlice = createSlice({
  name: "counter",
  initialState,
  reducers: {
    increment(state) {
      state.value += 1;
    },
    decrement(state) {
      state.value -= 1;
    },
    incrementBy(state, action: PayloadAction<number>) {
      state.value += action.payload;
    },
    // action intended for saga
    incrementAsync() {},
  },
});

export const { increment, decrement, incrementBy, incrementAsync } =
  counterSlice.actions;

export default counterSlice.reducer;
