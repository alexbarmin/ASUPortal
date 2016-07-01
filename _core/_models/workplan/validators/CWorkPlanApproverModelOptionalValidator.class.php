<?php
/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 11.12.15
 * Time: 23:23
 */

class CWorkPlanApproverModelOptionalValidator extends IModelValidatorOptional {
	private $model;
	
	function getError() {
		return implode($this->validate(), "; <br>");
	}
	
	function onRead(CModel $model) {
		$this->model = $model;
		return count($this->validate()) == 0;
	}
	
	function validate() {
		// тут валидация, она возвращает массив ошибок
		$errors = array();
		$discipline = CCorriculumsManager::getDiscipline($this->model->corriculum_discipline_id);
		$terms = array();
		$terms[] = "term.name";
		$termIds = array();
		$errorTerm = false;
		$sectionsDisciplines = array();
		if (!empty($discipline->sections->getItems())) {
			foreach ($discipline->sections->getItems() as $section) {
				$sectionsDisciplines[] = $section->id;
			}
		}
		foreach ($this->model->terms->getItems() as $term) {
			if (!in_array($term->number, $sectionsDisciplines)) {
				$errorTerm = true;
			}
			if (is_null($term->corriculum_discipline_section)) {
				$errorTerm = true;
			}
			$termIds[] = $term->getId();
			$terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
		}
		if ($errorTerm) {
			$errors[] = "<b><font color='#FF0000'>Обновите названия семестров из дисциплины!</font></b>";
		}
		if (count($termIds) > 0) {
			$terms[] = "sum(if(l.term_id in (".join(", ", $termIds)."), l.value, 0)) as t_sum";
		}
		$query = new CQuery();
		$query->select(join(", ", $terms))
			->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
			->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
			->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
			->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
			->condition("category.plan_id = ".$this->model->getId()." and l._deleted = 0 and category._deleted = 0");
		$objects = $query->execute();
		$result = 0;
		foreach ($objects->getItems() as $key=>$value) {
			$result += $value["t_sum"];
		}
		if (!is_null($this->model->finalControls)) {
			foreach ($this->model->finalControls->getItems() as $control) {
				$item = $control->controlType;
			}
		}
		if (isset($item) && $item == "Зачет") {
			$result += 9;
		} elseif(isset($item)) {
			$result += 36;
		}
		$totalHours = $result;
		$totalCredits = round($result/36, 2);
		if(intval($totalCredits) != $totalCredits) {
			$errors[] = "<b>Число зачётных единиц дисциплины (".$totalCredits.") должно быть целым (cумма часов: ".$totalHours.")</b>";
		}
		foreach ($this->model->terms as $term) {
			foreach ($discipline->sections->getItems() as $sect) {
				if ($term->number == $sect->id) {
					$sumAuditor = 0;
					$sumHours = 0;
					$sumExamUnit = 0;
					$sumKSR = 0;
					$sumCourseWork = 0;
					$sumCourseProject = 0;
					$sumLabWork = 0;
					$sumLecture = 0;
					$sumTotal = 0;
					$sumPractice = 0;
					$sumSelfWork = 0;
					$sumRGR = 0;
					$sumExamen = 0;
					$sumCredit = 0;
					$sumCreditWithMark = 0;
					foreach ($this->model->categories as $category) {
						foreach ($category->sections as $section) {
							foreach ($section->loadsDisplay as $load) {
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "auditor") {
									$sumAuditor += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "hours") {
									$sumHours += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "exam_unit") {
									$sumExamUnit += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "ksr") {
									$sumKSR += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "course_work") {
									$sumCourseWork += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "course_project") {
									$sumCourseProject += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "labwork") {
									$sumLabWork += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "lecture") {
									$sumLecture += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "total") {
									$sumTotal += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "practice") {
									$sumPractice += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "self_work") {
									$sumSelfWork += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "rgr") {
									$sumRGR += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "examen") {
									$sumExamen += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "credit") {
									$sumCredit += $load->value;
								}
								if ($load->term->number == $term->number and $load->loadType->getAlias() == "creditWithMark") {
									$sumCreditWithMark += $load->value;
								}
							}
						}
					}
					$auditorZan = $sumLecture+$sumPractice+$sumLabWork;
					$teorObuch = $auditorZan+$sumSelfWork;
					foreach ($sect->labors->getItems() as $labor) {
						if ($term->number == $sect->id and $labor->type->getAlias() == "auditor") {
							if ($labor->value != $auditorZan) {
								$errors[] = "<b>Число часов аудиторных занятий за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов (".$auditorZan.") из нагрузки: 
											лекции (".$sumLecture."), практики (".$sumPractice."), лабораторные работы (".$sumLabWork.")</b>";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "hours") {
							if ($labor->value != $teorObuch) {
								$errors[] = "<b>Всего теоретическое обучение за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов (".$teorObuch.") из нагрузки: 
										аудиторные занятия (".$auditorZan."), самостоятельная работа (".$sumSelfWork.")</b>";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "exam_unit") {
							if ($labor->value != $sumExamUnit) {
								$errors[] = "Число часов зачётных единиц за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumExamUnit.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "ksr") {
							if ($labor->value != $sumKSR) {
								$errors[] = "Число часов КСР за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumKSR.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "course_work") {
							if ($labor->value != $sumCourseWork) {
								$errors[] = "Число часов курсовых работ за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCourseWork.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "course_project") {
							if ($labor->value != $sumCourseProject) {
								$errors[] = "Число часов курсовых проектов за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCourseProject.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "labwork") {
							if ($labor->value != $sumLabWork) {
								$errors[] = "Число часов лабораторных работ за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumLabWork.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "lecture") {
							if ($labor->value != $sumLecture) {
								$errors[] = "Число часов лекций за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumLecture.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "total") {
							if ($labor->value != $sumTotal) {
								$errors[] = "Трудоёмкость общая за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumTotal.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "practice") {
							if ($labor->value != $sumPractice) {
								$errors[] = "Число часов практик за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumPractice.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "self_work") {
							if ($labor->value != $sumSelfWork) {
								$errors[] = "Число часов самостоятельных работ за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumSelfWork.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "rgr") {
							if ($labor->value != $sumRGR) {
								$errors[] = "Число часов РГР за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumRGR.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "examen") {
							if ($labor->value != $sumExamen) {
								$errors[] = "Число часов на экзамен за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumExamen.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "credit") {
							if ($labor->value != $sumCredit) {
								$errors[] = "Число часов на зачёт за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCredit.")";
							}
						}
						if ($term->number == $sect->id and $labor->type->getAlias() == "creditWithMark") {
							if ($labor->value != $sumCreditWithMark) {
								$errors[] = "Число часов на зачёт с оценкой за ".$sect->title.
									" семестр из дисциплины (".$labor->value.") не совпадает с суммой часов из нагрузки (".$sumCreditWithMark.")";
							}
						}
					}
				}
			}
		}
		return $errors;
	}

}