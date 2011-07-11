<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'account-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'username'); ?>
        <?php echo $form->textField($model,'username'); ?>
        <?php echo $form->error($model,'username'); ?>
    </div>
	
    <div class="row">
        <?php echo $form->labelEx($model,'password'); ?>
        <?php echo $form->passwordField($model,'password'); ?>
        <?php echo $form->error($model,'password'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'verifyPassword'); ?>
		<?php echo $form->passwordField($model,'verifyPassword'); ?>
		<?php echo $form->error($model,'verifyPassword'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'locale'); ?>
        <?php echo $form->dropDownList($model,'locale', array(
            '0' => 'English',
            '8' => 'Russian')); ?>
        <?php echo $form->error($model,'locale'); ?>
    </div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Create'); ?>
	</div>

<?php $this->endWidget(); ?>

</div>