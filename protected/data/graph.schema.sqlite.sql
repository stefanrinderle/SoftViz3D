CREATE TABLE tbl_EdgeElement (parent_id integer, id INTEGER PRIMARY KEY, label TEXT, out_id integer, in_id integer);
CREATE TABLE tbl_TreeElement (level integer, id INTEGER PRIMARY KEY, parent_id integer, label TEXT);