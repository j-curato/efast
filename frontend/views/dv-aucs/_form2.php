<?php

use app\models\Books;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

?>
<div class="test">




    <div id="container" class="container">
        <div class="row">
            <div class="col-sm-12" style="color:red;text-align:center">
                <h4 id="link">
                </h4>
            </div>
        </div>
        <form id='save_data' method='POST'>

            <input type="text" name='transaction_timestamp' id="transaction_timestamp" style="display: none;">
            <input type="text" name='book_id' id="book_id" style="display: none;">
            <?php
            $q = 0;
            if (!empty($update_id)) {

                $q = $update_id;
            }
            echo " <input type='text' id='update_id' name='update_id' value='$q' style='display:none' >";
            ?>
            <div class="row">

                <div class="col-sm-3">
                    <label for="reporting_period">Reporting Period</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'reporting_period',
                        'id' => 'reporting_period',
                        // 'value' => '12/31/2010',
                        'options' => ['required' => true],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm',
                            'startView' => "year",
                            'minViewMode' => "months",
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="nature_of_transaction">Nature of Transaction</label>
                    <select required id="nature_of_transaction" name="nature_of_transaction" class="nature_of_transaction select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="mrd_classification">MRD Classification</label>
                    <select required id="mrd_classification" name="mrd_classification" class="mrd_classification select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transaction Type</label>
                    <select required id="transaction" name="transaction_type" class="transaction select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <select required id="payee" name="payee" class="payee select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>


                <div class="col-sm-3" id='bok'>
                    <label for="book">Book</label>
                    <select id="book" name="book" class="book select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>
                <!-- <div class="col-sm-3" id='bok'>
                    <label for="payee_id">payee_id</label>
                    <select id="payee_id" name="payee_id" class="payee_id select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div> -->


            </div>
            <div class="row">
                <textarea name="particular" readonly id="particular" placeholder="PARTICULAR" required rows="3"></textarea>
            </div>

            <table id="transaction_table" class="table table-striped">
                <thead>
                    <th>Ors ID</th>
                    <th>Serial Number</th>
                    <th>Particular</th>
                    <th>Payee</th>
                    <th>Total Obligated</th>
                    <th>Amount Disbursed</th>
                    <th>2306 (VAT/ Non-Vat)</th>
                    <th>2307 (EWT Goods/Services)</th>
                    <th>1601C (Compensation)</th>
                    <th>Other Trust Liabilities</th>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Total</th>
                    <th>
                        <div id="total_disbursed"></div>
                    </th>
                    <th>
                        <div id="total_vat"></div>
                    </th>
                    <th>
                        <div id="total_ewt"></div>
                    </th>
                    <th>
                        <div id="total_compensation"></div>
                    </th>
                    <th>
                        <div id="total_liabilities"></div>
                    </th>

                </tfoot>
            </table>



            <table id="accounting_entry_table" style="width: 100%;">

                <tbody>
                    <?php
                    $row = 1;

                    if (!empty($model->id)) {
                        $entries_query = Yii::$app->db->createCommand("SELECT 
                            dv_accounting_entries.debit,
                            dv_accounting_entries.credit,
                            dv_accounting_entries.object_code,
                            accounting_codes.account_title
                            FROM dv_accounting_entries
                            LEFT JOIN  accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
                            WHERE dv_accounting_entries.dv_aucs_id = :id
                        ")
                            ->bindValue(':id', $model->id)
                            ->queryAll();
                        foreach ($entries_query as $val) {
                            $object_code = $val['object_code'];
                            $account_title = $val['account_title'];
                            $debit = $val['debit'];
                            $credit = $val['credit'];

                            
                            echo "<tr tr class='panel  panel-default' style='margin-top: 2rem;margin-bottom:2rem;'>
                            <td style='max-width:100rem;'>
    
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <label for='isCurrent'>Current/NonCurrent </label>
    
                                        <input type='text' name='isCurrent[$row]' placeholder='Current/NonCurrent' />
                                    </div>
                                    <div class='col-sm-3'>
                                        <label for='cadadr_number'>Cash Flow </label>
    
                                        <select name='cash_flow_id[$row]' style='width: 100% ;display:none'>
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class='col-sm-3'>
                                        <label for='cadadr_number'>Changes in Net Asset and Equity </label>
                                        <select name='isEquity[$row]' style='width: 100% ;display:none'>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
    
                                <div class='row'>
                                    <div class='col-sm-6'>
                                        <label for='stocks'>Chart of Account</label>
                                        <select name='chart_of_account_id[$row]' class='chart-of-accounts' style='width: 100%'>
                                            <option value='$object_code'>$account_title</option>
                                        </select>
                                    </div>
                                    <div class='col-sm-2'>
                                        <label for='debit'></label>
                                        <input type='text' class='debit_amount' placeholder='Debit' value='$debit'>
                                        <input type='hidden' name='debit[$row]' class='debit' placeholder='Debit' value='$debit'>
                                    </div>
                                    <div class='col-sm-2'>
                                        <label for='credit'></label>
                                        <input type='text' class='credit_amount' placeholder='Credit' value='$credit'>
                                        <input type='hidden' name='credit[$row]' class='credit' placeholder='Credit' value='$credit'>
                                    </div>
    
                                </div>
                            </td>
                            <td style='  text-align: center;'>
                                <div class='pull-right' style='padding: 12px;'>
                                    <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                    <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </td>
    
    
                        </tr>";

                            echo " <tr>
                            <td colspan='2'>
                                <hr>
                            </td>
                        </tr>";
                        $row++;
                        }
                    } else {


                    ?>
                        <tr tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                            <td style="max-width:100rem;">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="isCurrent">Current/NonCurrent </label>

                                        <input type="text" name="isCurrent[0]" placeholder="Current/NonCurrent" />
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="cadadr_number">Cash Flow </label>

                                        <select name="cash_flow_id[0]" style="width: 100% ;display:none">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="cadadr_number">Changes in Net Asset and Equity </label>
                                        <select name="isEquity[0]" style="width: 100% ;display:none">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="stocks">Chart of Account</label>
                                        <select name="chart_of_account_id[0]" class="chart-of-accounts" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="debit"></label>
                                        <input type="text" class="debit_amount" placeholder="Debit">
                                        <input type="hidden" name="debit[0]" class="debit" placeholder="Debit">
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="credit"></label>
                                        <input type="text" class="credit_amount" placeholder="Credit">
                                        <input type="hidden" name="credit[0]" class="credit" placeholder="Credit">
                                    </div>

                                </div>
                            </td>
                            <td style='  text-align: center;'>
                                <div class='pull-right' style="padding: 12px;">
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

            <div class="total row">

                <div class="col-sm-3 col-md-offset-5">

                    <div>
                        <label for="d_total"> Total Debit</label>
                        <div id="d_total">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">

                        <label for="c_total"> Total Credit</label>
                        <div id="c_total">
                        </div>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-success" style="width: 100%;" id="save" name="save"> SAVE</button>
        </form>

        <form name="add_data" id="add_data">

            <div style="display: none;">
                <input type="text" id="transaction_type" name="transaction_type">
                <input type="text" id="dv_count" name="dv_count">
            </div>

            <!-- PROCESS ORS ANG MODEL -->
            <!-- NAA SA CREATE CONTROLLER NAKO GE CHANGE -->
            <?php
            $dataProvider->pagination = ['pageSize' => 10];

            ?>
            <!-- <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'panel' => [
                            // 'type' => GridView::TYPE_PRIMARY,
                            'heading' => 'List of Areas',
                        ],
                        'floatHeaderOptions' => [
                            'top' => 50,
                            'position' => 'absolute',
                        ],
                        'export' => false,
                        'pjax' => true,
                        'showPageSummary' => true,
                        'columns' => [

                            'serial_number',
                            'transaction.particular',
                            'transaction.payee.account_name',
                            [
                                'label' => 'Book',
                                'attribute' => 'book_id',
                                'value' => 'book.name',
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                                'filterWidgetOptions' => [
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'placeholder' => 'Select Book'
                                    ]
                                ],
                                'format' => 'raw'
                            ],

                            [
                                'label' => 'Total Obligated',
                                'value' => function ($model) {
                                    $query = Yii::$app->db->createCommand("SELECT SUM(process_ors_entries.amount)as total
                      
                            FROM process_ors_entries
                            where process_ors_entries.process_ors_id = :ors_id
                           
                         
                          ")
                                        ->bindValue(":ors_id", $model->id)
                                        ->queryOne();
                                    return $query['total'];
                                },
                                'format' => ['decimal', 2],
                                'pageSummary' => true
                            ],

                            [
                                'class' => '\kartik\grid\CheckboxColumn',
                                'checkboxOptions' => function ($model, $key, $index, $column) {
                                    return ['value' => $model->id, 'onchange' => 'enableDisable(this)', 'style' => 'width:20px;', 'class' => 'checkbox', ''];
                                }
                            ],
                            [
                                'label' => 'Amount Disbursed',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return ' ' .  MaskMoney::widget([
                                        'name' => "amount_disbursed[$model->id]",
                                        'disabled' => true,
                                        'id' => "amount_disbursed_$model->id",
                                        'options' => [
                                            'class' => 'amounts',
                                        ],
                                        'pluginOptions' => [
                                            'prefix' => '₱ ',
                                            'allowNegative' => true
                                        ],
                                    ]);
                                }
                            ],
                            [
                                'label' => '2306 (VAT/ Non-Vat)',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return ' ' .  MaskMoney::widget([
                                        'name' => "vat_nonvat[$model->id]",
                                        'disabled' => true,
                                        'id' => "vat_nonvat_$model->id",
                                        'options' => [
                                            'class' => 'amounts',
                                        ],
                                        'pluginOptions' => [
                                            'prefix' => '₱ ',
                                            'allowNegative' => true
                                        ],
                                    ]);
                                }
                            ],
                            [
                                'label' => '2307 (EWT Goods/Services)',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return ' ' .  MaskMoney::widget([
                                        'name' => "ewt_goods_services[$model->id]",
                                        'disabled' => true,
                                        'id' => "ewt_goods_services_$model->id",
                                        'options' => [
                                            'class' => 'amounts',
                                        ],
                                        'pluginOptions' => [
                                            'prefix' => '₱ ',
                                            'allowNegative' => true
                                        ],
                                    ]);
                                }
                            ],
                            [
                                'label' => '1601C (Compensation)',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return ' ' .  MaskMoney::widget([
                                        'name' => "compensation[$model->id]",
                                        'disabled' => true,
                                        'id' => "compensation_$model->id",
                                        'options' => [
                                            'class' => 'amounts',
                                        ],
                                        'pluginOptions' => [
                                            'prefix' => '₱ ',
                                            'allowNegative' => true
                                        ],
                                    ]);
                                }
                            ],
                            [
                                'label' => 'Other Trust Liabilities',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return ' ' .  MaskMoney::widget([
                                        'name' => "other_trust_liabilities[$model->id]",
                                        'disabled' => true,
                                        'id' => "other_trust_liabilities_$model->id",
                                        'options' => [
                                            'class' => 'amounts',
                                        ],
                                        'pluginOptions' => [
                                            'prefix' => '₱ ',
                                            'allowNegative' => true
                                        ],
                                    ]);
                                }
                            ],
                            // [
                            //     'label' => 'Actions',
                            //     'format' => 'raw',
                            //     'value' => function ($model) {
                            //         return ' ' .  MaskMoney::widget([
                            //             'name' => "amount[$model->id]",
                            //             'disabled' => true,
                            //             'id' => "amount_$model->id",
                            //             'options' => [
                            //                 'class' => 'amounts',
                            //             ],
                            //             'pluginOptions' => [
                            //                 'prefix' => '₱ ',
                            //                 'allowNegative' => true
                            //             ],
                            //         ]);
                            //     }
                            // ],

                        ],
                    ]); ?>
            <button type="submit" class="btn btn-primary" name="submit" id="submit" style="width: 100%;"> ADD</button> -->
        </form>







    </div>
