<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<?php $this->widget('application.extensions.mbmenu.MbMenu', array(
        'items'=>array(
            array('label'=>'Home', 'url'=>array('/admin'),
                'items'=>array(
                    array('label'=>'Menu Manangment', 'url'=>array('/admin/core/menu')),
                    array('label'=>'User Manangment', 'url'=>array('/admin/core/user')),
                )),
            array('label'=>'WoW Managment',
                'items'=>array(
                    array('label'=>'Realm Manangment', 'url'=>array('/admin/wow/realm/index')),
                    array('label'=>'Account Manangment', 'url'=>array('/admin/wow/account/index')),
                    array('label'=>'Characters Manangment', 'url'=>array('/admin/wow/character/index')),
                )),
            array('label'=>'View Site', 'url'=>array('/site')),
          ))); ?>

	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
    <div class="container">
        <div id="content">
	<?php echo $content; ?>
        </div>
    </div>
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by Hyperion.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?><br/>
        Отпахало за <?=sprintf('%0.5f',Yii::getLogger()->getExecutionTime())?> с. Сожрано памяти: <?=round(memory_get_peak_usage()/(1024*1024),2)."MB"?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>