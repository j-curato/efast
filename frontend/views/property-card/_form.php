<?php

use app\models\Par;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyCard */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-card-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'pc_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'balance')->textInput(['maxlength' => true]) ?>
    <?php
    $par = '';
    if (!empty($model)) {

        $par = ArrayHelper::map(Par::find()->where(['par_number' => $model->par_number]), 'par_number', 'par_number');
    }
    ?>
    <?= $form->field($model, 'par_number')->widget(Select2::class, [
        'data' => $par,
        'name' => 'par_number',
        'id' => 'par_number',
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
                'data' => new JsExpression('function(params) { return {q:params.term ,province: params.province}; }'),
                'cache' => true
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(par_number) { return par_number.text; }'),
            'templateSelection' => new JsExpression('function (par_number) { return par_number.text; }'),
        ],

    ]) ?>
    <table id="par_table">

        <tr>
            <th>Property Number</th>
            <td id="property_number"></td>
            <th>PAR Number</th>
            <td id="par_number"></td>

        </tr>
        <tr>
            <th>Quantity</th>
            <td id="quantity"></td>
            <th>Amount</th>
            <td id="amount"></td>
        </tr>
        <tr>
            <th>Article</th>
            <td id="article"></td>
            <th>PAR Date</th>
            <td id="par_date"></td>
        </tr>
        <tr>
            <th>Description</th>
            <td>
                <span>Model:</span>
                <span id="model"></span>
                <br>
                <span>Serial Number:</span>
                <span id="serial_number"></span>
                <br>
                <span>IAR Number:</span>
                <span id="iar_number"></span>
                <br>

            </td>
            <th>Recieved By</th>
            <td id="recieved_by"></td>
        </tr>
        <tr>
            <th>Book</th>
            <td id="book"></td>
            <th>Unit of Measure</th>
            <td id="unit_of_measure"></td>
        </tr>

        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
        margin: 20px;
    }

    table {

        width: auto;
    }

    #par_table {
        display: none;
    }

    .property-card-form {
        background-color: white;
        padding: 20px;
    }
</style>

<?php
$js = <<<JS
    var property_card = $('#propertycard-par_number')
    property_card.change(()=>{

        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=par/par-details',
            data:{
                par_number:property_card.val()
            },
            success:function(data){
                var res = JSON.parse(data)
                console.log(res)
                $('#par_number').text(res.par_number)
                $('#property_number').text(res.property_number)
                $('#article').text(res.article)
                $('#quantity').text(res.quantity)
                $('#par_date').text(res.par_date)
                $('#recieved_by').text(res.rcv_by_employee_name)
                $('#book').text(res.book_name)
                $('#unit_of_measure').text(res.unit_of_measure)
                $('#model').text(res.model)
                $('#serial_number').text(res.serial_number)
                $('#iar_number').text(res.iar_number)
                $('#amount').text(res.acquisition_amount)
                $('#par_table').show()

            }
        })
    })
    $(document).ready(()=>{
        if (property_card.val()!=''){
            property_card.trigger('change')
        }
    })
JS;
$this->registerJs($js);
?>