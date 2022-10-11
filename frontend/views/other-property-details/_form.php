<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OtherPropertyDetails */
/* @var $form yii\widgets\ActiveForm */

$row_number = 1;
$uacs = [];
$useful_life_in_mnths = 0;
if (!empty($model->fk_chart_of_account_id)) {
    $query = Yii::$app->db->createCommand("SELECT chart_of_accounts.id , CONCAT (chart_of_accounts.uacs ,'-',chart_of_accounts.general_ledger) as general_ledger FROm chart_of_accounts WHERE chart_of_accounts.id = :id")->bindValue(':id', $model->fk_chart_of_account_id)->queryAll();
    $uacs = ArrayHelper::map($query, 'id', 'general_ledger');
    $useful_life_in_mnths = YIi::$app->db->createCommand("SELECT 
ppe_useful_life.life_to
FROM chart_of_accounts
LEFT JOIN ppe_useful_life ON chart_of_accounts.fk_ppe_useful_life_id = ppe_useful_life.id
 WHERE chart_of_accounts.id = :id")
        ->bindValue(':id', $model->fk_chart_of_account_id)
        ->queryScalar();
}
?>

<div class="other-property-details-form">
    <div class="container">

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-3">

                <?= $form->field($model, 'fk_property_id')->widget(Select2::class, [
                    // 'data' => $property,
                    'name' => 'property_number',
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
            <div class="col-sm-3">
                <?= $form->field($model, 'first_month_depreciation')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'autoclose' => true,
                        'minViewMode' => 'months'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">

                <?= $form->field($model, 'start_month_depreciation')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'autoclose' => true,
                        'minViewMode' => 'months'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'depreciation_schedule')->textInput() ?>

            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_chart_of_account_id')->widget(Select2::class, [
                    'data' => $uacs,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=other-property-details/search-chart-of-accounts',
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
            <div class="col-sm-3">
                <?= $form->field($model, 'salvage_value_prcnt')->textInput(['type' => 'number', 'min' => 1]) ?>
            </div>
        </div>



        <div class="cal-container">

            <table id="items_table" class="table">
                <tbody>


                    <?php

                    if (!empty($items)) {
                        foreach ($items as $item) {
                            echo " <tr>
                            <td>
                               <input type='hidden' class='item_id' value='{$item['id']}' name='items[{$row_number}][item_id]'>
                                <label for='book'>Book</label>
                                <br>
                                <select name='items[{$row_number}][book]' class=' book' style='width: 100%;'>
                                    <option value='{$item['book_id']}'>{$item['book_name']} </option>
                                </select>
                            </td>
                            <td>
                                <label for='amount'>Unit Cost</label>
                                <input type='text' class='amount form-control mask-amount' value='{$item['amount']}'>
                                <input type='hidden' name='items[{$row_number}][amount]' class='amount form-control main-amount' value='{$item['amount']}'>
                            </td>
                            <td>
    
                                <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </td>
                        </tr>";
                            $row_number++;
                        }
                    } else {
                    ?>
                        <tr>
                            <td>
                                <label for="book">Book</label>
                                <br>
                                <select name="items[1][book]" class=" book" style="width: 100%;">
                                    <option value="">Select Book </option>
                                    <?php
                                    // $books = Yii::$app->db->createCommand("SELECT * FROM books")->queryAll();
                                    // foreach ($books as $val) {
                                    //     echo "<option value='{$val['id']}'>{$val['name']} </option>";
                                    // }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <label for="amount">Unit Cost</label>
                                <input type="text" class="amount form-control mask-amount">
                                <input type="hidden" name="items[1][amount]" class="amount form-control main-amount">
                            </td>
                            <td>

                                <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


        <div class="row">
            <div class="col-sm-3 col-sm-offset-5">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <button class="btn btn-warning" id="calculate">Calculate</button>

                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <table id="computation_table" class="table">
        <thead>
            <th>Book</th>
            <th>Acquisition Cost</th>
            <th>Salvage Value
                <br>
                (at least 5% of Cost, rounded to nearest ones)
            </th>
            <th>Depreciable Amount</th>
            <th>1st month of Depn.</th>
            <th>2nd to the last month</th>
            <th>No. of months <br>(from 1st month to 2nd to the last month)</th>
            <th>Monthly Depreciation
                <br>
                (from 1st month to 2nd to the last month, rounded to the nearest ones)
            </th>
            <th>
                Total Depreciation
                <br>
                (from 1st month to 2nd to the last month)
            </th>
            <th>
                Last Month
            </th>
            <th>
                Monthly Depreciation
                <br>
                (Last Month)
            </th>


        </thead>
        <tbody>


        </tbody>
    </table>


</div>
<style>
    th,
    td {
        text-align: center;
    }

    .cal-container {
        width: 60%;
        margin-left: auto;
        margin-right: auto;
    }

    .book {
        width: 100%;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/moment.min.js");
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/views/other-property-details/otherPropertyDetailsJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);

?>

<script>
    let row_number = <?= $row_number ?>;
    let useful_life_in_mnths = <?= $useful_life_in_mnths * 12 ?>;
    $(document).ready(() => {
        getAllBooks()
        $('#otherpropertydetails-fk_chart_of_account_id').trigger('change')
    })
</script>