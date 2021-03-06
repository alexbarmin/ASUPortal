{function name=print_discipline_row level=0}
    <tr>
        <td>{$cycle->title_abbreviated}</td>
        <td>{CHtml::activeViewGroupSelect("id", $discipline, false, true)}</td>
        <td>
            {if !is_null($discipline->cycle)}
    			{if ($discipline->cycle->number != "")}
					{$discipline->cycle->number}.
				{/if}
            {/if}
            {if $discipline->parent_id !== "0"}
                {if !is_null($discipline->parent)}
                    {$discipline->parent->ordering}
                {/if}
				{if ($discipline->ordering) != ""}
					.{$discipline->ordering}
				{/if}
            {else}
                {$discipline->ordering}
            {/if}
        </td>
        <td>
            {if !is_null($discipline->discipline)}
                {for $i=1 to $level}
                    &nbsp;&nbsp;
                {/for}
                <a href="disciplines.php?action=edit&id={$discipline->getId()}">{$discipline->discipline->getValue()}</a>
            {/if}
        </td>
        <!-- Распределение по видам занятий -->
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getLaborValue()}
            {/if}
        </td>
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getLaborAuditor()}
            {/if}
        </td>
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getLaborTheoryEducation()}
            {/if}
        </td>
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getLaborTotal()}
            {/if}
        </td>
        <td>
            {if $discipline->children->getCount() == 0}
                {$discipline->getCreditUnits()}
            {/if}
        </td>
        {foreach $labors->getItems() as $key=>$value}
            <td>
                {if $discipline->getLaborByType($key) != 0}
                    {$discipline->getLaborByType($key)}
                {/if}
            </td>
        {/foreach}
        <!-- Распределение по видам занятий -->
        <td>&nbsp;</td>
    </tr>
{/function}
{if $corriculum->cycles->getCount() == 0}
	Нет дисциплин для отображения
{else}
<form action="index.php" method="post" id="MainView">
<table class="table table-striped table-bordered table-hover table-condensed">
    <thead>
    <tr>
        <td rowspan="2">Цикл</td>
        <td colspan="3">Дисциплины</td>
        <td colspan="{$labors->getCount() + 5}">Распределение нагрузки по видям занятий</td>
        <td>Форма итогового контроля</td>
    </tr>
    <tr>
        <td>{CHtml::activeViewGroupSelect("id", $corriculum->cycles->getFirstItem(), true)}</td>
        <td>№</td>
        <td>Наименование дисциплины</td>
        <td><b>Всего</b></td>
        <td><b>Аудиторные занятия</b></td>
        <td><b>Всего теор. обуч.</b></td>
        <td><b>Трудоемкость общая</b></td>
        <td><b>Зачетные единицы</b></td>
        {foreach $labors->getItems() as $labor}
            <td>
                {if !is_null($labor->type)}
                    {$labor->type->getValue()}
                {/if}
            </td>
        {/foreach}
        <td>&nbsp;</td>
    </tr>
    </thead>
    {foreach $corriculum->cycles->getItems() as $cycle}
        <tr>
            <td colspan="{(10 + $labors->getCount())}">
                <a href="cycles.php?action=edit&id={$cycle->getId()}">{$cycle->title}</a>
            </td>
        </tr>
        {foreach $cycle->disciplines->getItems() as $discipline}
            {print_discipline_row discipline=$discipline level=0}
            {foreach $discipline->children->getItems() as $child}
                {print_discipline_row discipline=$child level=1}
            {/foreach}
        {/foreach}
    {/foreach}
</table>
</form>
{/if}