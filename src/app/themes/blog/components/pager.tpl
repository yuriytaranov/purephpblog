
<div>Всего: {$pager->total}</div>
<div>
    {if not $pager->isFirst()}
    <a href="{$pager->first()}">В начало</a>
    {/if}
    {foreach $pager->pages(5) as $i => $page}
        {if $pager->current eq $i}
            <strong>{$i}</strong>
        {else}
            <a href="{$page}">{$i}</a>
        {/if}
    {/foreach}
    {if not $pager->isLast()}
    <a href="{$pager->last()}">В конец</a>
    {/if}
</div>