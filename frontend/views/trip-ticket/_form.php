<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\TripTicket */
/* @var $form yii\widgets\ActiveForm */

$driver = '';
$authorized_by = '';

if (!empty($model->driver)) {

    $driver_query = YIi::$app->memem->updateEmployee($model->driver);
    $driver  = ArrayHelper::map($driver_query, 'employee_id', 'employee_name');
}
if (!empty($model->authorized_by)) {

    $authorized_by_query = YIi::$app->memem->updateEmployee($model->authorized_by);
    $authorized_by  = ArrayHelper::map($authorized_by_query, 'employee_id', 'employee_name');
}
$row_number = 1;
?>

<div class="trip-ticket-form card" style='padding:1rem'>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <div class="col-sm-2">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [

                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                ]
            ]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'to_date')->widget(DatePicker::class, [

                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoClose' => true,
                ]
            ]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'car_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,UPPER(car_name) as car_name FROM cars ")->queryAll(), 'id', 'car_name'),
                'pluginOptions' => [
                    'placeholder' => "Select Car"
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'driver')->widget(Select2::class, [
                'data' => $driver,
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
            <?= $form->field($model, 'authorized_by')->widget(Select2::class, [
                'data' => $authorized_by,
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
    <?= $form->field($model, 'purpose')->textarea(['rows' => 3]) ?>


    <table class="table" id="items_table">

        <?php

        foreach ($items as $item) {
            $id = $item['id'];
            $departure_time = $item['departure_time'];
            $departure_place = $item['departure_place'];
            $arrival_time = $item['arrival_time'];
            $arrival_place = $item['arrival_place'];
            $employee_id = $item['employee_id'];
            $employee_name = $item['employee_name'];
            echo "<tr class='panel  panel-default' style='margin-top: 2rem;margin-bottom:2rem;'>
                <td style='max-width:100rem;'>
                <input type='hidden' name='items[$row_number][item_id]' class='item_id form-control' value='$id'>
                    <div class='row'>

                       
                        <div class='col-sm-2'>
                            <label for='departure_time'>Departure Time</label>
                            <input type='time' name='items[$row_number][departure_time]' class='departure_time form-control' value='$departure_time'>
                        </div>

                        <div class='col-sm-3'>
                            <label for='departure_place'>Place</label>
                            <input type='text' name='items[$row_number][departure_place]' class='form-control departure_place'  value='$departure_place'>
                        </div>
                        <div class='col-sm-2'>
                            <label for='arrival_time'>Arival Time</label>
                            <input type='time' name='items[$row_number][arrival_time]' class=' form-control arrival_time'  value='$arrival_time'>
                        </div>
                        <div class='col-sm-2'>

                            <label for='quantity'>Place</label>
                            <input type='text' name='items[$row_number][arrival_place]' class='form-control  arrival_place'  value='$arrival_place'>

                        </div>
                        <div class='col-sm-3'>
                            <label for='employee'>Employee</label>
                            <select name='items[$row_number][employee_id]' class='employee_select form-control ' style='width: 100%' >
                                <option value='$employee_id'> $employee_name</option>
                            </select>
                        </div>

                    </div>
                    </td>
                    <td style='  text-align: center;'>
                        <div class='pull-right'>
                            <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>

            </tr>";
            $row_number++;
        }
        if (empty($items)) {
        ?>
            <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                <td style="max-width:100rem;">

                    <div class="row">

                        <div class="col-sm-2">
                            <label for="departure_time">Departure Time</label>
                            <input type="time" name="items[0][departure_time]" class="departure_time form-control">
                        </div>

                        <div class="col-sm-3">
                            <label for="departure_place">Place</label>
                            <input type="text" name="items[0][departure_place]" class="form-control departure_place">
                        </div>



                        <div class="col-sm-2">
                            <label for="arrival_time">Arival Time</label>
                            <input type="time" name="items[0][arrival_time]" class=" form-control arrival_time">
                        </div>
                        <div class="col-sm-2">

                            <label for="quantity">Place</label>
                            <input type="text" name="items[0][arrival_place]" class="form-control  arrival_place">

                        </div>
                        <div class="col-sm-3">
                            <label for="employee">Employee</label>
                            <select name="items[0][employee_id]" class="employee_select form-control " style="width: 100%">
                                <option></option>
                            </select>
                        </div>

                    </div>


                </td>
                <td style='  text-align: center;'>
                    <div class='pull-right'>
                        <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                        <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                    </div>
                </td>


            </tr>
        <?php } ?>
    </table>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>
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
            $(".employee_select").select2("destroy");

            const source = $(this).closest("tr");
            const clone = source.clone(true);
            clone.find(".book").val("").trigger("change");
            clone.find(".departure_time").attr("name", `items[${row_number}][departure_time]`);
            clone.find(".departure_place").attr("name", `items[${row_number}][departure_place]`);
            clone.find(".arrival_time").attr("name", `items[${row_number}][arrival_time]`);
            clone.find(".arrival_place").attr("name", `items[${row_number}][arrival_place]`);
            clone.find(".employee_select").attr("name", `items[${row_number}][employee_id]`);
            clone.find(".item_id").remove();
            clone.find(".remove_this_row").removeClass("disabled");
            $("#items_table tbody").append(clone);
            row_number++;
            employeeSelect()
        });
    })
</script>