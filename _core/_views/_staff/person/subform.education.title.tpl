<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th width="10"></th>
        <th width="10">#</th>
        <th width="30%">Степень</th>
        <th>Год присвоения</th>
        <th>Серия, номер свидетельства</th>
        <th width="20%">Доп. информация</th>
        <th width="32"><i class="icon-camera"></i></th>
    </tr>
    {counter start=0 print=false}
    {foreach $form->person->degrees->getItems() as $degree}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить звание?')) { location.href='papers.php?action=delete&id={$degree->getId()}'; }; return false;"></a></td>
            <td><a href="papers.php?action=edit&id={$degree->getId()}">{counter}</a></td>
            <td>
                {if !is_null($degree->degree)}
                    {$degree->degree->getValue()}
                {/if}
            </td>
            <td>{$degree->god_zach}</td>
            <td>номер {$degree->doc_num}, серия {$degree->doc_series}</td>
            <td>{$degree->comment}</td>
            <td>{CHtml::activeAttachPreview("file_attach", $degree, true)}</td>
        </tr>
    {/foreach}
</table>