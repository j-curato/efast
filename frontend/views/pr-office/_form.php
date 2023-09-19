<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\PrOffice */
/* @var $form yii\widgets\ActiveForm */


$office = [
    'RO' => 'RO',
    'ADN' => 'ADN',
    'ADS' => 'ADS',
    'PDI' => 'PDI',
    'SDN' => 'SDN',
    'SDS' => 'SDS',
];
$division = [
    'CPD' => 'CPD',
    'FAD' => 'FAD',
    'IDD' => 'IDD',
    'OPD' => 'OPD',
    'ORD' => 'ORD',
    'SDD' => 'SDD',
    'MSSU' => 'MSSU',
];
$unit_head = [];
if (!empty($model->fk_unit_head)) {
    $unit_headQuery = Yii::$app->db->createCommand("SELECT employee_name,employee_id FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_unit_head)->queryAll();
    $unit_head = ArrayHelper::map($unit_headQuery, 'employee_id', 'employee_name');
}

?>

<div class="pr-office-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'office')->widget(Select2::class, [
        'data' => $office,
        'options' => ['placeholder' => 'Select Office'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>


    <?= $form->field($model, 'division')->widget(Select2::class, [
        'data' => $division,
        'options' => ['placeholder' => 'Select Division'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]) ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fk_unit_head')->widget(Select2::class, [
        'data' => $unit_head,
        'options' => ['placeholder' => 'Search for a Employee ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
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

    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>