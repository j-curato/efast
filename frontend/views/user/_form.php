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
            'name' => 'roles',
            'data' => ArrayHelper::map($roles, 'name', 'name'),
            'value' => $model->getRoles(),
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