{extends file='layout.tpl'}
{block name=title}Главная страница{/block}
{block name=body}
    {foreach $data as $category}
        <div>
            <div>{$category->category_name|escape:html}</div>
            <ul>
            {foreach $category->posts as $post}
                <li>
                    <a href="/post/{$post->post_slug|escape}">{$post->post_name|escape:html}</a>
                </li>
            {/foreach}
            </ul>
            <div><a href="/category/{$category->category_slug|escape}">Все посты</a></div>
        </div>
    {/foreach}
{/block}