<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $model app\models\TravelOrder */
/* @var $form yii\widgets\ActiveForm */

$approved_by = [];
$recommending_approval = [];
$budget_officer = [];

if (!empty($model->fk_recommending_approval)) {

    $recommending_approval_query = YIi::$app->memem->updateEmployee($model->fk_recommending_approval);
    $recommending_approval  = ArrayHelper::map($recommending_approval_query, 'employee_id', 'employee_name');
}
if (!empty($model->fk_approved_by)) {
    $approved_by_query = Yii::$app->memem->updateEmployee($model->fk_approved_by);
    $approved_by  = ArrayHelper::map($approved_by_query, 'employee_id', 'employee_name');
}
if (!empty($model->fk_budget_officer)) {
    $budget_officer_query = YIi::$app->memem->updateEmployee($model->fk_budget_officer);
    $budget_officer  = ArrayHelper::map($budget_officer_query, 'employee_id', 'employee_name');
}




$row_number = 1;
// fa-flag on fa-circle
Yii::$app->db->createCommand();
?>
<div class="travel-order-form" id="main">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-2">

            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'type')->widget(Select2::class, [

                'data' => ['national' => 'National', 'regional' => 'Regional'],
                'pluginOptions' => [
                    'placeholder' => 'Select Type',
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_budget_officer')->widget(Select2::class, [
                'data' => $budget_officer,
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

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_recommending_approval')->widget(Select2::class, [
                'data' => $recommending_approval,
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
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
                'data' => $approved_by,
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

        </div>
    </div>
    <textarea class="purpose form-control hidden" rows="4" name="purpose"><?= $model->purpose ?></textarea>
    <textarea class="expected_output form-control hidden" rows="4" name="expected_output"><?= $model->expected_outputs ?></textarea>

    <?= $form->field($model, 'destination')->textarea(['rows' => 4]) ?>
    <?= $form->field($model, 'purpose')->textarea(['rows' => 4, 'value' =>  preg_replace('#\[n\]#', "\n", $model->purpose)]) ?>
    <?= $form->field($model, 'expected_outputs')->textarea(['rows' => 4, 'value' =>  preg_replace('#\[n\]#', "\n", $model->expected_outputs)]) ?>






    <table id="items_table" class="table">

        <tbody>

            <?php

            foreach ($items as $item) {
                $employee_name = !empty($item['employee_name']) ? $item['employee_name'] : '';
                $employee_id = !empty($item['employee_id']) ? $item['employee_id'] : '';
                $from_date = !empty($item['from_date']) ? $item['from_date'] : '';
                $to_date = !empty($item['to_date']) ? $item['to_date'] : '';
                $item_id = !empty($item['id']) ? $item['id'] : '';
                echo "<tr class='panel  panel-default' style='margin-top: 2rem;margin-bottom:2rem;'>
                    <td style='max-width:100rem;'>
                    <input type='hidden' name='items[$row_number][item_id]' class=' form-control item_id' value='$item_id'>

                        <label for='employee'>Employee</label>
                        <select name='items[$row_number][employee_id]' class='employee_select form-control ' style='width: 100%'>
                            <option value='$employee_id'>$employee_name</option>
                        </select>
                    </td>
                    <td>
                        <label for='from_date'>From Date</label>
                        <input type='date' name='items[$row_number][from_date]' class='from_date form-control' value='$from_date'>
                    </td>
                    <td>
                        <label for='to_date'>To Date</label>
                        <input type='date' name='items[$row_number][to_date]' class=' form-control to_date' value='$to_date'>


                    </td>
                    <td style='  text-align: center;'>
                        <div class='pull-right'>
                            <button class='add_new_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>


                </tr>";
                $row_number++;
            }
            ?>
            <?php
            if (empty($items)) {

            ?>
                <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                    <td style="max-width:100rem;">

                        <label for="employee">Employee</label>
                        <select name="items[0][employee_id]" class="employee_select form-control " style="width: 100%">
                            <option></option>
                        </select>
                    </td>
                    <td>
                        <label for="from_date">From Date</label>
                        <input type="date" class="from_date form-control">
                    </td>
                    <td>
                        <label for="to_date">To Date</label>
                        <input type="date" class=" form-control to_date">


                    </td>
                    <td style='  text-align: center;'>
                        <div class='pull-right'>
                            <button class='add_new_row btn btn-primary btn-xs' type="button"><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>


                </tr>
            <?php } ?>

        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .hidden {
        display: none;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<script>
    let row_number = <?= $row_number ?>


    $(document).ready(function() {

        employeeSelect()
        $(".remove_this_row").on("click", function(event) {
            event.preventDefault();
            $(this).closest("tr").remove();
        });
        $(".add_new_row").on("click", function(event) {
            event.preventDefault();
            console.log('qwe')
            $(".employee_select").select2("destroy");




            const source = $(this).closest("tr");
            const clone = source.clone(true);
            clone.find(".from_date").attr("name", `items[${row_number}][from_date]`);
            clone.find(".from_date").val("");
            clone.find(".to_date").val("");
            clone.find(".to_date").attr("name", `items[${row_number}][to_date]`);
            clone.find(".employee_select").attr("name", `items[${row_number}][employee_id]`);
            clone.find(".employee_select").val("");
            clone.find(".item_id").remove();
            clone.find(".remove_this_row").removeClass("disabled");
            $("#items_table tbody").append(clone);
            row_number++;
            employeeSelect()
        });
        $('#travelorder-purpose').change(() => {
            let specs = $('#travelorder-purpose').val()
            console.log(specs)
            specs = specs.replace(/\n/g, "[n]");
            specs = specs.replace(/"/g, '\'');

            $('.purpose').val(specs)
        })
        $('#travelorder-expected_outputs').on('change keyup', () => {
            let specs = $('#travelorder-expected_outputs').val()
            console.log(specs)
            specs = specs.replace(/\n/g, "[n]");

            $('.expected_output').val(specs)
        })

    })
</script>