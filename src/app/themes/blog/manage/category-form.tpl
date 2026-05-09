{extends file='layout.tpl'}
{block name=title}New Category{/block}
{block name=body}
    <form action="/manage/category/new" method="POST">
        <div>
            <label>
                Название:
                <input type="text" name="category[name]"/>
            </label>
        </div>
        <div>
            <label>
                Slug:
                <input type="text" name="category[slug]"/>
            </label>
        </div>
        <div>
            <label>
                Описание:
                <textarea name="category[description]"></textarea>
            </label>
        </div>
        <div>
            <button type="submit">
                Создать
            </button>
        </div>
    </form>
{/block}