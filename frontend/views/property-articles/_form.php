<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PropertyArticles */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-articles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'article_name')->textInput(['maxlength' => true]) ?>


        <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>