</div>

<style>
    textarea {
        max-width: 100%;
        width: 100%;
    }

    .grid-view td {
        white-space: normal;
    }

    .select {
        width: 500px;
        height: 2rem;
    }

    #submit {
        margin: 10px;
    }

    input {
        width: 100%;
        font-size: 15px;
        padding: 5px;
        border-radius: 5px;
        border: 1px solid black;

    }

    .row {
        margin: 5px;
    }

    .container {
        background-color: white;
        height: auto;
        padding: 10px;
        border-radius: 2px;
    }

    .accounting_entries {
        background-color: white;
        padding: 2rem;
        border: 1px solid black;
        border-radius: 5px;
    }

    .swal-text {
        background-color: #FEFAE3;
        padding: 17px;
        border: 1px solid #F0E1A1;
        display: block;
        margin: 22px;
        text-align: center;
        color: #61534e;
    }
</style>

<script src="<?= Url::base() ?>/frontend/web/js/scripts.js" type="text/javascript"></script>

<?php

// $csrfTokenName = Yii::$app->request->csrfTokenName;
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);

$csrfToken = Yii::$app->request->csrfToken;
?>

<!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
<script>
    var vacant = 0;
    var i = 1;
    var x = [0];
    var update_id = undefined;
    var cashflow = [];
    var accounts = [];

    function enableDisable(checkbox) {
        var isDisable = true
        if (checkbox.checked) {
            isDisable = false
        }
        enableInput(isDisable, checkbox.value)

    }

    function removeItem(index) {
        // $(`#form-${index}`).remove();
        // arr_form.splice(index, 1);
        // vacant = index
        // $('#form' + index + '').remove();

        document.getElementById(`form-${index}`).remove()
        for (var y = 0; y < x.length; y++) {
            if (x[y] === index) {
                delete x[y]
                x.splice(y, 1)
            }
        }
        // console.log(x, Math.max.apply(null, x))
        getTotal()


    }

    function isCurrent(index, i) {
        // var chart_id = document.getElementById('chart-0').val()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=jev-preparation/is-current',
            data: {
                chart_id: index.value
            },
            dataType: 'json',
            success: function(data) {

                $('#isCurrent-' + i).val(data.current_noncurrent)
                // console.log(data)
                // data.isCashEquivalent ? : $('#cash_flow_id-' + i).hide()
                data.isEquity ? $('#isEquity-' + i).show() : $('#isEquity-' + i).hide()
                if (data.isCashEquivalent == true) {
                    // $('#cashflow-' + i).select2({
                    //     data: cashflow,
                    //     placeholder: 'Select Cash Flow'
                    // })
                    // $('#cashflow-' + i).val(2).trigger('change');
                    $('#cashflow-' + i).next().show()
                } else {

                    $('#cashflow-' + i).val(null).trigger('change');
                    // document.getElementById('isEquity-' + i).value = 'null'
                    $('#cashflow-' + i).select2().next().hide();


                }
                if (data.isEquity == true) {
                    // $('#isEquity-' + i).select2({
                    //     data: net_asset,
                    //     placeholder: 'Select Net Asset'

                    // })

                    $('#isEquity-' + i).next().show()
                } else {

                    $('#isEquity-' + i).val(null).trigger('change');
                    // document.getElementById('isEquity-' + i).value = 'null'
                    $('#isEquity-' + i).select2().next().hide();


                }
            }
        })
    }




    function getTotal() {
        var total_disbursed = 0;
        var total_vat = 0;
        var total_ewt = 0;
        var total_compensation = 0;
        var total_liabilities = 0;
        $(".amount_disbursed").each(function() {
            total_disbursed += parseFloat($(this).val()) || 0;
        });
        $(".vat").each(function() {
            total_vat += parseFloat($(this).val()) || 0;
        });
        $(".ewt").each(function() {
            total_ewt += parseFloat($(this).val()) || 0;
        });
        $(".compensation").each(function() {
            total_compensation += parseFloat($(this).val()) || 0;
        });
        $(".liabilities").each(function() {
            total_liabilities += parseFloat($(this).val()) || 0;
        });
        $("#total_disbursed").text(thousands_separators(total_disbursed))
        $("#total_vat").text(thousands_separators(total_vat))
        $("#total_ewt").text(thousands_separators(total_ewt))
        $("#total_compensation").text(thousands_separators(total_compensation))
        $("#total_liabilities").text(thousands_separators(total_liabilities))

    }

    function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

    function enableInput(isDisable, index) {
        $(`#amount_${index}-disp`).prop('disabled', isDisable);
        $(`#amount_${index}`).prop('disabled', isDisable);
        $(`#amount_disbursed_${index}-disp`).prop('disabled', isDisable);
        $(`#amount_disbursed_${index}`).prop('disabled', isDisable);
        $(`#vat_nonvat_${index}-disp`).prop('disabled', isDisable);
        $(`#vat_nonvat_${index}`).prop('disabled', isDisable);
        $(`#ewt_goods_services_${index}-disp`).prop('disabled', isDisable);
        $(`#ewt_goods_services_${index}`).prop('disabled', isDisable);
        $(`#compensation_${index}-disp`).prop('disabled', isDisable);
        $(`#compensation_${index}`).prop('disabled', isDisable);
        $(`#other_trust_liabilities_${index}-disp`).prop('disabled', isDisable);
        $(`#other_trust_liabilities_${index}`).prop('disabled', isDisable);
        // button = document.querySelector('.amount_1').disabled=false;

    }

    function remove(i) {
        i.closest("tr").remove()
        dv_count--
        getTotal()
    }

    function addDvToTable(result) {
        if ($("#transaction").val() == 'Single') {
            // $('#particular').val(result[0]['transaction_particular'])
            // $('#payee').val(result[0]['transaction_payee_id']).trigger('change')
        }
        for (var i = 0; i < result.length; i++) {
            if ($('#transaction').val() == 'Single' && i == 1) {
                break;
            }
            $('#book_id').val(result[0]['book_id'])
            var amount_disbursed = result[i]['amount_disbursed'] ? result[i]['amount_disbursed'] : 0;
            var vat_nonvat = result[i]['vat_nonvat'] ? result[i]['vat_nonvat'] : 0;
            var ewt_goods_services = result[i]['ewt_goods_services'] ? result[i]['ewt_goods_services'] : 0;
            var compensation = result[i]['compensation'] ? result[i]['compensation'] : 0;
            var other_trust_liabilities = result[i]['other_trust_liabilities'] ? result[i]['other_trust_liabilities'] : 0;
            var row = `<tr>
                            
 
                            <td > <input style='display:none' value='${result[i]['ors_id']}' type='text' name='process_ors_id[]'/></td>
 
                            <td> ${result[i]['serial_number']}</td>
                            <td> 
                            ${result[i]['transaction_particular']}
                            </td>
                            <td> ${result[i]['transaction_payee']}</td>
                            <td> ${result[i]['total']}</td>
                            <td> <input value='${amount_disbursed}' type='text' name='amount_disbursed[]' class='amount_disbursed'/></td>
                            <td> <input value='${vat_nonvat}' type='text' name='vat_nonvat[]' class='vat'/></td>
                            <td> <input value='${ewt_goods_services}' type='text' name='ewt_goods_services[]' class='ewt'/></td>
                            <td> <input value='${compensation}' type='text' name='compensation[]' class='compensation'/></td>
                            <td> <input value='${other_trust_liabilities}' type='text' name='other_trust_liabilities[]' class='liabilities'/></td>
                            <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
                        `
            $('#transaction_table tbody').append(row);
            // total += amount_disbursed
            select_id++;
            dv_count++;

        }


        getTotal()
        $("#dv_count").val(dv_count)

    }


    var select_id = 0;

    var transaction_type = $("#transaction").val();
    var dv_count = 1;
    var tracking_sheet = []
    var sheet = []
    var net_asset = []


    $(document).ready(function() {
        var entry_counter = <?=$row?>;
        // $("#payee ").select2({'readonly'});
        // $("#particular").prop({disabled:'readonly'});
        $("#bok").hide();


        $('.remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        $('.add_new_row').on('click', function(event) {
            event.preventDefault();
            $('.chart-of-accounts').select2('destroy');
            // $('.unit_of_measure').select2('destroy');
            $('.debit_amount').maskMoney('destroy');
            $('.credit_amount').maskMoney('destroy');
            var source = $(this).closest('tr');
            var clone = source.clone(true);

            clone.children('td').eq(0).find('.chart-of-accounts').val(0)
            clone.children('td').eq(0).find('.chart-of-accounts').attr('name', 'chart_of_account_id[' + entry_counter + ']')
            clone.children('td').eq(0).find('.debit').val(0)
            clone.children('td').eq(0).find('.debit').attr('name', 'debit[' + entry_counter + ']')
            clone.children('td').eq(0).find('.debit_amount').val(0)
            clone.children('td').eq(0).find('.credit').val(0)
            clone.children('td').eq(0).find('.credit').attr('name', 'credit[' + entry_counter + ']')
            clone.children('td').eq(0).find('.credit_amount').val(0)
            $('#accounting_entry_table tbody').append(clone);
            var spacer = `<tr><td colspan="2"><hr></td></tr>`;
            $('#accounting_entry_table tbody').append(spacer);
            clone.find('.remove_this_row').removeClass('disabled');
            maskDebitAmount()
            maskCreditAmount()
            chartOfAccountSelect()
            entry_counter++


        });
        getNatureOfTransactions().then(function(data) {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.id,
                    text: val.name
                })
            })
            nature_of_transaction = array
            $('#nature_of_transaction').select2({
                data: nature_of_transaction,
                placeholder: "Select Nature of Transaction"
            })

        })
        getMrdClassification().then(function(data) {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.id,
                    text: val.name
                })
            })
            mrd_classification = array
            $('#mrd_classification').select2({
                data: mrd_classification,
                placeholder: "Select MRD Classification"
            })

        })
        getBooks().then(function(data) {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.id,
                    text: val.name
                })
            })
            books = array
            $('#book').select2({
                data: books,
                placeholder: "Select Book"
            })

        })
        getNetAssets().then(function(data) {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.id,
                    text: val.specific_change
                })
            })
            net_asset = array
            $('#isEquity-0').select2({
                data: net_asset,
                placeholder: 'Select Net Asset'

            }).next().hide();
        })


        // MAG ADD OG DATA NA BUHATAN OG DV

    })
    $('#submit').click(function(e) {
        var date = new Date()

        // var x = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate() + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds()
        // if ($('#transaction_timestamp').val() == '') {
        //     $('#transaction_timestamp').val(x)

        // }
        e.preventDefault();
        $.ajax({
            url: window.location.pathname + '?r=dv-aucs/get-dv',
            method: "POST",
            data: $('#add_data').serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // console.log(res)
                if (res.isSuccess) {

                    addDvToTable(res.results)
                } else {
                    swal({
                        title: "Error",
                        text: res.error,
                        type: "error",
                        timer: 6000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    });
                }

            }
        });
        $('.checkbox').prop('checked', false); // Checks it
        $('.amounts').prop('disabled', true);
        $('.amounts').val(null);
    })

    function getDebitCreditTotal() {
        var total_credit = 0.00;
        var total_debit = 0.00;
        $(".credit").each(function() {
            total_credit += Number($(this).val());
        })
        $(".debit").each(function() {
            total_debit += Number($(this).val());
        })

        document.getElementById("d_total").innerHTML = "<h4>" + thousands_separators(total_debit) + "</h4>";
        document.getElementById("c_total").innerHTML = "<h4>" + thousands_separators(total_credit) + "</h4>";
        //  $(".debit").change(function(){
        //     $(this).val() =  thousands_separators(total_debit)
        //  })
        // $(this).val().replact
    }

    $(document).on("keyup change", ".credit, .debit", function() {
        getDebitCreditTotal()

    })

    function maskCreditAmount() {

        $('.credit_amount').maskMoney({
            allowNegative: true
        });
    }

    function maskDebitAmount() {

        $('.debit_amount').maskMoney({
            allowNegative: true
        });
    }

    function chartOfAccountSelect() {
        $('.chart-of-accounts').select2({
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
                }
            },
        });
    }

    $(document).ready(() => {

        maskCreditAmount()
        maskDebitAmount()
        chartOfAccountSelect()
        i = 1
        $('.chart-of-accounts').on('change', function() {
            console.log($(this).val())
        })
        $('.credit_amount').on('change keyup', function(e) {
            console.log($(this).val())
            e.preventDefault()
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('tr');
            source.children('td').eq(0).find('.credit').val(amount)
        })
        $('.debit_amount').on('change keyup', function(e) {
            console.log($(this).val())
            e.preventDefault()
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('tr');
            source.children('td').eq(0).find('.debit').val(amount)
        })
        $('#payee').select2({
            ajax: {
                url: window.location.pathname + '?r=payee/search-payee',
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
                }
            },
        });


        var update_id = $('#update_id').val()
        if (update_id > 0) {
            $.ajax({
                url: window.location.pathname + "?r=dv-aucs/update-dv",
                type: "POST",
                data: {
                    dv_id: update_id,
                    _csrf: "<?php echo $csrfToken ?>"
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res.result)
                    var transaction_type = res.result[0]['transaction_type']
                    var type = '';
                    if (!transaction_type) {
                        if (res.result.length > 1) {
                            type = 'Multiple'
                        } else if (res.result.length === 1) {
                            type = 'Single'
                        } else if (res.result.length === 0) {
                            type = 'No Ors'
                        }
                    } else {
                        type = transaction_type
                    }
                    // if (type !='No Ors'){

                    addDvToTable(res.result)
                    // }


                    // $("#particular").val(res.result[0]['particular'])
                    // $("#payee").val(res.result[0]['payee_id']).trigger('change');
                    $("#mrd_classification").val(res.result[0]['mrd_classification_id']).trigger("change");
                    $("#nature_of_transaction").val(res.result[0]['nature_of_transaction_id']).trigger("change");
                    $("#reporting_period").val(res.result[0]['reporting_period'])
                    // $('#transaction').val(type).trigger('change')
                    $('#book').val(res.result[0]['book_id']).trigger('change')

                    if (res.result[0]['tracking_sheet_id'] == null) {
                        $('#transaction').val(type).trigger('change')
                        $("#particular").val(res.result[0]['particular'])
                        // $("#payee").val(res.result[0]['payee_id']).trigger('change');
                        var payeeSelect = $('#payee');
                        var option = new Option([res.result[0]['transaction_payee']], [res.result[0]['payee_id']], true, true);
                        payeeSelect.append(option).trigger('change')

                    } else {

                        var trackingSheetSelect = $('#tracking_sheet');
                        var option = new Option([res.result[0]['tracking_number']], [res.result[0]['tracking_sheet_id']], true, true);
                        trackingSheetSelect.append(option).trigger('change')
                        onTrackingSheetChange(res.result[0]['tracking_sheet_id'])
                        // $('#tracking_sheet').val(res.result[0]['tracking_sheet_id']).trigger('change')

                    }

                    var x = 0

                    var dv_accounting_entries = res.dv_accounting_entries;
                    for (x = 1; x < res.dv_accounting_entries.length; x++) {

                        add()
                    }
                    for (x = 0; x < res.dv_accounting_entries.length; x++) {
                        $("#debit-" + x).val(dv_accounting_entries[x]['debit'])
                        $("#credit-" + x).val(dv_accounting_entries[x]['credit'])
                        var chart = dv_accounting_entries[x]['object_code'] + "-" + dv_accounting_entries[x]['account_title']

                        var cashflow = dv_accounting_entries[x]['cashflow_id'];
                        var net_asset = dv_accounting_entries[x]['net_asset_equity_id'];
                        // $("#chart-" + x).val(dv_accounting_entries[x]['object_code']).trigger('change');

                        var chartAccSelect = $('#chart-' + x);
                        var option = new Option([chart], [dv_accounting_entries[x]['object_code']], true, true);
                        chartAccSelect.append(option).trigger('change')
                        $("#isEquity-" + x).val(dv_accounting_entries[x]['net_asset_equity_id']).trigger('change');
                        $("#cashflow-" + x).val(cashflow).trigger('change');
                        // if ($("#cashflow-" + x).length) {} else {}
                        // if (x < res.dv_accounting_entries.length - 1) {
                        //     add()
                        // }
                    }
                    getDebitCreditTotal()

                }
            })
        }
    })
