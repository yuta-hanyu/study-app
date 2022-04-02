export interface BookMarks {
  id?: number | null;
  user_id?: number | null;
  book_mark_folders_id?: number | null;
  title?: string;
  link?: string;
  memo?: string;
  fixed?: number | null;
  is_deleted?: number | null;
};
