{extends file="_core.component.tpl"}

{block name="asu_center"}
    {if ($objects->getCount() == 0)}
        Нет объектов для отображения
    {else}
        <table class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th width="16">&nbsp;</th>
                    <th width="16">#</th>
                    <th width="16">&nbsp;</th>
                    <th>{CHtml::tableOrder("goal", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить цель рабочей программы')) { location.href='workplangoals.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplangoals.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->goal}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/goal/common.right.tpl"}
{/block}