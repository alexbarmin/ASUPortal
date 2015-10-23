<?php

class CWorkPlanStructure2 extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Структура дисциплины. Без столбца Итого";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TABLE;
    }

    public function execute($contextObject)
    {
        $result = array();
        $terms = array();
        $terms[] = "term.name";
        $termIds = array();
        foreach ($contextObject->terms->getItems() as $term) {
        	$termIds[] = $term->getId();
        	$terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
        }
        $query = new CQuery();
        $query->select(join(", ", $terms))
	        ->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
	        ->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	        ->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	        ->condition("category.plan_id = ".$contextObject->getId())
	        ->group("l.load_type_id")
	        ->order("term.name");
        $objects = $query->execute();
        foreach ($objects->getItems() as $key=>$value) {
        	$arr = array_values($value);
        	$dataRow = array();
        	for ($i = 0; $i <= count($value)-1; $i++) {
        		$dataRow[$i] = $arr[$i];
        	}
        	$result[] = $dataRow;
        }
        return $result;
    }
}