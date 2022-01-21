<?php

use app\models\Books;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\JevBeginningBalance */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="jev-beginning-balance-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'year')->widget(DatePicker::class, [
        'pluginOptions' => [
            'format' => 'yyyy',
            'autoclose' => true,
            'minViewMode' => 'years'
        ]
    ]) ?>



    <?= $form->field($model, 'book_id')->widget(Select2::class, [
        'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
        'pluginOptions' => [
            'placeholder' => 'Select Book'
        ]
    ]) ?>

    <table id="form_fields_data">
        <tbody>
            <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                <td style="max-width:100rem;">

                    <div class="row">
                        <div class="col-sm-4">
                            <label for="chart_of_account">Chart of Accounts</label>
                            <select required name="object_code[]" class="chart_of_account form-control" style="width: 100%">
                                <option></option>
                            </select>
                        </div>





                        <div class="col-sm-4">
                            <label for="amount">Unit Cost</label>
                            <input type="text" class="amount form-control">
                            <input type="hidden" name="unit_cost[0]" class="unit_cost">
                        </div>

                        <div class="col-sm-2">

                            <label for="quantity">Quantity</label>
                            <input type="number" name='quantity[0]' class="form-control quantity">

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label for="specs_view">Specification</label>
                            <textarea rows="2" class="specs_view form-control" id="q"></textarea>
                            <input name="specification[0]" rows="2" class="specs" type='hidden'>
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
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    function stockSelect() {
        $('.stocks').select2({
            ajax: {
                url: window.location.pathname + '?r=pr-stock/search-stock',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                },
            },
        });
    }

    function maskAmount() {

        $('.amount').maskMoney({
            allowNegative: true
        });


    }
    $(document).ready(function() {

        var x = 1
        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            $('.stocks').select2('destroy');
            $('.unit_of_measure').select2('destroy');
            $('.unit_cost').maskMoney('destroy');
            var source = $(this).closest('tr');
            var clone = source.clone(true);
            clone.children('td').eq(0).find('.desc').text('')
            clone.children('td').eq(0).find('.quantity').val(0)
            clone.children('td').eq(0).find('.quantity').attr('name', 'unit_of_measure[' + x + ']')

            clone.children('td').eq(0).find('.pr_item_id').val('')
            clone.children('td').eq(0).find('.pr_item_id').attr('name', 'pr_item_id[' + x + ']')
            clone.children('td').eq(0).find('.stocks').val('')
            clone.children('td').eq(0).find('.stocks').attr('name', 'pr_stocks_id[' + x + ']')
            clone.children('td').eq(0).find('.unit_cost').val(0)
            clone.children('td').eq(0).find('.unit_cost').attr('name', 'unit_cost[' + x + ']')
            clone.children('td').eq(0).find('.amount').val(0)
            clone.children('td').eq(0).find('.specs').val(null)
            clone.children('td').eq(0).find('.specs_view').val(null)
            clone.children('td').eq(0).find('.specs').attr('name', 'specification[' + x + ']');

            // clone.children('td').eq(0).find('.specification').val('')
            $('#form_fields_data').append(clone);
            var spacer = `<tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>`
            $('#form_fields_data').append(spacer);
            clone.find('.remove_this_row').removeClass('disabled');
            stockSelect()
            maskAmount()
            unitOfMeasureSelect()
            x++


        });

    })
</script>