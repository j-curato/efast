<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InspectionReport */
/* @var $form yii\widgets\ActiveForm */

$rfi = [];
if (!empty($model->fk_request_for_inspection_item_id)) {

    $rfi_query = YIi::$app->db->createCommand("SELECT id,rfi_number FROM request_for_inspection WHERE id =:id")
        ->bindValue(':id', $model->fk_request_for_inspection_item_id)
        ->queryAll();
    $rfi = ArrayHelper::map($rfi_query, 'id', 'rfi_number');
}
?>

<div class="inspection-report-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_request_for_inspection_item_id')->widget(Select2::class, [
                'data' => $rfi,
                'options' => ['placeholder' => 'Search for a RFI ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=request-for-inspection/search-rfi',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>