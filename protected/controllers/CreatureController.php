<?php

class CreatureController extends Controller
{
    public function actionView($id)
    {
        $this->layout = '//layouts/wiki';
        $model = $this->loadModel($id);

        $baseUrl = Yii::app()->request->baseUrl;

        $this->cs->registerCssFile($baseUrl . '/css/wow/wiki/wiki.css');
        $this->cs->registerCssFile($baseUrl . '/css/wow/wiki/item.css');

        $this->cs->registerScriptFile($baseUrl . '/js/wow/wiki/wiki.js', CClientScript::POS_END);
        $this->cs->registerScriptFile($baseUrl . '/js/wow/wiki/item.js', CClientScript::POS_END);
        $this->cs->registerScriptFile($baseUrl . '/js/local-common/table.js', CClientScript::POS_END);
        $this->cs->registerScriptFile($baseUrl . '/js/local-common/cms.js', CClientScript::POS_END);
        $this->cs->registerScriptFile($baseUrl . '/js/local-common/filter.js', CClientScript::POS_END);
        $this->cs->registerScriptFile($baseUrl . '/js/local-common/utility/model-rotator.js', CClientScript::POS_END);

        $this->render('view', array('model' => $model));
    }

    public function actionIndex()
    {
        $model = new CreatureTemplate('search');
        
        $this->render('index', array('model' => $model));
    }
    
    public function actionTooltip($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('tooltip', array('model' => $model));
    }

	public function actionLoot($id)
	{
		$model = $this->loadModel($id);
		$dataProvider = $model->loot;

		$this->renderPartial('/item/_items', array('dataProvider' => $dataProvider));
	}

    public function loadModel($id)
    {
        $model = CreatureTemplate::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
}
