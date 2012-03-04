<?php

class WowModule extends CWebModule
{

    public function init()
    {
        $this->setImport(array(
            'wow.models.*',
            'wow.components.*',
        ));

        //Yii::app()->db_world->active = true;

        Yii::app()->onModuleCreate(new CEvent($this));
    }

    public static function t($str = '', $params = array(), $dic = 'wow')
    {
        return Yii::t("WowModule.".$dic, $str, $params);
    }

}
