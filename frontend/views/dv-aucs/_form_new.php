<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use app\models\Books;
use app\models\FundClusterCode;
use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use GuzzleHttp\Psr7\Query;
use kartik\money\MaskMoney;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
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

                <div class="col-sm-3">
                    <label for="tracking_sheet">Tracking Sheet NO.</label>
                    <select id="tracking_sheet" name="tracking_sheet" class="tracking_sheet select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
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
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <select required id="payee" name="payee" class="payee select" style="width: 100%; margin-top:50px">
                        <option></option>
                    </select>
                </div>

                <div class="col-sm-3" style="height:60x">
                    <label for="transaction">Transaction Type</label>
                    <select required id="transaction" name="transaction_type" class="transaction select" style="width: 100%; margin-top:50px">
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

            <div class="container">
                <div id="form-0" class="accounting_entries" style="max-width: 100%;">
                    <!-- chart of accounts -->

                    <div class="row">
                        <div>
                            <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;" onClick="removeItem(0)"><i class="glyphicon glyphicon-minus"></i></button>
                            <button type="button" class=' btn btn-success btn-xs' style=" text-align: center; float:right;margin-right:5px" onClick="add()"><i class="glyphicon glyphicon-plus"></i></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="isCurrent">Current/NonCurrent </label>

                            <input type="text" name="isCurrent[]" placeholder="Current/NonCurrent" id="isCurrent-0" />
                        </div>
                        <div class="col-sm-3">
                            <label for="cadadr_number">Cash Flow </label>

                            <select id="cashflow-0" name="cash_flow_id[]" style="width: 100% ;display:none">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label for="cadadr_number">Changes in Net Asset and Equity </label>

                            <select id="isEquity-0" name="isEquity[]" style="width: 100% ;display:none">
                                <option></option>
                            </select>
                        </div>
                    </div>

                    <div class="row gap-1">

                        <div class="col-sm-5 ">

                            <div>
                                <select id="chart-0" name="chart_of_account_id[]" class="chart-of-account" onchange=isCurrent(this,0) style="width: 100%">
                                    <option></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <input type="text" id="debit-0" name="debit[]" class="debit" placeholder="Debit">
                        </div>
                        <div class="col-sm-3">
                            <input type="text" id="credit-0" name="credit[]" class="credit" placeholder="Credit">
                        </div>
                    </div>
                </div>
            </div>
            <div class="total row">

                <div class="col-sm-3 col-md-offset-5">
                    <!-- <div class="form-group">
        <label for="exampleInputEmail1">TOTAL DEBIT</label>
        <input disabled type="text" style="background-color:white" class="form-control" id="d_total"  aria-describedby="emailHelp" placeholder="Total Dedit">
    </div> -->
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
            <?= GridView::widget([
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
                            $query = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount)as total,
                            process_ors.id as ors_id
                            FROM process_ors,raouds,raoud_entries
                            where process_ors.id = raouds.process_ors_id
                            AND raouds.id=raoud_entries.raoud_id
                            AND process_ors.id= :ors_id
                            GROUP BY process_ors.id")
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
            <button type="submit" class="btn btn-primary" name="submit" id="submit" style="width: 100%;"> ADD</button>
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

<!-- <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script src="/afms/frontend/web/js/scripts.js" type="text/javascript"></script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" type="text/css" rel="stylesheet" /> -->


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


    function add() {

        var latest = Math.max.apply(null, x)
        $(`#form-${latest}`)
            .after(`<div id="form-${i}" style="max-width:100%;border: 1px solid gray;width:100%; padding: 2rem; margin-top: 1rem;background-color:white;border-radius:5px" class="control-group input-group" class="accounting_entries">
                <!-- chart of accounts -->
                <div class="row"  >
                    <div>
                        <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;" onClick="removeItem(${i})"><i class="glyphicon glyphicon-minus"></i></button>
                        <button type="button" class=' btn btn-success btn-xs' style=" text-align: center; float:right;margin-right:5px" onClick="add()"><i class="glyphicon glyphicon-plus"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="isCurrent">Current/NonCurrent </label>
                        <input type="text" name="isCurrent[]" id="isCurrent-${i}" placeholder="Current/NonCurrent"/>
                    </div>
                    <div class="col-sm-3" style="">
                        <label for="isCurrent">Cash Flow </label>
                        <select id="cashflow-${i}" name="cash_flow_id[]" style="width: 100% ;display:none" >
                            <option ></option>
                        </select>
                    </div>
                    <div class="col-sm-3" >
                        <label for="isCurrent">Changes in Net Asset and Equity </label>
                            <select id="isEquity-${i}" name="isEquity[]" style="width: 100% ;display:none" >
                                <option ></option>
                            </select>
                    </div>
                </div>
        
                <div class="row gap-1">
                        <div class="col-sm-5 ">
                            <select id="chart-${i}" name="chart_of_account_id[]"  class="chart-of-accounts" onchange=isCurrent(this,${i}) style="width: 100%">
                            <option></option>
                            </select>
                    </div>

                    <div class="col-sm-3">
                        <div >  <input type="text" id="debit-${i}"  name="debit[]" class="debit"  placeholder="Debit"></div>
                    </div>
                    <div class="col-sm-3">
                        <div >  <input type="text"   id="credit-${i}" name="credit[]" class="credit" placeholder="Credit"></div>
                    </div>
                </div>

                    
            </div>
            `)
        $(`#chart-${i}`).select2({
            data: accounts,
            placeholder: "Select Chart of Account",

        });
        $(`#cashflow-${i}`).select2({
            data: cashflow,
            placeholder: 'Select Cash Flow'
        }).next().hide()
        $(`#isEquity-${i}`).select2({
            data: net_asset,
            placeholder: 'Select Net Asset'

        }).next().hide();
        var deb = document.getElementsByName('debit[]');
        // arr_form.splice(latest, 0, latest + 1)
        // deb[1].value = 123
        x.push(i)

        i++

    }
    $('.add-btn').click(function() {
        add()
        getTotal()
    })

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

    $(document).ready(function() {
        // $("#payee ").select2({'readonly'});
        // $("#particular").prop({disabled:'readonly'});
        $("#bok").hide();


        getPayee().then(function(data) {
            var array = []
            $.each(data, function(key, val) {
                array.push({
                    id: val.id,
                    text: val.account_name
                })
                payee = array
                $('#payee').select2({
                    data: payee,
                    placeholder: 'Select Payee'
                })
            })
        })
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
</script>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php SweetAlertAsset::register($this); ?>
<?php

$script = <<< JS
        var reporting_period = '';
        var transactions=[];
        var nature_of_transaction=[];
        var reference=[];
        var mrd_classification=[];
        var books=[];
        var bbb=undefined;

        $("#tracking_sheet").change(function()  {
            // console.log(sheet)
            var transaction_type ='';
            var payee = 0;
            var particular =''
            var ors_id = []
            var x={}
            var pay= []
            var y =1
            var t_type=[]
            // $('#transaction_table >tbody').html('')
            // $('#transaction_table tbody').html()=''
            $.each(sheet,function(key,val){
                if (val.id ==$('#tracking_sheet').val()){
                    transaction_type =val.transaction_type
                    payee = val.payee_id
                    particular =val.particular
                    ors_id.push(val.process_ors_id) 
                    x= '{'+val.process_ors_id+':0}'
                    y = val.process_ors_id
                    pay.push({
                        id:val.p_id,
                        text:val.account_name
                    })
                    t_type.push(transaction_type)
                    return false
                }
            })
            // $('#payee_id').select2({
            //     data:pay,
            // })
            $("#payee").val(payee).trigger('change')
            // $(`#transaction option:not(:selected)`).attr('disabled', false);
            // $('#transaction').select2({
            //     data:t_type
            // })
            $("#transaction").val(transaction_type).trigger('change')
            // $(`#transaction option:not(:selected)`).attr('disabled', true);
            $("#payee").val(payee).trigger('change')
            $("#particular").val(particular).trigger('change')
            // console.log(x)
            // if (transaction_type =='Single'){
            //     console.log(ors_id)
            //         $.ajax({
            //         url: window.location.pathname + '?r=dv-aucs/get-dv',
            //         method: "POST",
            //         data:{selection:ors_id,
            //         transaction_type:transaction_type,
            //         amount_disbursed:x,
            //         vat_nonvat:x,
            //         ewt_goods_services:x,
            //         compensation:x,
            //         other_trust_liabilities:x,


                
            //     },
            //         success: function(data) {
            //             var res = JSON.parse(data)
            //             // console.log(data)
            //             if (res.isSuccess) {

            //                 addDvToTable(res.results)
            //             } else {
            //                 swal({
            //                     title: "Error",
            //                     text: res.error,
            //                     type: "error",
            //                     timer: 6000,
            //                     button: false
            //                     // confirmButtonText: "Yes, delete it!",
            //                 });
            //             }

            //         }
            //     });
            // }
       
        })


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
        if (transaction_type ==='No Ors' && count-1 <0){
            addDvToTable(result)
           
            // $("#bok").prop('required',true);
        }
        if (transaction_type==='No Ors'){
            $("#bok").show();
            $("#book").prop('required',true);
        }
        else{
            $("#bok").hide();
            $("#book").prop('required',false);
            
        }
    })
    // function chart(){
        
    //     return  $.getJSON('/afms/frontend/web/index.php?r=transaction/get-all-transaction')
    //     .then(function (data) {

    //         var array = []
    //         $.each(data, function (key, val) {
    //             array.push({
    //                 id: val.id,
    //                 text: val.tracking_number
    //             })
    //         })
    //         transaction = array
    //         $('#transaction_id').select2({
    //             data: transaction,
    //             placeholder: "Select Transaction",

    //         })

    //     });
    // }
    $.when(getChartOfAccounts() ).done(function(chart){
            var array = []
            // console.log(chart)
            $.each(chart, function(key, val) {
                array.push({
                    id: val.object_code,
                    text: val.object_code + ' ' + val.account_title
                })

            })
            accounts = array
            $("#chart-0").select2({
                data: accounts,
                placeholder: 'Select Account'
            })
        var update_id= $('#update_id').val()
        if (update_id>0){
            $.ajax({
                url:window.location.pathname + "?r=dv-aucs/update-dv",
                type:"POST",
                data:{dv_id:update_id},
                success:function(data){

                    var res = JSON.parse(data)
                    console.log(res.result)
                    var transaction_type=res.result[0]['transaction_type']
                    var type='';

                        if (!transaction_type){
                            if (res.result.length >1){
                                type='Multiple'
                            }
                            else if(res.result.length ===1){
                                type='Single'
                            }
                            else if(res.result.length ===0){
                                type='No Ors'
                            }
                        }
                        else{
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
                 
                    if (res.result[0]['tracking_sheet_id'] ==null){
                     $('#transaction').val(type).trigger('change')
                                          
                    $("#particular").val(res.result[0]['particular'])
                    $("#payee").val(res.result[0]['payee_id']).trigger('change');
                        
                    }
                    else{
                        $('#tracking_sheet').val(res.result[0]['tracking_sheet_id']).trigger('change')
                    }

                    var x=0
               
                        var dv_accounting_entries = res.dv_accounting_entries;
                        console.log(dv_accounting_entries)
                        for (x; x<res.dv_accounting_entries.length;x++){
                            $("#debit-"+x).val(dv_accounting_entries[x]['debit'])
                            $("#credit-"+x).val(dv_accounting_entries[x]['credit'])
                            var chart = dv_accounting_entries[x]['id'] +"-" +dv_accounting_entries[x]['object_code']+"-"+dv_accounting_entries[x]['lvl']
                            
                            var cashflow = dv_accounting_entries[x]['cashflow_id'];
                            var net_asset= dv_accounting_entries[x]['net_asset_equity_id'];
                            $("#chart-"+x).val(dv_accounting_entries[x]['object_code']).trigger('change');
                            $("#isEquity-"+x).val(dv_accounting_entries[x]['net_asset_equity_id']).trigger('change');
                            $("#cashflow-"+x).val(cashflow).trigger('change');
                            if ($( "#cashflow-"+x ).length ){
                            }
                            else{
                            }
                            if (x < res.dv_accounting_entries.length -1){
                                add()
                            }
                        }
            getDebitCreditTotal()
                    
                }
            })
        }
    });


    $(document).ready(function() {

        getAllTrackingSheet().then(function(data) {

        var array = []
        sheet = data
        $.each(data, function(key, val) {
            array.push({
                id: val.id,
                text: val.tracking_number
            })
        })
        tracking_sheet = array
        $('#tracking_sheet').select2({
            data: tracking_sheet,
            placeholder: 'Select Tracking Sheet'
        })
        });


        // CHART OF ACCOUNTS

        // $.getJSON('/dti-afms-2/frontend/web/index.php?r=chart-of-accounts/get-general-ledger')
        //         .then(function(data) {
        //             var array = []
        //             $.each(data, function(key, val) {
        //                 array.push({
        //                     id: val.id,
        //                     text: val.object_code + ' ' + val.title
        //                 })
        //             })
        //             accounts = array
       
        //         })
                // GET ALL MRD CLASSIFICATIOn

            // TRANSACTION TYPE
           var transaction = ["Single", "Multiple","No Ors"]
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