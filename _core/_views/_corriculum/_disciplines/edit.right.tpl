<p>
    <a href="cycles.php?action=edit&id={$discipline->cycle_id}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
        Назад
    </center></a>
</p>

<p>
    <a href="?action=del&id={$discipline->getId()}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/places/user-trash.png"><br>
        Удалить
    </center></a>
</p>

<p>
    <a href="labors.php?action=add&id={$discipline->getId()}"><center>
        <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
        Добавить вид нагрузки
    </center></a>
</p>

<p>
    <a href="competentions.php?action=add&id={$discipline->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить компетенцию
        </center></a>
</p>

<p>
    <a href="workplans.php?action=add&id={$discipline->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/list-add.png"><br>
            Добавить рабочую программу
        </center></a>
</p>