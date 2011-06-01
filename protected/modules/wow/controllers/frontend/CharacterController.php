<?php

class CharacterController extends Controller
{
    public $layout='//layouts/profile_wrapper';

    public function actionSimple($realm, $name)
    {
        Database::$realm = (string)$realm;
        $model = $this->loadModel((string)$name);
        $model->loadAdditionalData();
        
        $this->registerFiles();
        $this->_cs->registerCss(1, '#content .content-top { background: url("/images/wow/character/summary/backgrounds/race/'.$model->race.'.jpg") left top no-repeat; } .profile-wrapper { background-image: url("/images/wow/2d/profilemain/race/'.$model->race.'-'.$model->gender.'.jpg"); }');
        
        $this->render('summary',array(
            'model'=>$model,
        ));
    }
    
	public function actionAdvanced($realm, $name)
    {
        Database::$realm = (string)$realm;
        $model = $this->loadModel((string)$name);
        $model->loadAdditionalData();
        
        $this->registerFiles();
        $this->_cs->registerCss(1, '#content .content-top { background: url("/images/wow/character/summary/backgrounds/race/'.$model->race.'.jpg") left top no-repeat; } .profile-wrapper { background-image: url("/images/wow/2d/profilemain/race/'.$model->race.'-'.$model->gender.'.jpg"); }');
        
        $this->render('summary',array(
            'model'=>$model,
        ));
    }

    public function actionThreed($realm, $name)
    {
        Database::$realm = (string)$realm;
        $model = $this->loadModel((string)$name);
        $model->loadAdditionalData();
        $this->registerFiles();
        
        $this->render('summary',array(
            'model'=>$model,
        ));
    }

    public function actionTooltip($realm, $name)
    {
    	
        Database::$realm = (string)$realm;
        $model = $this->loadModel((string)$name);
        $model->loadAdditionalData();
        
        $this->renderPartial('tooltip',array(
            'model'=>$model,
        ));
    }
    
    public function loadModel($name)
    {
        $this->_model = Character::model()->find('name = ?', array($name));
        if($this->_model===null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $this->_model;
    }

	private function registerFiles()
	{
        $this->_cs->registerCssFile('/css/wow/profile.css');
        $this->_cs->registerCssFile('/css/wow/character/summary.css');
        $this->_cs->registerScriptFile('/js/wow/profile.js', CClientScript::POS_END);
        $this->_cs->registerScriptFile('/js/wow/character/summary.js', CClientScript::POS_END);
	}
}