</script>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this); ?>
<?php

$script = <<< JS
        var reporting_period = '';
        var transactions=[];
        var nature_of_transaction=[];
        var reference=[];
        var mrd_classification=[];
        var books=[];
        var bbb=undefined;

       


    $('#transaction_table').on('change keyup', ['.amount_disbursed','.ewt','.vat','.compensation','.liabilities'], function() {
        getTotal()

     });


    
    $("#transaction").change(function(){

        const date = new Date().toLocaleString( { timeZone: 'Asia/Manila' });
            // console.log(date);   
        //    var transaction_id = $("#transaction_id").val()
        //     var date = new Date()

            // var x = date.getFullYear()+'-'+date.getMonth() + '-'+ date.getDate() + ' ' + date.getHours()+':'+date.getMinutes()+':'+date.getSeconds()
            // console.log(x)
            $('#transaction_timestamp').val(date)
          
        var transaction_type=$("#transaction").val()

        $("#transaction_type").val(transaction_type)
        // if (transaction_type =='Single'){
        var result=[1]
        // }
        var count=$('#transaction_table tbody tr').length
        if (transaction_type ==='No Ors'|| transaction_type ==='Accounts Payable' && count-1 <0){
            addDvToTable(result)
           
            // $("#bok").prop('required',true);
        }
        if (transaction_type==='No Ors' || transaction_type ==='Accounts Payable' ){
            $("#bok").show();
            $("#book").prop('required',true);
        }
        else{
            $("#bok").hide();
            $("#book").prop('required',false);
            
        }
    })




    $(document).ready(function() {



            // TRANSACTION TYPE
           var transaction = ["Single", "Multiple",
                "Accounts Payable",
                "Replacement to Stale Checks",
                'Replacement of Check Issued'
        ]
            $('#transaction').select2({
                data: transaction,
                placeholder: "Select transaction",

            })  
            // $("#transaction option:not(:selected)").attr('disabled',true)    
            // INSERT ANG DATA SA DATABASE
           


    })
    $('#save_data').submit(function(e) {
  

         e.preventDefault();


         $.ajax({
            url: window.location.pathname + '?r=dv-aucs/insert-dv',
            method: "POST",
            data: $('#save_data').serialize(),
            success: function(data) {
                var res=JSON.parse(data)
                if (res.isSuccess==true) {
                    swal({
                        title: "Success",
                        // text: "You will not be able to undo this action!",
                        type: "success",
                        timer: 3000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    }, function() {
                        window.location.href = window.location.pathname + '?r=dv-aucs/view&id='+res.id
                    });
                    $('#save_data')[0].reset();
                }
                else if(res.isSuccess==false){

                    swal({
                        title: "Error",
                        text: res.error,
                        type: "error",
                        timer: 6000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    });
                }
                else if(res.isSuccess=='exist'){
                    var dv_link = window.location.pathname + "?r=dv-aucs/view&id=" +res.id
                    $('#link').text('NAA NAY DV ANG ORS ')
            bbb = $(`<a type="button" href='`+ dv_link+`' >link here</a>`);
                        bbb.appendTo($("#link"));
                    swal({
                        title: "Error",
                        text: "Naa Nay DV",
                        type: "error",
                        timer: 6000,
                        button: false
                        // confirmButtonText: "Yes, delete it!",
                    });
                }
            }
        });

})

     



JS;
$this->registerJs($script);
?>