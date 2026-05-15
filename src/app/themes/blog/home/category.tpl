{extends file='layout.tpl'}
{block name=title}{$category->name}{/block}
{block name=body}
    <div>
        <h1>{$category->name}</h1>
        <div>{$category->description}</div>
        <div>
            <div><a href="{$order->link("created_at")}">По дате публикации</a></div>
            <div><a href="{$order->link("views")}">По количеству просмотров</a></div>
        </div>
        <div>
        {foreach $pager->data as $post}
            <div>
                <div><a href="/post/{$post->slug}">{$post->name}</a></div>
            </div>
        {/foreach}
        </div>
        {include file="components/pager.tpl" pager=$pager}
    </div>
{/block}