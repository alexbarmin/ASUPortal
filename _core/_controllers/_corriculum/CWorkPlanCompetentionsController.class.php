<?php
class CWorkPlanCompetentionsController extends CBaseController{
    protected $_isComponent = true;

    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление компетенциями");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_COMPETENTIONS." as t")
            ->order("t.id asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." AND type=".CRequest::getInt("type"));
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanCompetention($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Добавить компетенцию",
            "link" => "workplancompetentions.php?action=add&id=".CRequest::getInt("plan_id")."&type=".CRequest::getInt("type"),
            "icon" => "actions/list-add.png"
        ));
        /**
         * Отображение представления
         */
        if (CRequest::getInt("type") == 0) {
        	$this->renderView("_corriculum/_workplan/competentions/index.tpl");
        } else {
        	$this->renderView("_corriculum/_workplan/competentions/indexInOut.tpl");
        }
    }
    public function actionAdd() {
        $object = new CWorkPlanCompetention();
        $object->plan_id = CRequest::getArray("id");
        $object->type = CRequest::getInt("type");
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        if (CRequest::getInt("type") == 0) {
        	$this->renderView("_corriculum/_workplan/competentions/add.tpl");
        } else {
        	$this->renderView("_corriculum/_workplan/competentions/addInOut.tpl");
        }
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplancompetentions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        if ($object->type == 0) {
        	$this->renderView("_corriculum/_workplan/competentions/edit.tpl");
        } else {
        	$this->renderView("_corriculum/_workplan/competentions/editInOut.tpl");
        }
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanCompetention(CRequest::getInt("id"));
        $plan = $object->plan_id;
        $type = $object->type;
        $object->remove();
        $this->redirect("workplancompetentions.php?action=index&plan_id=".$plan."&type=".$type);
    }
    public function actionSave() {
        $object = new CWorkPlanCompetention();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplancompetentions.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplancompetentions.php?action=index&plan_id=".$object->plan_id."&type=".$object->type);
            }
            return true;
        }
        $this->setData("object", $object);
        if ($object->type == 0) {
        	$this->renderView("_corriculum/_workplan/competentions/edit.tpl");
        } else {
        	$this->renderView("_corriculum/_workplan/competentions/editInOut.tpl");
        }
    }
}