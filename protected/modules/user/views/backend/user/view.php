<?php
$this->breadcrumbs = array(
    UserModule::t('Users') => array('admin'),
    $model->username,
);
?>
<h1><?php echo UserModule::t('View User').' "'.$model->username.'"'; ?></h1>

<?php
echo $this->renderPartial('_menu', array(
    'list' => array(
        CHtml::link(UserModule::t('Create User'), array('create')),
        CHtml::link(UserModule::t('Update User'), array('update', 'id' => $model->id)),
        CHtml::linkButton(UserModule::t('Delete User'), array('submit' => array('delete', 'id'      => $model->id), 'confirm' => UserModule::t('Are you sure to delete this item?'))),
    ),
));


$attributes = array(
    'id',
    'username',
);

$profileFields = ProfileField::model()->forOwner()->sort()->findAll();
if ($profileFields)
{
    foreach ($profileFields as $field)
    {
        array_push($attributes, array(
            'label' => UserModule::t($field->title),
            'name'  => 'profile.'.$field->varname,
            'type'  => 'raw',
            'value' => (($field->widgetView($model->profile)) ? $field->widgetView($model->profile) : (($field->range) ? Profile::range($field->range, $model->profile->getAttribute($field->varname)) : $model->profile->getAttribute($field->varname))),
        ));
    }
}

array_push($attributes, 'email', 'activkey', array(
    'name'  => 'createtime',
    'value' => date("d.m.Y H:i:s", $model->createtime),
        ), array(
    'name'  => 'lastvisit',
    'value' => (($model->lastvisit) ? date("d.m.Y H:i:s", $model->lastvisit) : UserModule::t("Not visited")),
        ), array(
    'name'  => 'status',
    'value' => User::itemAlias("UserStatus", $model->status),
        ), array(
    'name'  => 'superuser',
    'value' => ($model->superuser) ? UserModule::t('Yes') : UserModule::t('No'),
        )
);

$this->widget('BootDetailView', array(
    'data'       => $model,
    'attributes' => $attributes,
));