-- migration link_posts_to_files created 2026-05-16-12-05-41

alter table posts
    drop foreign key fk_file_id_files_id;