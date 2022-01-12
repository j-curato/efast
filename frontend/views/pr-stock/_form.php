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

    <div class="panel panel-default container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'bac_code')->textInput() ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'unit_of_measure_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(UnitOfMeasure::find()->asArray()->all(), 'id', 'unit_of_measure'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Unit of Measure'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-6">
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
            </div>
        </div>
        <div class="row">

            <div class="col-sm-6">


                <?= $form->field($model, 'stock')->textarea([
                    'row' => 2,
                    'style' => 'max-width:100%; max-height: 15rem;'

                ]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'description')->textarea([
                    'row' => 2,
                    'style' => 'max-width:100%; max-height: 15rem;'

                ]) ?>

            </div>

        </div>




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

                if (!empty($model->id) && !empty($model->prStockSpecification)) {

                    foreach ($model->prStockSpecification as $val) {
                        echo "<tr>
                        <td> <input type='text' name='specification[]' class='specification form-control' value='$val->description' placeholder='Specification'> </td>
                        <td style='  text-align: center;'>
                            <div class='pull-left'>
                                <button class='add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </div>
                        </td>
                    </tr>";
                    }
                } else {
                    echo "<tr>
                    <td> <input type='text' name='specification[]' class='specification form-control' placeholder='Specification'> </td>
                    <td style='  text-align: center;'>
                        <div class='pull-left'>
                            <button class='add_new_row btn btn-success btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                            <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>
                </tr>";
                }
                ?>


            </tbody>
        </table> -->
        <div class="form-group" style="text-align: center;">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:50rem;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
<style>
    .panel {
        padding: 20px;
    }
</style>


<script type="text/javascript">
    $(document).ready(function() {
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
</script>