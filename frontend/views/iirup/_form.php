<?php

use app\components\helpers\MyHelper;
use app\models\Office;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Iirup */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="iirup-form card">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true
                ]
            ]) ?>
        </div>

        <?php

        if (Yii::$app->user->can('ro_property_admin')) {
            echo '   <div class="col-sm-3">';
            echo   $form->field($model, 'fk_office_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                'pluginOptions' => [
                    'placeholder' => 'Select Office'
                ]
            ]);
            echo ' </div>';
        }
        ?>

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_acctbl_ofr')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_acctbl_ofr), 'employee_id', 'employee_name'),
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
            <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_approved_by), 'employee_id', 'employee_name'),
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
    </div>
    <table class="table" id="items_tbl">
        <thead>
            <th> Book</th>
            <th> Property No.</th>
            <th> Date Acquired</th>
            <th> Particulars/Articles</th>
            <th> Unit Cost</th>
            <th>PAR No.</th>
            <th>Accumulated Depreciation</th>
            <th>Carrying Amount</th>
        </thead>
        <tbody>
            <?php

            foreach ($items as $itm) {

                echo "<tr>
               
                <td style='display:none'>
                <input type='hidden' value='{$itm['item_id']}' name='items[$itemRow][item_id]'>
                <input type='hidden' value='{$itm['other_property_detail_item_id']}' name='items[$itemRow][other_property_detail_item_id]'>
                </td>
               <td>{$itm['book_name']}</td>
               <td>{$itm['property_number']}</td>
               <td>{$itm['date_acquired']}</td>
               <td>{$itm['article_name']} - {$itm['description']}</td>
               <td>{$itm['acquisition_amount']}</td>
               <td>{$itm['par_number']}</td>
               <td>{$itm['mnthly_depreciation']}</td>
               <td>{$itm['amount']}</td>
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
    .iirup-form {
        padding: 2rem;
    }
</style>
<?php
 
?>

<script>
    let itemRow = <?= $itemRow ?>;

    function displayItems(data) {
        $('#items_tbl tbody').html('')
        $.each(data, (key, val) => {
            const r = `<tr>
                 <td style='display:none'><input type='hidden' value='${val.other_property_detail_item_id}' name='items[${itemRow}][other_property_detail_item_id]'></td>
                <td>${val.book_name}</td>
                <td>${val.property_number}</td>
                <td>${val.date_acquired}</td>
                <td>${val.article_name} - ${val.description}</td>
                <td>${thousands_separators(val.acquisition_amount)}</td>
                <td>${val.par_number}</td>
                <td>${thousands_separators(val.mnthly_depreciation)}</td>
                <td>${thousands_separators(val.amount)}</td>
                <td><a class='remove btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a></td>
            </tr>`
            $('#items_tbl tbody').append(r)
            itemRow++
        })


    }

    function getProperties() {
        const reporting_period = $('#iirup-reporting_period').val()
        const emp_id = $('#iirup-fk_acctbl_ofr').val()
        if (reporting_period && emp_id) {
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=iirup/get-properties',
                data: {
                    reporting_period: reporting_period,
                    employee_id: emp_id
                },
                success: (data) => {
                    const res = JSON.parse(data)
                    console.log(res)

                    displayItems(res)
                }
            })
        }
    }
    $(document).ready(() => {
        $('#items_tbl').on('click', '.remove', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('#iirup-reporting_period').change(() => {
            getProperties()
        })
        $('#iirup-fk_acctbl_ofr').change(() => {
            getProperties()
        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#Iirup").on("beforeSubmit", function (event) {
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