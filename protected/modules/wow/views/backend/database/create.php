<?php
$this->breadcrumbs = array(
    WowModule::t('Databases') => array('admin'),
    Yii::t('app', 'Add'),
);
?>

<h1>Realmlist Info</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>
