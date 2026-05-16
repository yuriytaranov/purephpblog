{extends "layout.tpl"}
{block name=title}{$post->name}{/block}
{block name=body}
    <div>
        Создано: {$post->created_at|date_format:"%d.%m.%Y %H:%M"}
        {if $post->created_at neq $post->updated_at}
            Обновлено: {$post->updated_at|date_format:"%d.%m.%Y %H:%M"}
        {/if}
        Просмотров: {$post->views}
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