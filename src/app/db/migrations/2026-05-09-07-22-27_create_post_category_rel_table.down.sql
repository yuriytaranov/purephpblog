-- migration create_post_category_rel_table created 2026-05-09-07-22-27
alter table post_category_rel drop foreign key fk_posts_id_post_id;
alter table post_category_rel drop foreign key fk_categories_id_category_id;
drop table post_category_rel;
