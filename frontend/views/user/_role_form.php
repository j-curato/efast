<?php

use Yii;
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
    <div class="form-group mb-3">
        <label for="">Child Roles
            <?php
            $roles = Yii::$app->authManager->getRoles();
            echo Select2::widget([
                'name' => 'childrenRoles',
                'data' => ArrayHelper::map($roles, 'name', 'name'),
                'size' => Select2::SMALL,
                'options' => ['placeholder' => 'Select a state ...', 'multiple' => false, 'style' => 'width: 300px;'],
                'pluginOptions' => [
                    'allowClear' => true,
                    // 'maximumSelectionLength' => 20
                ],
            ])
            ?>
        </label>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>