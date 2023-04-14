<?php

use app\components\helpers\MyHelper;
use app\models\Employee;
use app\models\Location;
use app\models\Office;
use app\models\Property;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Par */
/* @var $form yii\widgets\ActiveForm */


$property_custodians = ArrayHelper::map(MyHelper::getPropertyCustodians(), 'employee_id', 'employee_name');
?>

<div class="par-form">


    <?php $form = ActiveForm::begin(); ?>

    <?php
    $rcv_by = '';
    $property = '';
    $agency =  '';
    $agency_id = '';
    $actual_user = [];
    $ppe_status = [
        '0' => 'Serviceable',
        '1' => 'UnServiceable'
    ];

    if (!empty($model->fk_property_id)) {
        $property = ArrayHelper::map(Property::find()->where('id = :id', ['id' => $model->fk_property_id])->asArray()->all(), 'id', 'property_number');
    }
    if (!empty($model->fk_received_by)) {
        $rcv_by = ArrayHelper::map(MyHelper::getEmployee($model->fk_received_by, 'all'), 'employee_id', 'employee_name');
    }
    if (!empty($model->fk_actual_user)) {
        $actual_user = ArrayHelper::map(MyHelper::getEmployee($model->fk_actual_user, 'all'), 'employee_id', 'employee_name');
    }
    ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'name' => 'date',
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>

        <?php

        if (YIi::$app->user->can('super-user')) {
            echo '<div class="col-sm-6">';
            echo  $form->field($model, 'fk_office_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Office'
                ]
            ]);
            echo '</div>';
        } ?>


        <div class="col-sm-6">
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
            <?= $form->field($model, 'fk_issued_by_id')->widget(Select2::class, [
                'data' => $property_custodians,
                'options' => ['placeholder' => 'Select Property Custodian'],

            ]) ?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model, 'fk_received_by')->widget(Select2::class, [
                'data' => $rcv_by,

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
                        'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'fk_actual_user')->widget(Select2::class, [
                'data' => $actual_user,
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

    <table id="property_details">
        <tbody>
            <tr>
                <th>
                    <span>Property No.</span>
                </th>
                <td>
                    <span id="property_number"></span>
                </td>
                <th>
                    <span>Date Aquired</span>
                </th>
                <td>
                    <span id="date"></span>
                </td>
            <tr>
                <th>
                    <span>Serial Number</span>
                </th>
                <td>
                    <span id="serial_number"></span>
                </td>
                <th>
                    <span>Amount</span>
                </th>
                <td>
                    <span id="amount"></span>
                </td>
            </tr>
            <tr>
                <th>
                    <span>Article</span>
                </th>
                <td>
                    <span id="article"></span>
                </td>
                <th rowspan="2">
                    <span>Description</span>
                </th>
                <td rowspan="2">
                    <span id="description"></span>
                </td>

            </tr>
            <tr>
                <th>
                    <span>Quantity</span>
                </th>
                <td>
                    <span id="quantity">1</span>
                </td>


            </tr>
            <tr>

            </tr>
            <tr>

                <th>
                    <span>Property Custodian</span>
                </th>
                <td>
                    <span id="disbursing_officer"></span>
                </td>
                <th>
                    <span>Unit</span>
                </th>
                <td>
                    <span id="unit"></span>

                </td>
            </tr>

        </tbody>
    </table>





    <div class="row">

        <div class="col-sm-5"></div>
        <div class="col-sm-2" style="padding: 2rem;">
            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>

        </div>
        <div class="col-sm-5"></div>
    </div>

    <?php ActiveForm::end(); ?>
</div>


<style>
    /* .par-form {
        background-color: white;
        padding: 20px;
        height: 100%;
        width: 100%;
    } */


    table,
    th,
    td {
        border: 1px solid black;
        padding: 10px;

    }

    #property_details {
        display: none;
        width: 100%;
    }

    table {
        width: 100%;
    }
</style>
<?php
$this->registerJsfile('@web/frontend/web/js/globalFunctions.js', ['depeneds' => [JqueryAsset::class]]);
?>
<script>

</script>
<?php
$js = <<<JS
    var studentSelect = $('#par-fk_property_id');
    $(document).ready(()=>{
        if (studentSelect.val() != '') {

            studentSelect.trigger('change')
        }

    })
 

    studentSelect.change(()=>{
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=property/get-property',
            data:{id:$('#par-fk_property_id').val()},
            success:function(data){
                var res = JSON.parse(data)
                console.log(res)
                $('#property_number').text(res.property_number)
                $('#book').text(res.book)
                $('#unit').text(res.unit_of_measure)
                $('#description').text(res.description)
                $('#date').text(res.date)
                $('#amount').text(thousands_separators(res.acquisition_amount))
                $('#serial_number').text(res.serial_number)
                $('#article').text(res.article)
                $('#disbursing_officer').text(res.disbursing_officer.toUpperCase())
                $('#iar_number').text(res.iar_number)
                $("#property_details").show()
            }
        })
    })
JS;
$this->registerJs($js);
?>