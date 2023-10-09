<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <label for="">Role</label>
        <?php
        $roles = Yii::$app->authManager->getRoles();
        echo Select2::widget([
            'name' => 'roles[]',
            'data' => ArrayHelper::map($roles, 'name', 'name'),
            'value' => $model->getRole(),
            'size' => Select2::SMALL,
            'options' => ['placeholder' => 'Select a Role', 'multiple' => true, 'style' => 'width: 300px;'],
            'pluginOptions' => [
                'allowClear' => true,
                // 'maximumSelectionLength' => 20
            ],
        ])
        ?>
    </div>

    <div class="form-group mb-3">
        <?= $form->field($model, 'status')->widget(Select2::class, [
            'name' => 'role',
            'data' => [
                '9' => 'Deactivate',
                '10' => 'Activate'
            ],
            'size' => Select2::SMALL,
            'options' => ['placeholder' => 'Select a Status', 'multiple' => false, 'style' => 'width: 300px;'],
            'pluginOptions' => [
                // 'maximumSelectionLength' => 20
            ],
        ])
        ?>
    </div>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>