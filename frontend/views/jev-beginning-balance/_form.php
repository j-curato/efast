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

    <div class="container">



        <div class="row">
            <div class="col-sm-3">
                <?= DatePicker::widget([
                    'name' => 'year',
                    'value' => $model->year,
                    'pluginOptions' => [
                        'format' => 'yyyy',
                        'autoclose' => true,
                        'minViewMode' => 'years'
                    ]
                ]) ?>
            </div>
            <div class="col-sm-3">
                <?= Select2::widget([
                    'id' => 'book_id',
                    'name' => 'book_id',
                    'value' => $model->book_id,
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ]) ?>
            </div>
        </div>

        <table id="form_fields_data">
            <tbody>

                <?php
                $i = 1;
                if (!empty($entries)) {

                    foreach ($entries as $val) {
                        echo "<tr class='panel  panel-default' style='margin-top: 2rem;margin-bottom:2rem;'>
                            <td style='max-width:100rem;'>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <label for='chart_of_account'>Chart of Accounts</label>
                                        <select required name='object_code[$i]' class='chart-of-account object_code form-control' required style='width: 100%'>
                                            <option value='{$val['object_code']}'>" . $val['object_code'] . '-' . $val['account_title'] . "</option>
                                        </select>
                                    </div>
                                    <div class='col-sm-4'>
                                        <label for='debit_amount'>Debit</label>
                                        <input type='text' class='debit_amount  form-control'  value='{$val['debit']}'>
                                        <input type='hidden' name='debit[$i]' value='{$val['debit']}' class='debit'>
                                    </div>
                                    <div class='col-sm-4'>
                                        <label for='credit_amount'>Credit</label>
                                        <input type='text' class='credit_amount form-control' value='{$val['credit']}'>
                                        <input type='hidden' name='credit[$i]' class='credit' value='{$val['credit']}'>
                                    </div>
                                </div>
                            </td>
                            <td style='  text-align: center;'>
                                <div class='pull-right'>
                                    <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                    <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </td>
        
        
                        </tr>
                        <tr>
                        <td colspan='2'>
                            <hr>
                        </td>
                    </tr>
                        ";
                        $i++;
                    }
                } else {


                ?>
                    <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                        <td style="max-width:100rem;">

                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="chart_of_account">Chart of Accounts</label>
                                    <select required name="object_code[0]" class="chart-of-account object_code form-control" required style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <label for="debit_amount">Debit</label>
                                    <input type="text" class="debit_amount form-control">
                                    <input type="hidden" name="debit[0]" class="debit">
                                </div>
                                <div class="col-sm-4">
                                    <label for="credit_amount">Credit</label>
                                    <input type="text" class="credit_amount form-control">
                                    <input type="hidden" name="credit[0]" class="credit">
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
                    <tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>

        <div class="form-group">
            <button class="btn btn-success" type="button" id="save">Save</button>
        </div>


    </div>

</div>
<style>
    .is-invalid .select2-container--default .select2-selection--single {
        border-color: #dc3545;
    }

    .select2-hidden-accessible select {
        display: block;
        margin: 0px auto;
        opacity: 0;
    }

    table {
        width: 100%;
    }

    .container {
        padding: 3rem;
        background-color: white;
    }


    th,
    td {
        padding: 1rem;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    function chartOfAccountSelect() {
        $('.chart-of-account').select2({
            ajax: {
                url: window.location.pathname + '?r=chart-of-accounts/search-accounting-code',
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
        $('.debit_amount').maskMoney({
            allowNegative: true
        });
        $('.credit_amount').maskMoney({
            allowNegative: true
        });
    }
    $(document).ready(function() {
        chartOfAccountSelect()
        maskAmount()
        var x = <?= $i ?>;
        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            $('.chart-of-account').select2('destroy');
            var source = $(this).closest('tr');
            var clone = source.clone(true);

            clone.children('td').eq(0).find('.chart-of-account').val('')
            clone.children('td').eq(0).find('.chart-of-account').attr('name', 'object_code[' + x + ']')
            clone.children('td').eq(0).find('.debit').val(0)
            clone.children('td').eq(0).find('.credit').val(0)
            clone.children('td').eq(0).find('.debit').attr('name', 'debit[' + x + ']')
            clone.children('td').eq(0).find('.credit').attr('name', 'credit[' + x + ']')
            clone.children('td').eq(0).find('.amount').val(0)
            clone.children('td').eq(0).find('.credit_amount').val(0)
            clone.children('td').eq(0).find('.debit_amount').val(0)


            // clone.children('td').eq(0).find('.specification').val('')
            $('#form_fields_data').append(clone);
            var spacer = `<tr>
                        <td colspan="2">
                            <hr>
                        </td>
                    </tr>`
            $('#form_fields_data').append(spacer);
            clone.find('.remove_this_row').removeClass('disabled');
            chartOfAccountSelect()
            maskAmount()
            x++


        });
        $('.debit_amount').on('change keyup', function(e) {
            e.preventDefault()
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('tr');
            source.children('td').eq(0).find('.debit').val(amount)

        })
        $('.credit_amount').on('change keyup', function(e) {
            e.preventDefault()
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('tr');
            source.children('td').eq(0).find('.credit').val(amount)

        })

        $('#save').click(function(e) {
            const debit = []
            $(".debit").map(function() {
                let index_number = parseInt(this.name.replace(/[^0-9.]/g, ""));
                debit[index_number] = this.value
            }).get();
            let credit = []
            $(".credit").map(function() {
                let index_number = parseInt(this.name.replace(/[^0-9.]/g, ""));
                credit[index_number] = this.value

            }).get()
            const object_codes = []
            $('.object_code').map(function() {
                let index_number = parseInt(this.name.replace(/[^0-9.]/g, ""));
                object_codes[index_number] = this.value

            }).get()

            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: {
                    year: $("input[name='year']").val(),
                    book_id: $("#book_id").val(),
                    object_code: JSON.stringify(Object.assign({}, object_codes)),
                    debit: JSON.stringify(Object.assign({}, debit)),
                    credit: JSON.stringify(Object.assign({}, credit))
                },
                success: function(data) {
                    console.log(data)
                }
            })
        })

    })
</script>

<?php
$script = <<< JS
    $(document).ready(function(){

    })
JS;
$this->registerJs($script);
?>