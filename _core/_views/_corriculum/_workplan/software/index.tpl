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
                    <th>{CHtml::tableOrder("software_id", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $object}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить ПО')) { location.href='workplansoftware.php?action=delete&id={$object->getId()}'; }; return false;"></a></td>
                    <td>{$object->ordering}</td>
                    <td><a href="workplansoftware.php?action=edit&id={$object->getId()}" class="icon-pencil"></a></td>
                    <td>{$object->software}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplansoftware.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/software/common.right.tpl"}
{/block}