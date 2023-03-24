<?php

use app\components\helpers\MyHelper;
use app\models\Property;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Derecognition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="derecognition-form panel">

    <div class="notes">
        Notes:
        <ul>
            <li>
                <span><i>If property number cannot be search it means it has no other property details.</i></span>
            </li>
        </ul>

    </div>

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="row" style="margin-top: 5rem;">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true
                ]
            ]) ?>

        </div>
        <!-- <div class="col-sm-3">
            <?= $form->field($model, 'type')->widget(Select2::class, [
                'data' => ['0' => 'q', '1' => 'With Irrup'],
                'pluginOptions' => [],
            ]) ?>
        </div> -->
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_property_id')->widget(Select2::class, [
                'data' => !empty($model->fk_property_id) ?
                    ArrayHelper::map(
                        Property::find()
                            ->where('property.id  = :id', ['id' => $model->fk_property_id])
                            ->asArray()
                            ->all(),
                        'id',
                        'property_number'
                    )
                    : [],
                'options' => ['placeholder' => 'Search Property No.'],
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
                        'data' => new JsExpression('function(params) {return {
                            q:params.term,
                            page:params.page||1,
                            withOPD:true,
                        }; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],
            ]) ?>
        </div>

    </div>





    <table class="table" id="iirup_items">
        <thead>
            <th>Book</th>
            <th> Date Acquired</th>
            <th> Particulars/Articles</th>
            <th>Property Number</th>
            <th>Quantity</th>
            <th>Unit Cost</th>
            <th>Total Cost</th>
            <th>Accumulated Depreciation</th>
            <th>Carrying Amount</th>
        </thead>
        <tbody>

        </tbody>
    </table>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .derecognition-form {
        padding: 2rem;
    }

    th,
    td {
        text-align: center;
    }

    .notes span {
        color: red;
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>
<script>
    function display(data) {
        $('#iirup_items tbody').html('')
        $.each(data, (key, val) => {
            const r = `<tr>
                <td>${val.book_name}</td>
                <td>${val.date_acquired}</td>
                <td><b>${val.article_name}</b><br><i>${val.description}</i></td>
                <td>${val.property_number}</td>
                <td>1</td>
                <td>${thousands_separators(val.acquisition_amount)}</td>
                <td>${thousands_separators(val.acquisition_amount)}</td>
                <td>${thousands_separators(val.mnthly_depreciation)}</td>
                <td>${thousands_separators(val.book_amt)}</td>
            </tr>`
            $('#iirup_items tbody').append(r)
        })
    }
    $(document).ready(() => {
        const q = <?= json_encode($propertyDetails) ?>;
        if (q.length != 0) {
            display(q)
        }
        $('#derecognition-fk_property_id').change(() => {

            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=derecognition/get-property-details',
                data: {
                    id: $('#derecognition-fk_property_id').val()
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
$("#Derecognition").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            console.log(typeof res.error_message)
            // if (typeof JSON.parse(res.error_message) === 'object') {
            //     console.log('object error')
            // }
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