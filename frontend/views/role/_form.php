<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Role */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="container card ">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        <div class="form-group mb-3">
            <label for="">Child Roles
                <?php
                $roles = Yii::$app->authManager->getRoles();
                echo Select2::widget([
                    'name' => 'childrenRoles',
                    'data' => ArrayHelper::map($roles, 'name', 'name'),
                    'value' => ArrayHelper::map($model->getChildren(), 'name', 'name'),
                    'size' => Select2::SMALL,
                    'options' => ['placeholder' => 'Select a state ...', 'multiple' => true, 'style' => 'width: 300px;'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        // 'maximumSelectionLength' => 20
                    ],
                ])
                ?>
            </label>
        </div>

        <div class="form-group mb-3">
            <label for="">Permissions
                <?php
                $permissions = Yii::$app->authManager->getPermissions();
                echo Select2::widget([
                    'name' => 'permissions',
                    'data' => ArrayHelper::map($permissions, 'name', 'name'),
                    'value' => ArrayHelper::map($model->getPermissions(), 'name', 'name'),
                    'size' => Select2::SMALL,
                    'options' => ['placeholder' => 'Select a state ...', 'multiple' => true, 'style' => 'width: 300px;'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        // 'maximumSelectionLength' => 20
                    ],
                ])
                ?>
            </label>
        </div>

        <div class="row justify-content-center">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>


</div>
<style>
    .select2-container .select2-selection--multiple .select2-selection__rendered {
        /* display: inline-grid; */
        list-style: none;
        padding: 0;
    }

    :not(.form-floating)>.input-sm.select2-container--krajee-bs4 .select2-selection--multiple .select2-selection__choice,
    :not(.form-floating)>.input-group-sm .select2-container--krajee-bs4 .select2-selection--multiple .select2-selection__choice {
        font-size: 0.8rem;
        margin: 0.3rem 0 0.2rem 0.2rem;
        padding: 0.05rem 0.05rem 0.05rem 0.2rem;
        max-width: fit-content;
        float: left;
        height: inherit;
    }

    :not(.form-floating)>.input-sm.select2-container--krajee-bs4 .select2-selection--multiple,
    :not(.form-floating)>.input-group-sm .select2-container--krajee-bs4 .select2-selection--multiple {
        min-height: calc(1.875rem - 1px);
        display: inline-block;
        width: 100%;
    }
</style>