-- migration link_posts_to_files created 2026-05-16-12-05-41

alter table posts
    add constraint fk_file_id_files_id
        foreign key (file_id) references files (id)
            on update cascade on delete set null;