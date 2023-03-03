<?php

use app\models\Agency;
use app\models\Par;
use app\models\TransferType;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ptr-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'readonly' => true,
                'options' => ['style' => 'background-color:white'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_to_agency_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Agency::find()->asArray()->all(), 'id', 'name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Agency'
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_property_id')->widget(Select2::class, [
                'name' => 'property_number',
                'options' => ['placeholder' => 'Search Property Number ...'],

                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=property/search-property',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(property_number) { return property_number.text; }'),
                    'templateSelection' => new JsExpression('function (property_number) { return property_number.text; }'),
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_transfer_type_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(TransferType::find()->asArray()->all(), 'id', 'type'),
                'pluginOptions' => [
                    'placeholder' => 'Select Transfer Type'
                ]
            ]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_issued_by')->widget(Select2::class, [
                'name' => 'fund_source',
                'options' => ['placeholder' => 'Search Employee ...'],
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
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-sm-6"> <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
                                    'name' => 'fund_source',
                                    'options' => ['placeholder' => 'Search Employee ...'],
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
                                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                            'cache' => true
                                        ],
                                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                                    ],

                                ]) ?></div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_received_by')->widget(Select2::class, [
                'name' => 'fund_source',
                'options' => ['placeholder' => 'Search Employee ...'],
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
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?></div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_actual_user')->widget(Select2::class, [
                'name' => 'fund_source',
                'options' => ['placeholder' => 'Search Employee ...'],
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
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?></div>
    </div>







    <div class="row">
        <div class="col-sm-3 col-sm-offset-5">

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:11rem']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>