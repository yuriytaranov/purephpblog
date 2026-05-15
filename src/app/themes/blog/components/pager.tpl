
<div>Всего постов: {$pager->total}</div>
<div>
    {foreach $pager->pages() as $i => $page}
        {if $pager->current eq $i}
            <strong>{$i}</strong>
        {else}
            <a href="{$page}">{$i}</a>
        {/if}
    {/foreach}
</div>