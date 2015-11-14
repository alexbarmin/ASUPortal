<?php

/**
 * Created by PhpStorm.
 * User: abarmin
 * Date: 14.11.15
 * Time: 12:05
 */
class CStatefullFormSmartyPlugin {
    /**
     * Регистрируем плагины в переданном экземпляре smarty
     *
     * @param Smarty $smarty
     */
    public static function registerPlugins(Smarty $smarty) {
        $smarty->registerPlugin('block', 'sf_changeState', array('CStatefullFormSmartyPlugin', 'StatefullForm_ChangeState'));
        $smarty->registerPlugin('block', 'sf_showIfVisible', array('CStatefullFormSmartyPlugin', 'StatefullForm_ShowIfVisible'));
        $smarty->registerPlugin('function', 'sf_toggleVisible', array('CStatefullFormSmartyPlugin', 'StatefullForm_ToggleVisible'));
    }

    public static function StatefullForm_ToggleVisible($params = array()) {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        $content = WEB_ROOT.'images'.CORE_DS.ICON_THEME.CORE_DS.'22x22'.CORE_DS.'actions'.CORE_DS;
        if ($bean->getElement($element)->getState() == 'show') {
            $content .= 'list-remove.png';
            $params['state'] = 'hide';
        } else {
            $content .= 'list-add.png';
            $params['state'] = 'show';
        }

        $content = '<img src="'.$content.'" />';

        echo self::StatefullForm_ChangeState($params, $content);
    }

    public static function StatefullForm_ShowIfVisible($params = array(), $content = '') {
        self::checkParams($params);

        $bean = self::getStatefullFormBean($params);
        $element = self::getElementId($params);

        if ($bean->getElement($element)->getState() == 'show') {
            return $content;
        }
        return '';
    }

    private static function getElementId($params = array()) {
        if (!array_key_exists('element', $params)) {
            throw new Exception('Не задан параметр element, к которому отправляется событие');
        }
        return $params['element'];
    }

    /**
     * Проверить, что все необходимые параметры заданы
     *
     * @param $params
     * @throws Exception
     */
    private static function checkParams($params) {
        /**
         * Проверим, все ли параметры заданы
         */
        if (!array_key_exists('bean', $params)) {
            throw new Exception('Не задан параметр bean');
        }
        if (!is_a($params['bean'], 'CStatefullFormBean')) {
            throw new Exception('Bean не экземпляр класса CStatefullFormBean');
        }
        if (!array_key_exists('element', $params)) {
            throw new Exception('Не задан параметр element, к которому отправляется событие');
        }
    }

    /**
     * Получить используемый в форме бин
     *
     * @param array $params
     * @return CStatefullFormBean
     * @throws Exception
     */
    private static function getStatefullFormBean($params = array()) {
        if (!array_key_exists('bean', $params)) {
            throw new Exception('Не задан параметр bean');
        }
        if (!is_a($params['bean'], 'CStatefullFormBean')) {
            throw new Exception('Bean не экземпляр класса CStatefullFormBean');
        }
        //
        return $params['bean'];
    }

    /**
     * Создать ссылку из параметров
     *
     * @param array $params
     * @return string
     */
    private static function createReference($params = array()) {
        $reference = WEB_ROOT;
        if (array_key_exists('address', $params)) {
            $reference = $params['address'];
            unset($params['address']);
        }
        $pairs = array();
        foreach ($params as $key=>$value) {
            if (is_a($value, 'CModel')) {
                $pairs[] = 'id=' . $value->getId();
            } elseif (is_a($value, 'CStatefullBean')) {
                $pairs[] = $key . '=' . $value->getBeanId();
            } else {
                $pairs[] = $key.'='.$value;
            }
        }
        $reference .= '?'.implode("&", $pairs);
        return $reference;
    }

    public static function StatefullForm_ChangeState($params = array(), $content = '') {
        self::checkParams($params);

        $params['action'] = 'sendEvent';
        $params['event'] = 'changeState';

        $link = '<a href="'.self::createReference($params).'">'.$content.'</a>';

        return $link;
    }
}