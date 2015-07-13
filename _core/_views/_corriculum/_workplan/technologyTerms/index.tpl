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
                    <th>{CHtml::tableOrder("term_id", $objects->getFirstItem())}</th>
                </tr>
            </thead>
            <tbody>
            {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
            {foreach $objects->getItems() as $term}
                <tr>
                    <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить семестр')) { location.href='workplantechnologyterms.php?action=delete&id={$term->getId()}'; }; return false;"></a></td>
                    <td>{counter}</td>
                    <td><a href="workplantechnologyterms.php?action=edit&id={$term->getId()}" class="icon-pencil"></a></td>
                    <td>{$term->term}</td>
                </tr>
            {/foreach}
            </tbody>
        </table>

        {CHtml::paginator($paginator, "workplantechnologyterms.php?action=index")}
    {/if}
{/block}

{block name="asu_right"}
    {include file="_corriculum/_workplan/technologyTerms/common.right.tpl"}
{/block}