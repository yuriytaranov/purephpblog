{extends "layout.tpl"}
{block name=title}{$post->name|escape:html}}{/block}
{block name=body}
    <h1>{$post->name}</h1>
    <div>
        Создано: {$post->created_at|date_format:"%d.%m.%Y %H:%M"}
        {if $post->created_at neq $post->updated_at}
            Обновлено: {$post->updated_at|date_format:"%d.%m.%Y %H:%M"}
        {/if}
        Просмотров: {$post->views}
    </div>
    <div>
        {if $imageUrl gt 0}
        <img src="/file/{$imageUrl|escape}" />
        {/if}
    </div>
    <div>
        {$post->description|escape:html}
    </div>
    <div>
        {$post->text|escape:html}
    </div>
    <div>
        <div>Похожие посты</div>
        {foreach $similar as $post}
            <div>
                <div><a href="/post/{$post->slug}">{$post->name}</a></div>
            </div>
        {/foreach}
    </div>
{/block}