{extends file="layout.tpl"}
{block name=title}Создать пост{/block}
{block name=body}

    <form action="/manage/post/new" method="POST">
        <div>
            <label>
                Категория:
                <select name="post[categories][]" multiple>
                    {foreach $categories as $value}
                        <option value="{$value->id}">{$value->name}</option>
                    {/foreach}
                </select>
            </label>
        </div>
        <div>
            <label>
                Изображение:
                <input type="text" name="post[image]" />
            </label>
        </div>
        <div>
            <label>
                Заголовок:
                <input type="text" name="post[name]" />
            </label>
        </div>
        <div>
            <label>
                Slug:
                <input type="text" name="post[slug]" />
            </label>
        </div>
        <div>
            <label>
                Описание:
                <textarea name="post[description]"></textarea>
            </label>
        </div>
        <div>
            <label>
                Текс:
                <textarea name="post[text]"></textarea>
            </label>
        </div>
        <div>
            <button type="submit">Создать</button>
        </div>
    </form>
{/block}