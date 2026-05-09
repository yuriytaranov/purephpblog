-- migration create_posts_table created 2026-05-09-06-16-31

create table posts
(
    id          int auto_increment primary key,
    image       varchar(255)                        not null,
    name        varchar(255)                        not null,
    slug        varchar(255)                        not null,
    description text                                null,
    text        text                                null,
    views       int                                 not null default 0,
    created_at  timestamp default current_timestamp not null,
    updated_at  timestamp default current_timestamp not null on update current_timestamp,
    deleted_at  timestamp                           null
);

alter table posts add unique index idx_posts_slug (slug);

alter table posts add index idx_posts_views (views);

alter table posts add index idx_posts_created_at (created_at);

alter table posts add index idx_posts_deleted_at (deleted_at);
