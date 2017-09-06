<form action="index.php" method="post" id="{$loadsId}">
    <table rel="stripe" class="table table-striped table-bordered table-hover table-condensed" border="1">
        <tr>
            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
            	<th>&nbsp;</th>
            	<th>&nbsp;</th>
            {/if}
            <th style="vertical-align:middle; text-align:center;">#</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::activeViewGroupSelect("id", $studyLoads->getFirstItem(), true)}</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("discipline_id", $studyLoads->getFirstItem(), false, false)}</th>
            <th style="vertical-align:bottom;"><div class="vert-text">Факультет</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("speciality_id", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("level_id", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("groups_count", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("students_count", $studyLoads->getFirstItem(), false, false)}</div></th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("load_type_id", $studyLoads->getFirstItem(), false, false)}</th>
            <th style="vertical-align:middle; text-align:center;">{CHtml::tableOrder("comment", $studyLoads->getFirstItem(), false, false)}</th>
	            {foreach $studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if in_array($kindId, array(0))}
							<th style="vertical-align:bottom;"><div class="vert-text">{$value}</div></th>
						{/if}
	                {/foreach}
	            {/foreach}
            <th style="vertical-align:bottom;"><div class="vert-text">Всего</div></th>
            <th style="vertical-align:bottom;"><div class="vert-text">{CHtml::tableOrder("on_filial", $studyLoads->getFirstItem(), false, false)}</div></th>
        </tr>
        <tr>
	        {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
	            {$ths = 14}
	        {else}
	            {$ths = 12}
	        {/if}
	        {for $i=1 to $ths + count($studyLoads->getFirstItem()->getStudyLoadTable()->getTableTotal())}
	            <th style="text-align:center; background-color: #E6E6FF;">{$i}</th>
	        {/for}
        </tr>
        {counter start=0 print=false}
        {foreach $studyLoads->getItems() as $studyLoad}
	        <tr>
	            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
	            	<td><a href="#" class="icon-trash"  title="Удалить" onclick="if (confirm('Действительно удалить нагрузку')) { location.href='?action=delete&id={$studyLoad->getId()}'; }; return false;"></a></td>
	            	<td><a href="../../_modules/_version_controls/index.php?action=index&id={$studyLoad->getId()}&module=_study_loads&class=CStudyLoad" class="icon-list-alt" title="Посмотреть версии" target="_blank"></a></td>
	            {/if}
	            <td>{counter}</td>
	            <td>{CHtml::activeViewGroupSelect("id", $studyLoad, false, true)}</td>
	            {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
	            	<td><a href="?action=edit&id={$studyLoad->getId()}" title="{", "|join:CStudyLoadService::getLecturersNameByDiscipline($studyLoad->discipline)->getItems()}">{$studyLoad->discipline->getValue()}</a></td>
	            {else}
	            	<td>{$studyLoad->discipline->getValue()}</td>
	            {/if}
	            <td>ИРТ</td>
	            <td>{$studyLoad->direction->getValue()}</td>
	            <td>{$studyLoad->studyLevel->name}</td>
	            <td>{$studyLoad->groups_count}</td>
	            <td>{$studyLoad->students_count + $studyLoad->students_contract_count}</td>
	            <td>{$studyLoad->studyLoadType->name}</td>
	            <td>{$studyLoad->comment}</td>
		            {foreach $studyLoad->getStudyLoadTable()->getTableTotal() as $typeId=>$rows}
						{foreach $rows as $kindId=>$value}
							{if !in_array($kindId, array(0))}
								<td>{clearNullValues number=number_format($value,1,',','') level=0}</td>
							{/if}
		                {/foreach}
		            {/foreach}
	            <td>{clearNullValues number=number_format($studyLoad->getSumWorksValue(),1,',','') level=0}</td>
	            <td>{clearNullValues number=number_format($studyLoad->getWorkWithFilialsTotals(),1,',','') level=0}</td>
	        </tr>
        {/foreach}
        <tr>
			{if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
            	<td>&nbsp;</td>
            	<td>&nbsp;</td>
            {/if}
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td><b>Итого</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
	            {foreach CStudyLoadService::getStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes)->getItems() as $typeId=>$rows}
					{foreach $rows as $kindId=>$value}
						{if !in_array($kindId, array(0))}
							<td><b>{clearNullValues number=number_format($value,1,',','') level=0}</b></td>
						{/if}
	                {/foreach}
	            {/foreach}
			<td><b>{clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPart($lecturer, $year, $part, $loadTypes),1,',','') level=0}</b></td>
			<td><b>{clearNullValues number=number_format(CStudyLoadService::getAllStudyWorksTotalValuesByLecturerAndPartWithFilials($lecturer, $year, $part, $loadTypes),1,',','') level=0}</b></td>
		</tr>
    </table>
    {if (CSessionService::hasAnyRole([$ACCESS_LEVEL_READ_ALL, $ACCESS_LEVEL_WRITE_ALL]))}
    	{CHtml::hiddenField("action", "copy")}
		{CHtml::hiddenField("kadri_id", $lecturer->getId())}
		{CHtml::hiddenField("year_id", $year->getId())}
	    <table border="0" width="100%" class="tableBlank">
			<tr>
				<td valign="top" width="500">
					<div class="controls">
						{CHtml::dropDownList("choice", $copyWays, "", null, "span12")}
					</div>
				</td>
			    <td valign="top">
					<div class="controls">
						{CHtml::dropDownList("lecturer", CStaffManager::getPersonsListWithType("профессорско-преподавательский состав"), $lecturer->getId(), null, "span12")}
					</div>
				</td>
			    <td valign="top">
					<div class="controls">
						{CHtml::dropDownList("year", CTaxonomyManager::getYearsList(), $year->getId(), null, "span12")}
					</div>
				</td>
			    <td valign="top">
					<div class="controls">
						{CHtml::dropDownList("part", CTaxonomyManager::getYearPartsList(), "", null, "span12")}
					</div>
				</td>
			    <td valign="top">
			    	<div class="controls">
						<input name="" type="submit" class="btn" value="ok">
					</div>	
				</td>
			</tr>
		</table>
    {/if}
</form>

<script>
	$(document).ready(function() {
		updateTableStripe();
		function updateTableStripe() {
			$('.table[rel="stripe"] tr').each(function(i) {
				if (i % 2 === 0) {
					$(this).find("td").css('background', '#c5d0e6');
				} else {
					$(this).find("td").css('background', '#DFEFFF');
				}
			});
		}
	});
</script>