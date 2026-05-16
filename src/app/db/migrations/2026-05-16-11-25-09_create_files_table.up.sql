-- migration create_files_table created 2026-05-16-11-25-09

create table files
(
    id         int auto_increment primary key,
    path       varchar(512)                        not null,
    name       varchar(255)                        not null,
    mime_type  varchar(100)                        not null,
    size       int                                 not null,
    hash       varchar(64)                         null,
    created_at timestamp default current_timestamp not null,
    deleted_at timestamp                           null
);

alter table files add unique index idx_files_hash (hash);

alter table files add index idx_files_deleted_at (deleted_at);

alter table files add index idx_files_path_name (path, name);
