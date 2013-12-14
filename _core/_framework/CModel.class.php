<?php
/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 04.05.12
 * Time: 22:17
 * To change this template use File | Settings | File Templates.
 */
class CModel {
    private $_errors = null;
    private static $_thisObject = null;
    /**
     * Название класса
     *
     * @static
     * @return string
     */
    public static function getClassName() {
        return __CLASS__;
    }
    /**
     * Переопределяемый метод для указания отношений между сущностями
     * @return array
     */
    protected function relations() {
        return array();
    }

    /**
     * Получаем отношения между объектами
     *
     * @return array
     */
    public function getRelations() {
        return $this->relations();
    }
    /**
     * Переопределяемый метод для указания названий полей в сущности
     *
     * @return array
     */
    public function attributeLabels() {
        return array();
    }
    /**
     * Автоматическая установка всех полей в записи из запроса
     *
     * @param array $array
     */
    public function setAttributes(array $array) {
        // загрузка файлов из загружаемых полей
        foreach ($this->fieldsProperty() as $field=>$property) {
            if ($property['type'] == FIELD_UPLOADABLE) {
                if (array_key_exists($this::getClassName(), $_FILES)) {
                    if ($_FILES[$this::getClassName()]['tmp_name'][$field] !== "") {
                        $fileName = CUtils::toTranslit($_FILES[$this::getClassName()]['name'][$field]);
                        $fileName = date("dmY_His")."_".$fileName;
                        /**
                         * Проверяем, что upload_dir существует
                         * Если его нет, то создадим
                         */
                        $uploadDir = $property["upload_dir"];
                        $uploadDir = explode(CORE_DS, $uploadDir);
                        $checkPath = CORE_DS;
                        foreach ($uploadDir as $path) {
                            if ($path !== "") {
                                $checkPath .= $path.CORE_DS;
                                if (!file_exists($checkPath)) {
                                    mkdir($checkPath, 0777);
                                }
                            }
                        }
                        while (file_exists($property['upload_dir'].$fileName)) {
                            $fileName = date("dmY_His")."_".$fileName;
                        }
                        move_uploaded_file($_FILES[$this::getClassName()]['tmp_name'][$field], $property['upload_dir'].$fileName);
                        $array[$field] = $fileName;
                    }
                }
            }
        }
        foreach ($array as $key=>$value) {
            $this->$key = $value;
        }
    }
    /**
     * Правила валидации модели, например, проверки на обязательные поля
     *
     * @return array
     */
    protected function validationRules() {
        return array();
    }
    /**
     * Правила валидации для работы с CHtml
     * @return arrray
     */
    public function getValidationRules() {
	    return $this->validationRules();
    }
    /**
     * Функция валидации данных модели
     *
     * @return bool
     */
    public function validate() {
        /**
         * Валидация полей модели
         */
        $rules = CCoreObjectsManager::getFieldValidators($this);
        $labels = CCoreObjectsManager::getAttributeLabels($this);
        foreach ($rules as $field=>$validators) {
            foreach ($validators as $validator) {
                if (is_object($validator)) {
                    /**
                     * Это новая система валидаторов
                     */
                    if (!$validator->run($this->$field)) {
                        $error = str_replace("%name%", $labels[$field], $validator->getError());
                        $this->getValidationErrors()->add($field, $error);
                    }
                } elseif (is_string($validator)) {
                    /**
                     * Это старая система валидаторов
                     */
                    if ($validator == "required") {
                        if ($this->$field == "") {
                            $error = str_replace("%name%", $labels[$field], ERROR_FIELD_REQUIRED);
                            $this->getValidationErrors()->add($field, $error);
                        }
                    } elseif ($validator == "numeric") {
                        if (!is_numeric($this->$field)) {
                            $error = str_replace("%name%", $labels[$field], ERROR_FIELD_NUMERIC);
                            $this->getValidationErrors()->add($field, $error);
                        }
                    } elseif ($validator == "selected") {
                        if ($this->$field == 0) {
                            $error = str_replace("%name%", $labels[$field], ERROR_FIELD_SELECTED);
                            $this->getValidationErrors()->add($field, $error);
                        }
                    } elseif ($validator == "checkdate") {
                        if ($this->$field != "") {
                            $dateValue = $this->$field;
                            $error = str_replace("%name%", $labels[$field], ERROR_FIELD_NOT_A_DATE);
                            if (strtotime($dateValue) === false) {
                                $this->getValidationErrors()->add($field, $error);
                            } else {
                                $dateArray = explode(".", $dateValue);
                                if (!checkdate($dateArray[1], $dateArray[0], $dateArray[2])) {
                                    $this->getValidationErrors()->add($field, $error);
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->validateModel(VALIDATION_EVENT_UPDATE);

        return $this->getValidationErrors()->getCount() == 0;
    }

    /**
     * Валидация модели по указанному событию
     *
     * @param $event
     * @return bool
     */
    protected function validateModel($event) {
        $isValid = true;
        /**
         * Валидация самой модели
         * Если текущая модель - наследник CFormModel, то, если не можем найти модель
         * для нее, смотрим модели объектов в публичных свойствах и валидируем их
         */
        $modelsToValidate = array();
        if (is_a($this, "CFormModel")) {
            $model = CCoreObjectsManager::getCoreModel($this);
            if (!is_null($model)) {
                $modelsToValidate[] = $this;
            } else {
                $publicVars = get_object_vars($this);
                foreach ($publicVars as $key=>$value) {
                    if (is_object($value)) {
                        $modelsToValidate[] = $value;
                    }
                }
            }
        } else {
            $modelsToValidate[] = $this;
        }
        foreach ($modelsToValidate as $model) {
            foreach (CCoreObjectsManager::getModelValidators($model)->getItems() as $validator) {
                if (!$validator->$event($model)) {
                    $error = $validator->getError();
                    $this->getValidationErrors()->add($this->getValidationErrors()->getCount(), $error);
                    $isValid = false;
                }
            }
        }
        return $isValid;
    }
    /**
     * Пееропределение связи свойств объекта с полями таблицы БД.
     * На случай, если имена полей в таблице давали нерусские люди
     *
     * @return array
     */
    protected function fieldsMapping() {
        return array(

        );
    }

    /**
     * Свойства полей, например, загрузка файлов
     *
     * @return array
     */
    protected function fieldsProperty() {
        return array(

        );
    }
    /**
     * Лист ошибок валидации модели
     *
     * @return CArrayList
     */
    public function getValidationErrors() {
        if (is_null($this->_errors)) {
            $this->_errors = new CArrayList();
        }
        return $this->_errors;
    }
    /**
     * Название атрибута модели
     *
     * @param $name
     * @return string
     */
    public function getAttributeLabel($name) {
        $labels = $this->attributeLabels();
        if (array_key_exists($name, $labels)) {
            return $labels[$name];
        } else {
            return $name;
        }
    }
}
