<?php

class CCourseProjectHeadOfDepartment extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Заведующий кафедрой";
    }

    public function getFieldDescription()
    {
        return "Используется при печати курсового проекта, принимает параметр id с Id рабочей программы";
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
        $result = CStaffService::getHeadOfDepartment();
        return $result;
    }
}