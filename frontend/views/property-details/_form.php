<?php

use app\models\Property;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyDetails */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="property-details-form">
    <?php
    $property = '';
    $model_id = '';
    if (!empty($model->id)) {
        $property = ArrayHelper::map(Property::find()->where(['property_number' => $model->property_number]), 'property_number', 'property_number');
        $model_id = [$model_id];
    }
    ?>
    <div class="row">
        <div class="col-sm-3">
            <?php echo Select2::widget([
                'data' => $property,
                'name' => 'property_number',
                'id' => 'property_number',
                'options' => [
                    'placeholder' => 'Search Property Number ...',
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
    <button class="add">Add</button>
    <table id='entry_table'>
        <thead>
            <th>First Month</th>
            <th>Last Month</th>
            <th>Salvage Value</th>
            <th>Estimated Useful Life</th>
            <th>Amount of Monthly Depreciation</th>
            <th>UACS</th>
        </thead>
        <tbody>

            <tr>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input type='text' class='form-control dep amount' name='liq_damages[]' onchange="f(this)">
                    <input type="text" class='hidden-amount' value='hehe'>

                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
            </tr>
        </tbody>
    </table>

</div>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<script>
    function f(q) {
        console.log(q.closest("input").value)

    }
    $(`.dep`).maskMoney({
        allowNegative: true
    });

    $('.add').click(() => {
        var row = `<tr>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
                <td>
                <input class="form-control dep" type="text">
                <input  type="text" class='hidden-amount'>
                </td>
                <td>
                    <input class="form-control" type="text">
                </td>
            </tr>`

        $('#entry_table tbody').append(row)
        $(`.dep`).maskMoney({
            allowNegative: true
        });
    })
</script>