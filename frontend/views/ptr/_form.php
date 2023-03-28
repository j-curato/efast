<?php

use app\components\helpers\MyHelper;
use app\models\Agency;
use app\models\Location;
use app\models\Office;
use app\models\Par;
use app\models\Property;
use app\models\TransferType;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */
/* @var $form yii\widgets\ActiveForm */

$ppe_status = [
    '0' => 'Serviceable',
    '1' => 'UnServiceable'
];
$property = [];
$rcv_by = [];
$actual_user = [];
$isd_by = [];
$apv_by = [];

$property_custodians = ArrayHelper::map(MyHelper::getPropertyCustodians(), 'employee_id', 'employee_name');
if (!empty($model->fk_property_id)) {
    $property = ArrayHelper::map(Property::find()->where('id = :id', ['id' => $model->fk_property_id])->asArray()->all(), 'id', 'property_number');
}
if (!empty($model->fk_received_by)) {
    $rcv_by = ArrayHelper::map(MyHelper::getEmployee($model->fk_received_by, 'all'), 'employee_id', 'employee_name');
}
if (!empty($model->fk_actual_user)) {
    $actual_user = ArrayHelper::map(MyHelper::getEmployee($model->fk_actual_user, 'all'), 'employee_id', 'employee_name');
}
if (!empty($model->fk_issued_by)) {
    $isd_by = ArrayHelper::map(MyHelper::getEmployee($model->fk_issued_by, 'all'), 'employee_id', 'employee_name');
}
if (!empty($model->fk_approved_by)) {
    $apv_by = ArrayHelper::map(MyHelper::getEmployee($model->fk_approved_by, 'all'), 'employee_id', 'employee_name');
}
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
                'data' => $property,
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
        <?php
        $x = 6;

        if (Yii::$app->user->can('super-user')) {
            echo '<div class="col-sm-3">';
            echo $form->field($model, 'fk_office_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Office'
                ]
            ]);
            echo "</div>";
            $x = 3;
        }
        ?>

        <div class="col-sm-<?= $x; ?>">
            <?= $form->field($model, 'is_unserviceable')->widget(Select2::class, [
                'data' => $ppe_status,
                'pluginOptions' => [
                    'placeholder' => 'Select Serviceable/Unserviceable'
                ]
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_location_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Location::find()->where('id = :id', ['id' => $model->fk_location_id])->all(), 'id', 'location'),
                'options' => ['placeholder' => 'Search Location ...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Yii::$app->request->baseUrl . '?r=location/search-location',
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page||1}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(property_number) { return property_number.text; }'),
                    'templateSelection' => new JsExpression('function (property_number) { return property_number.text; }'),
                ],

            ]) ?>
        </div>
    </div>


    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_issued_by')->widget(Select2::class, [
                'data' => $property_custodians,
                'options' => ['placeholder' => 'Search Employee ...'],
                'pluginOptions' => [],

            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
                'data' => $apv_by,
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
    </div>
    <div class="row">



        <div class="col-sm-6">
            <?= $form->field($model, 'fk_received_by')->widget(Select2::class, [
                'data' => $rcv_by,
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
                'data' => $actual_user,
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
    </div>



    <table id="property_details" class="table">

        <tr>
            <th>Current User:</th>
            <td id="from_officer"></td>
        </tr>
        <tr>
            <th>Date Acquired:</th>
            <td id="date_acquired"></td>
            <th>Amount</th>
            <td id="acq_amt"></td>
        </tr>
        <tr>
            <th>Description:</th>
            <td colspan="3" id="des">

            </td>

        </tr>
        <tr>
            <th>Serial Number:</th>
            <td id="srl_no">
            </td>
            <th>
                Unit of Measure:
            </th>
            <td id="unt_msr"></td>
        </tr>
    </table>




    <div class="row">
        <div class="col-sm-3 col-sm-offset-5">

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:11rem']) ?>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>
<script>
    $(document).ready(() => {

        $('#ptr-fk_property_id').change(() => {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=ptr/get-property-details',
                data: {
                    id: $('#ptr-fk_property_id').val(),

                },
                success: (data) => {
                    const res = JSON.parse(data)
                    $('#date_acquired').text(res.acquisition_date)
                    $('#acq_amt').text(thousands_separators(res.acquisition_amount))
                    $('#des').text(res.article + ' ' + res.description)
                    $('#srl_no').text(res.serial_number)
                    $('#unt_msr').text(res.unit_of_measure)
                    $('#from_officer').text(res.from_officer)
                }
            })
        })
    })
</script>