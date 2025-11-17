'use client';

import { useEffect } from "react";
import { useAppDispatch, useAppSelector } from "../redux/hooks";
import { increment, decrement } from "../redux/slices/exampleSlice";
import { fetchTodosStart } from "../redux/slices/todoSlice";

export default function Home() {
  const dispatch = useAppDispatch();
  const { todos, loading: todosLoading } = useAppSelector((state) => state.todo);
  const value = useAppSelector((state) => state.example.value);

  useEffect(() => {
    dispatch(fetchTodosStart()); // Trigger todos saga
  }, [dispatch]);

  return (
    <div className="flex min-h-screen items-center justify-center bg-zinc-50 font-sans dark:bg-black">
      <div>
        <h1 className="text-2xl">Value: {value}</h1>
        <button onClick={() => dispatch(increment())}>
          Increment
        </button>
        <button onClick={() => dispatch({ type: "example/incrementAsync" })}>
          Increment Async
        </button>
        <button onClick={() => dispatch(decrement())}>Decrement</button>

        <h1>Todos (Redux Saga)</h1>
        {todosLoading ? <p>Loading todos...</p> : (
          <ul>{todos.map((item) => <li key={item.id}>{item.title}</li>)}</ul>
        )}
      </div>
    </div>
  );
}