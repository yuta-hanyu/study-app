export interface Todos {
  id?: number | null;
  title?: string;
  content?: string;
  state?: number  | 0 | 1 | 2 | 3 | null;
  book_mark?: number | null;
  reminder?: string;
  sort_order?: number | null;
};
