<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sub-accounts1-form">

    <?php $form = ActiveForm::begin(); ?>

    <label class="control-label">Tag Multiple</label>
    <?=
    Select2::widget([
        'name' => 'color_2',
        'value' => ['red', 'green'], // initial value
        'data' => $data,
        'maintainOrder' => true,
        'options' => ['placeholder' => 'Select a color ...', 'multiple' => true],
        'pluginOptions' => [
            'tags' => true,
            'maximumInputLength' => 10
        ],

    ]);
    ?>
    <div class="row justify-content-center">

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>