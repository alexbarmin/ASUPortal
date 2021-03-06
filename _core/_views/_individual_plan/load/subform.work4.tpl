{if ($load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getCount() == 0)}
    <div class="alert alert-block">
        Нет данных для отображения
    </div>
{else}
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th></th>
            <th>#</th>
            <th>{CHtml::tableOrder("title_id", $load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("plan_hours", $load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("plan_expiration_date", $load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("is_executed", $load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getFirstItem())}</th>
            <th>{CHtml::tableOrder("comment", $load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getFirstItem())}</th>
        </tr>
        {counter start=0 print=false}
        {foreach $load->getWorksByType(CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)->getItems() as $work}
            <tr>
                <td>
                    <a href="work.php?action=edit&id={$work->getId()}&year={$year}">
                        <i class="icon-pencil"></i>
                    </a>
                </td>
                <td>
                    <a href="#" onclick="if (confirm('Действительно удалить запись?')) { location.href='work.php?action=delete&id={$work->getId()}'; }; return false;">
                        <i class="icon-trash"></i>
                    </a>
                </td>
                <td>{counter}</td>
                <td>{$work->getTitle()}</td>
                <td>{$work->plan_hours}</td>
                <td>{$work->plan_expiration_date}</td>
                <td>{$work->isExecuted()}</td>
                <td>{$work->comment}</td>
            </tr>
        {/foreach}
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        	<tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><b>Итого запланировано</b></td>
                <td><b>{CIndividualPlanLoadService::getSumPlanAmountWorksByType($load, CIndPlanPersonWorkType::STUDY_AND_EDUCATIONAL_LOAD)}</b></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        {if (CIndividualPlanLoadService::getTotalHoursRates($load) != 0) }
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Разница</td>
                <td>
                    {if (CIndividualPlanLoadService::getDifferenceHours($load) > 0)}
                        <font color='#FF0000'>{CIndividualPlanLoadService::getDifferenceHours($load)}</font>
                    {else}
                        <font color='#00FF00'>{CIndividualPlanLoadService::getDifferenceHours($load)}</font>
                    {/if}
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        {else}
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>В нагрузке не указан приказ!</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        {/if}
    </table>
{/if}