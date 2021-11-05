<?php

use app\models\Employee;
use app\models\Property;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Par */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="par-form">

    <div class="panel panel-default">

        <?php $form = ActiveForm::begin(); ?>

        <?php
        $rcv_by = '';
        $property = '';
        $agency =  '';
        $agency_id = '';
        if (!empty($model)) {
            $rcv_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
                ->bindValue(':id', $model->employee_id)
                ->queryAll();

            $rcv_by = ArrayHelper::map($rcv_by_query, 'employee_id', 'employee_name');
            $property = ArrayHelper::map(Property::find()->where(['property_number' => $model->property_number]), 'property_number', 'property_number');
            $q = Yii::$app->db->createCommand("SELECT * FROM agency")->queryAll();
            $agency = ArrayHelper::map($q, 'id', 'name');
            $agency_id = $model->agency_id;
        }
        ?>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'name' => 'date',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'agency_id')->widget(Select2::class, [
                    'data' => $agency,
                    'value' => [$agency_id],
                    'pluginOptions' => [
                        'placeholder' => 'Select Agency'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'property_number')->widget(Select2::class, [
                    'data' => $property,
                    'name' => 'property_number',
                    'id' => 'property_number',
                    'options' => [
                        'placeholder' => 'Search for a Fund Source ...',

                    ],

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
                        <span>model</span>
                    </th>
                    <td>
                        <span id="model"></span>
                    </td>
                    <th>
                        <span>Book</span>
                    </th>
                    <td>
                        <span id="book"></span>

                    </td>

                </tr>
                <tr>
                    <th>
                        <span>Quantity</span>
                    </th>
                    <td>
                        <span id="quantity"></span>
                    </td>
                    <th>
                        <span>Unit</span>
                    </th>
                    <td>
                        <span id="unit"></span>

                    </td>

                </tr>
                <tr>
                    <th>
                        <span>Description</span>
                    </th>
                    <td>
                        <span id="description"></span>
                    </td>
                    <th>
                        <span>Disbursing Officer</span>
                    </th>
                    <td>
                        <span id="disbursing_officer"></span>
                    </td>
                </tr>
                <tr>
                    <th>IAR Number</th>
                    <td id="iar_number"></td>
                </tr>
            </tbody>
        </table>





        <?= $form->field($model, 'employee_id')->widget(Select2::class, [
            'data' => $rcv_by,
            'name' => 'fund_source',
            'options' => ['placeholder' => 'Search for a Fund Source ...'],
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

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>


</div>
<style>
    /* .par-form {
        background-color: white;
        padding: 20px;
        height: 100%;
        width: 100%;
    } */
    .panel {
        padding: 20px;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 10px;

    }

    #property_details {
        display: none;
    }

    table {
        width: 100%;
    }
</style>
<script>

</script>
<?php
$js = <<<JS
    var studentSelect = $('#par-property_number');
    $(document).ready(()=>{
        if (studentSelect.val() != '') {

            studentSelect.trigger('change')
        }

    })
    studentSelect.change(()=>{
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=property/get-property',
            data:{id:$('#par-property_number').val()},
            success:function(data){
                var res = JSON.parse(data)
                console.log(res)
                $('#property_number').text(res.property_number)
                $('#quantity').text(res.quantity)
                $('#book').text(res.book)
                $('#unit').text(res.unit_of_measure)
                $('#description').text(res.article)
                $('#date').text(res.date)
                $('#amount').text(res.acquisition_amount)
                $('#serial_number').text(res.serial_number)
                $('#model').text(res.model)
                $('#disbursing_officer').text(res.disbursing_officer.toUpperCase())
                $('#iar_number').text(res.iar_number)
                $("#property_details").show()
            }
        })
    })
JS;
$this->registerJs($js);
?>