-- migration create_post_category_rel_table created 2026-05-09-07-22-27
create table post_category_rel
(
    id          int auto_increment primary key,
    post_id     int not null,
    category_id int not null
);

alter table post_category_rel
    add constraint fk_categories_id_category_id
        foreign key (category_id) references categories (id)
            on update cascade on delete cascade;

alter table post_category_rel
    add constraint fk_posts_id_post_id
        foreign key (post_id) references posts (id)
            on update cascade on delete cascade;

alter table post_category_rel
    add index idx_post_category_rel_post_id (post_id);

alter table post_category_rel
    add index idx_post_category_rel_category_id (category_id);

alter table post_category_rel
    add unique index idx_post_category (post_id, category_id);
