<?php

use app\components\helpers\MyHelper;
use app\models\EmployeeSearchView;
use app\models\Office;
use app\models\PropertyStatus;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Rlsddp */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="rlsddp-form card" style="padding: 1rem;">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="row">

        <?php

        if (Yii::$app->user->can('ro_property_admin')) {
            echo ' <div class="col-sm-2">';
            echo     $form->field($model, 'fk_office_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                'pluginOptions' => [
                    'placeholder' => ''
                ]
            ]);
            echo '</div>';
        }
        ?>
        <div class="col-sm-2">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-2">
            <?= $form->field($model, 'status')->widget(Select2::class, [
                'data' => [
                    '1' => 'Lost',
                    '2' => 'Stolen',
                    '3' => 'Damaged',
                    '4' => 'Destroyed',
                ],
                'pluginOptions' => [
                    'placeholder' => 'Select Property Status'
                ]
            ]) ?>
        </div>





        <div class="col-sm-3">
            <?= $form->field($model, 'fk_acctbl_offr')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_acctbl_offr), 'employee_id', 'employee_name'),
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
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page||1}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>

        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_supvr')->widget(Select2::class, [
                'data' => ArrayHelper::map(
                    MyHelper::getEmployee($model->fk_supvr),
                    'employee_id',
                    'employee_name',
                    'employee_id',
                    'employee_name'
                ),
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
                        'data' => new JsExpression('function(params) { return {q:params.term,page:params.page||1}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]) ?>

        </div>
        <div class="col-sm-2">

            <?= $form->field($model, 'is_blottered')->widget(Select2::class, [
                'data' => ['1' => 'Yes', '0' => 'No'],
                'pluginOptions' => [
                    'placeholder' => ''
                ]
            ]) ?>
        </div>

        <div class="col-sm-3">
            <?= $form->field($model, 'blotter_date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'police_station')->textInput() ?>
        </div>
    </div>
    <?= $form->field($model, 'circumstances')->textarea(['rows' => 4]) ?>
    <table class="" id="par_table">
        <thead>
            <tr>
                <th> PAR No.</th>
                <th> PAR Date</th>
                <th> Property Number </th>
                <th> Description </th>
                <th> Actual User </th>
                <th> Acquisition Cost </th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php

            foreach ($items as $itm) {
                echo "<tr>
                <td  style='display:none;'><input type='hidden' value ='{$itm['id']}' name='items[$itemRow][par_id]'>
                <input type='hidden' value ='{$itm['item_id']}' name='items[$itemRow][item_id]'>
                </td>
                <td> {$itm['par_number']}</td>
                <td> {$itm['par_date']}</td>
                <td> {$itm['property_number']}</td>
                <td> {$itm['article']}\n {$itm['description']}</td>
                <td> {$itm['actual_user']}</td>
                <td>" . number_format($itm['acquisition_amount'], 2) . "</td>
                <td><a class='remove btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>
            </tr>";
                $itemRow++;
            }
            ?>
        </tbody>
    </table>

    <div class="row justify-content-center" style="padding-top: 2rem;">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>


</div>
<style>
    .container {
        padding: 2rem;
    }

    .panel {
        padding: 2rem;
    }

    #par_table {
        width: 100%;
    }

    #par_table th,
    #par_table td {
        padding: 8px;
        border: 1px solid black;
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends', [JqueryAsset::class]]);
?>
<script>
    let itemRow = <?= $itemRow ?>;

    function display(data) {
        $('#par_table tbody').html('')
        $.each(data, (key, val) => {
            const r = `<tr>
                <td style='display:none'><input type='hidden' value ='${val.id}' name='items[${itemRow}][par_id]'></td>
                <td>${val.par_number}</td>
                <td>${val.par_date}</td>
                <td>${val.property_number}</td>
                <td>${val.article}\n${val.description}</td>
                <td>${val.actual_user}</td>
                <td>${thousands_separators(val.acquisition_amount)}</td>
                <td><a class='remove btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>
            </tr>`
            $('#par_table tbody').append(r)
            itemRow++
        })
    }
    $(document).ready(() => {
        $('#par_table').on('click', '.remove', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('#rlsddp-fk_acctbl_offr').change(() => {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=rlsddp/get-pars',
                data: {
                    id: $('#rlsddp-fk_acctbl_offr').val()
                },
                success: (data) => {
                    const res = JSON.parse(data)
                    console.log(res)
                    display(res)
                }
            })
        })
    })
</script>

<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#Rlsddp").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            console.log(res)
            swal({
                icon: 'error',
                title: res.error_message,
                type: "error",
                timer: 3000,
                closeOnConfirm: false,
                closeOnCancel: false
            })
        },
        error: function (data) {
     
        }
    });
    return false;
});
JS;
$this->registerJs($js);
?>