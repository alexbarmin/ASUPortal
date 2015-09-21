<?php

class CWorkPlanEducationTechnologiesDescription extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Описание образовательных технологий";
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
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
		$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
		$result = $plan->education_technologies;
        return $result;
    }
}