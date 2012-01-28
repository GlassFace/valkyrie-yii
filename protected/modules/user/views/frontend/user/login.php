<?php
$this->breadcrumbs = array(
    UserModule::t("Login"),
);
$this->pageCaption = UserModule::t("Login");
$this->pageTitle = Yii::app()->name.' - '.$this->pageCaption;
$this->pageDescription = "You've been here before, haven't you?";

if (Yii::app()->user->hasFlash('success')):
    $this->widget('BootAlert', array(
        'template' => '<div class="alert-message block-message {key}"><p>{message}</p></div>',
    ));
endif;
?>

<p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>
<?php
$form      = $this->beginWidget('BootActiveForm');
echo UserModule::t('Fields with <span class="required">*</span> are required.');
echo CHtml::errorSummary($model);
echo $form->textFieldRow($model, 'username');
echo $form->passwordFieldRow($model, 'password');
?>
<p class="hint">
<?php echo CHtml::link(UserModule::t("Register"), Yii::app()->getModule('user')->registrationUrl); ?> | <?php echo CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl); ?>
</p>
    <?php echo $form->checkBoxRow($model, 'rememberMe'); ?>
<div class="actions">
<?php echo CHtml::submitButton(UserModule::t("Login"), array('class' => 'btn primary')); ?>
</div>
<?php $this->endWidget(); ?>