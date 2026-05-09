{extends file='layout.tpl'}
{block name=title}Главная страница{/block}
{block name=body}
    {foreach $data as $category}
        <div>
            <div>{$category->category_name}</div>
            <ul>
            {foreach $category->posts as $post}
                <li>
                    <a href="/post/{$post->post_slug}">{$post->post_name}</a>
                </li>
            {/foreach}
            </ul>
            <div><a href="/category/{$category->category_slug}">Все посты</a></div>
        </div>
    {/foreach}
{/block}