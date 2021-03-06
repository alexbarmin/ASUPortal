<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 08.03.13
 * Time: 18:04
 * To change this template use File | Settings | File Templates.
 */
class CCorriculumLaborsController extends CBaseController {
	protected $_isComponent = true;
	
    public function __construct() {
        if (!CSession::isAuth()) {
            $this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Индивидуальные учебные планы");

        parent::__construct();
    }
    public function actionAdd() {
        $labor = new CCorriculumDisciplineLabor();
        $this->addActionsMenuItem(array(
        	"title" => "Назад",
        	"link" => "disciplineSections.php?action=index&discipline_id=".CRequest::getInt("discipline_id"),
        	"icon" => "actions/edit-undo.png"
        ));
        $labor->section_id = CRequest::getInt("id");
        $labor->discipline_id = CRequest::getInt("discipline_id");
        $this->setData("labor", $labor);
        $this->renderView("_corriculum/_labors/add.tpl");
    }
    public function actionEdit() {
        $labor = CCorriculumsManager::getLabor(CRequest::getInt("id"));
        $labor->discipline_id = CRequest::getInt("discipline_id");
        $this->addActionsMenuItem(array(
        	"title" => "Назад",
        	"link" => "disciplineSections.php?action=index&discipline_id=".$labor->discipline_id,
        	"icon" => "actions/edit-undo.png"
        ));
        $this->setData("labor", $labor);
        $this->renderView("_corriculum/_labors/edit.tpl");
    }
    public function actionSave() {
        $labor = new CCorriculumDisciplineLabor();
        $labor->setAttributes(CRequest::getArray($labor::getClassName()));
        if ($labor->validate()) {
            $labor->save();
            if ($this->continueEdit()) {
                $this->redirect("labors.php?action=edit&id=".$labor->getId()."&discipline_id=".$labor->discipline_id);
            } else {
            	if (is_null($labor->section)) {
            		$this->redirect("disciplineSections.php?action=index&discipline_id=".$labor->discipline_id);
            	} else {
            		$this->redirect("disciplineSections.php?action=index&discipline_id=".$labor->section->discipline_id);
            	}
                
            }
            return true;
        }
        $this->setData("labor", $labor);
        $this->renderView("_corriculum/_labors/add.tpl");
    }
    public function actionDel() {
        $labor = CCorriculumsManager::getLabor(CRequest::getInt("id"));
        if (is_null($labor->section)) {
            $id = $labor->discipline_id;
        } else {
            $id = $labor->section->discipline_id;
        }
        $labor->remove();
        $this->redirect("disciplineSections.php?action=index&discipline_id=".$id);
    }
}
