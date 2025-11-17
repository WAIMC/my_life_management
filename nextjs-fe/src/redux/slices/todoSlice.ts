import { createSlice, PayloadAction } from '@reduxjs/toolkit';

interface Todo {
  id: number;
  title: string;
  completed: boolean;
}

interface TodoState {
  todos: Todo[];
  loading: boolean;
  error: string | null;
}

const initialState: TodoState = {
  todos: [],
  loading: false,
  error: null,
};

const todoSlice = createSlice({
  name: 'todo',
  initialState,
  reducers: {
    fetchTodosStart: (state) => {
      state.loading = true;
      state.error = null;
    },
    fetchTodosSuccess: (state, action: PayloadAction<Todo[]>) => {
      state.loading = false;
      state.todos = action.payload;
    },
    fetchTodosFailure: (state, action: PayloadAction<string>) => {
      state.loading = false;
      state.error = action.payload;
    },
    addTodo: (state, action: PayloadAction<Omit<Todo, 'id'>>) => {
      state.todos.push({ ...action.payload, id: Date.now() });
    },
  },
});

export const { fetchTodosStart, fetchTodosSuccess, fetchTodosFailure, addTodo } = todoSlice.actions;
export default todoSlice.reducer;