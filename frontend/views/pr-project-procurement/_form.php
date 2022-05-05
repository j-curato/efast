<?php

use app\models\PrOffice;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectProcurement */
/* @var $form yii\widgets\ActiveForm */

$province = strtolower(Yii::$app->user->identity->province);
$division = strtolower(Yii::$app->user->identity->division);
if (

    $province === 'ro' &&
    $division === 'sdd' ||
    $division === 'cpd' ||
    $division === 'idd' ||
    $division === 'ord'


) {

    $office = Yii::$app->db->createCommand("SELECT id, CONCAT(office,'-',division,'-',unit) as office_name 
    FROM pr_office WHERE pr_office.division = :division ")
        ->bindValue(':division', $division)
        ->queryAll();
} else {

    $office = Yii::$app->db->createCommand("SELECT id, CONCAT(office,'-',division,'-',unit) as office_name FROM pr_office")
        ->queryAll();
}

$employee_id = '';
if (!empty($model->id)) {
    $employee_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->employee_id)
        ->queryAll();

    $employee_id = ArrayHelper::map($employee_query, 'employee_id', 'employee_name');
}

?>

<div class="pr-project-procurement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textarea(
        [
            'rows' => 3,
            'style' => 'max-width:100%'
        ]
    ) ?>

    <?= $form->field($model, 'pr_office_id')->widget(Select2::class, [
        'data' => ArrayHelper::map($office, 'id', 'office_name'),
        'pluginOptions' => [
            'placeholder' => 'Select Office'
        ]
    ]) ?>

    <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>

    <?= $form->field($model, 'employee_id')->widget(Select2::class, [
        'data' => $employee_id,
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

    <div class="form-group" style="text-align: center;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:20rem']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>