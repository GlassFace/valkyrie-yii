<?php

class CharacterController extends Controller
{

    public function init()
    {
        parent::init();

        $this->layout = '//layouts/column2';

        if(isset($_GET['realm']))
            Database::$realm = (string) $_GET['realm'];
        if(isset($_GET['name']))
        {
            $this->_model = $this->loadModel($_GET['name']);
            $this->_model->loadAdditionalData();

            if($this->_model->getPvpTitle($this->_model->honorRank))
            {
                $this->pageCaption = $this->_model->getPvpTitle($this->_model->honorRank) . ' ';
            }
            
            $this->pageCaption .= $this->_model->name;
            $this->pageDescription = "{$this->_model->race_text} - {$this->_model->class_text} ({$this->_model->talents['name']}) {$this->_model['level']} lvl";
              
            $this->menu = array(
                array(
                    'label' => 'Сводка',
                    'url'   => array('/wow/character/view', 'realm' => Database::$realm, 'name'  => $this->_model->name)
                ),
                array(
                    'label' => 'Таланты',
                    'url'   => array('/wow/character/talents', 'realm' => Database::$realm, 'name'  => $this->_model->name)
                ),
                array(
                    'label' => 'Репутация',
                    'url'   => array('/wow/character/reputation', 'realm' => Database::$realm, 'name'  => $this->_model->name)
                ),
                array(
                    'label' => 'PvP',
                    'url'   => array('/wow/character/pvp', 'realm' => Database::$realm, 'name'  => $this->_model->name)
                ),
                array(
                    'label' => 'Лента новостей',
                    'url'   => array('/wow/character/feed', 'realm' => Database::$realm, 'name'  => $this->_model->name)
                    ));
        }
    }

    public function actionView()
    {
        //$this->registerFiles();

        $this->render('view', array(
            'model' => $this->_model,
        ));
    }

    public function actionIndex()
    {
        $this->layout = '//layouts/column1';

        Database::$realm = Database::model()->find('type = "characters"')->title;
        $model = new Character('search');
        $model->unsetAttributes();

        if(isset($_GET['Character']))
            $model->attributes = $_GET['Character'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /* public function actionTalents($realm, $name)
      {
      Database::$realm = (string)$realm;
      $model = $this->loadModel((string)$name);
      $model->loadAdditionalData();

      $this->registerFiles();
      $this->_cs->registerCssFile('/css/wow/character/talent.css');
      $this->_cs->registerCssFile('/css/wow/tool/talent-calculator.css');
      $this->_cs->registerScriptFile('/js/wow/tool/talent-calculator.js', CClientScript::POS_END);

      $this->render('talents',array(
      'model'=>$model,
      ));
      } */

    public function actionTooltip($realm, $name)
    {
        $this->renderPartial('tooltip', array(
            'model' => $this->_model,
        ));
    }

    public function actionReputation($realm, $name)
    {
        $this->render('reputation', array(
            'model' => $this->_model,
        ));
    }

    public function actionPvp($realm, $name)
    {
        $this->render('pvp', array(
            'model' => $this->_model,
        ));
    }

    public function actionFeed($realm, $name)
    {
        $this->render('feed', array(
            'model' => $this->_model,
        ));
    }

    public function loadModel($name)
    {
        $this->_model = Character::model()->find('name = ?', array($name));
        if($this->_model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $this->_model;
    }

}
