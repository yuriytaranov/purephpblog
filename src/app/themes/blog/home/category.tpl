{extends file='layout.tpl'}
{block name=title}{$category->name|escape:html}{/block}
{block name=body}
    <div>
        <h1>{$category->name|escape:html}</h1>
        <div>{$category->description|escape:html}</div>
        <div>
            <div><a href="{$order->link("created_at")}">По дате публикации</a></div>
            <div><a href="{$order->link("views")}">По количеству просмотров</a></div>
        </div>
        <div>
        {foreach $pager->data as $post}
            <div>
                <div><a href="/post/{$post->slug|escape}">{$post->name|escape:html}</a></div>
            </div>
        {/foreach}
        </div>
        {include file="components/pager.tpl" pager=$pager}
    </div>
{/block}