-- migration create_categories_table created 2026-05-09-06-01-31
create table categories
(
    id          int auto_increment primary key,
    name        varchar(255)                        not null,
    slug        varchar(255)                        not null,
    description text                                null,
    created_at  timestamp default current_timestamp not null,
    updated_at  timestamp default current_timestamp not null on update current_timestamp,
    deleted_at  timestamp                           null
);

alter table categories add unique index idx_categories_slug (slug);

alter table categories add index idx_categories_deleted_at (deleted_at);