<?php

use app\models\UnitOfMeasure;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */
/* @var $form yii\widgets\ActiveForm */

$chart_of_account_id = '';
if (!empty($model->id)) {
    $chart_of_account_query   = Yii::$app->db->createCommand("SELECT
     id,
     CONCAT(uacs,'-',general_ledger)  as account 

    FROM chart_of_accounts WHERE id = :id")
        ->bindValue(':id', $model->chart_of_account_id)
        ->queryAll();

    $chart_of_account_id = ArrayHelper::map($chart_of_account_query, 'id', 'account');
}
?>

<div class="pr-stock-form">

    <?php


    ?>


    <?php $form = ActiveForm::begin(); ?>

    <!-- 
    <div class="row">
        <div class="col-sm-3">
            <select name="part" class="part" style="width: 100%;">

                <option></option>

            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <select name="stock_type" class="stock_type" style="width: 100%;">

                <option></option>

            </select>
        </div>
    </div> -->

    <?= $form->field($model, 'part')->widget(Select2::class, [
        'data' => ['part-1' => 'Part-1', 'part-2' => 'Part-2', 'part-3' => 'Part-3'],
        'pluginOptions' => [
            'placeholder' => 'Select Unit of Measure'
        ]
    ]) ?>


    <?= $form->field($model, 'type')->widget(Select2::class, [
        'data' => [],
        'pluginOptions' => [
            'placeholder' => 'Select Unit of Measure'
        ],
        'options' => [
            'style' => 'width:100%'
        ]
    ]) ?>
    <?= $form->field($model, 'bac_code')->textInput() ?>

    <?= $form->field($model, 'unit_of_measure_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(UnitOfMeasure::find()->asArray()->all(), 'id', 'unit_of_measure'),
        'pluginOptions' => [
            'placeholder' => 'Select Unit of Measure'
        ]
    ]) ?>
    <?= $form->field($model, 'chart_of_account_id')->widget(Select2::class, [
        'data' => $chart_of_account_id,
        'options' => ['placeholder' => 'Search for a Chart of Account ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => Yii::$app->request->baseUrl . '?r=chart-of-accounts/search-chart-of-accounts',
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



    <?= $form->field($model, 'stock_title')->textarea([
        'row' => 2,
        'style' => 'max-width:100%; max-height: 15rem;'

    ]) ?>







    <?= $form->field($model, 'amount')->widget(MaskMoney::class, [
        'options' => [
            'class' => 'amounts',
        ],
        'pluginOptions' => [
            'prefix' => 'PHP ',
            'allowNegative' => true
        ],
    ]) ?>




    <!-- <table class="table table-striped table-bordered" id="form_fields_data">
            <thead>
                <tr>
                    <th>Specification</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
        

                <?php

                // if (!empty($model->id) && !empty($model->prStockSpecification)) {

                //     foreach ($model->prStockSpecification as $val) {
                //         echo "<tr>
                //         <td> <input type='text' name='specification[]' class='specification form-control' value='$val->description' placeholder='Specification'> </td>
                //         <td style='  text-align: center;'>
                //             <div class='pull-left'>
                //                 <button class='add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                //                 <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                //             </div>
                //         </td>
                //     </tr>";
                //     }
                // } else {
                //     echo "<tr>
                //     <td> <input type='text' name='specification[]' class='specification form-control' placeholder='Specification'> </td>
                //     <td style='  text-align: center;'>
                //         <div class='pull-left'>
                //             <button class='add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                //             <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                //         </div>
                //     </td>
                // </tr>";
                // }
                ?>


            </tbody>
        </table> -->
    <div class="form-group" style="text-align: center;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:20rem;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .panel {
        padding: 20px;
    }
</style>


<script type="text/javascript">

</script>

<?php
$script = <<<JS
    var parts = [
        'Part-1',
        'Part-2',
        'Part-3',

    ];


    var stock = [];

    var stock_types = [];
    $(document).ready(function() {

        $('#prstock-part').change(function(e) {
            e.preventDefault()
            var q = $(this).val()
            var x = q.toLowerCase()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + "?r=pr-stock/get-part&part=" + x,
                success: function(data) {
                    // console.log(data)
                    var types = JSON.parse(data)
                    stock_types = types

                    $('#prstock-type')
                        .find('option')
                        .remove()
                        .end()
                        .append('<option value=""></option>')
                        .val('');


                    var array = []
                    var stockTypeSelect = $('#prstock-type')
                    $.each(types, function(key, val) {

                        var option = new Option([val.type], [val.type], true, true);
                        stockTypeSelect.append(option)
                    })
                    $('#prstock-type').val('')

                    // console.log(array)
                    // var types = array
                    // for (var i = 0; i < types.length; i++) {

                    // }

                    // $('#prstock-type').select2({
                    //     data: qqq,
                    //     placeholder: "Select Unit of Measure",

                    // })
                }
            })

            if ($('#prstock-part').val()!='part-1'){

                $('#prstock-bac_code').attr('disabled',true)
            }
            else{

                $('#prstock-bac_code').attr('disabled',false)
            }

        })
        $('#prstock-type').on('change', function(e) {
            e.preventDefault()
            var q = $(this).val()
            var find_type = stock_types.filter(function(n) {
                return n.type === q
            })
            if ($('#prstock-part').val() != 'part-3') {
                $.ajax({
                    type: 'POST',
                    url: window.location.pathname + '?r=chart-of-accounts/get-chart-info',
                    data: {
                        object_code: find_type[0]['object_code']
                    },
                    success: function(data) {
                        console.log(data)
                        var res = JSON.parse(data)
                        var stockSelect = $('#prstock-chart_of_account_id')
                        var option = new Option([res.chart_account], [res.id], true, true);
                        stockSelect.append(option).trigger('change');

                    }
                })
            }



        })

        $('.part').select2({
            data: parts,
            placeholder: "Select Unit of Measure",

        })
        $('.stock_type').select2({
            data: stock,
            placeholder: "Select Unit of Measure",

        })
        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            var source = $(this).closest('tr');
            var clone = source.clone(true);
            clone.children('td').eq(0).find('.specification').val('')
            $('#form_fields_data').append(clone);
            clone.find('a.disabled').removeClass('disabled');
        });
    });

JS;

$this->registerJs($script);


?>