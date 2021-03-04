<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->

<?php

use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
?>
<div class="test">

    <div id="container" class="container">

        <form name="add_data" id="add_data">

            <div class="row">

                <div class="col-sm-3">
                    <label for="check_ada_date">Check/ADA Date</label>

                    <?php
                    echo DatePicker::widget([
                        'name' => 'check_ada_date',
                        'id' => 'check_ada_date',
                        // 'value' => '12/31/2010',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="date">Date</label>

                    <?php
                    echo DatePicker::widget([
                        'name' => 'date',
                        'id' => 'date',
                        // 'value' => '12/31/2010',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ],
                        'options' => ['required' => true],
                    ]);
                    ?>
                </div>
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
                    <label for="reference">Reference</label>

                    <select id="reference" name="reference" class="reference select" style="width: 100%" required>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="row">


                <div class="col-sm-3" style="height:60x">
                    <label for="fund_cluster_code">Func Cluster Code</label>
                    <select id="fund_cluster_code" name="fund_cluster_code" class="fund_cluster_code select" style="width: 100%;">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="r_center_id">Responisibility Center</label>
                    <select id="r_center_id" name="r_center" class="r_center_id select" style="width: 100%">
                        <option></option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="check_ada">Check ADA</label>

                    <select id="check_ada" name="check_ada" class="check_ada select" style="width: 100%">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="payee">Payee</label>
                    <select id="payee" name="payee_id" class="payee select" style="width: 100%">
                        <option></option>
                    </select>
                </div>
            </div>

            <div class=" row">


                <div class="col-sm-4">
                    <label for="lddap">LDDAP</label>

                    <input type="text" name="lddap" placeholder="LDDAP">
                </div>
                <div class="col-sm-4">
                    <label for="dv_number">DV Number</label>

                    <input type="text" name="dv_number" placeholder="DV NUMBER">
                </div>
                <div class="col-sm-4">
                    <label for="cadadr_number">CADADR </label>

                    <input type="text" name="cadadr_number" placeholder="CADADR NUMBER">
                </div>

            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="text" name="particular" placeholder="PARTICULAR" required>
                </div>
            </div>
            <!-- BUTTON -->
            <div style="width: 100%; margin-bottom:50px;margin-right:25px;">
                <button type="button" id="add-btn" class="btn btn-success btn-md" style="float:right;margin-right:20px"><i class="glyphicon glyphicon-plus"></i></button>
            </div>
            <div id="form-0" class="accounting_entries">
                <!-- chart of accounts -->
                <div class="row">
                    <div class="col-sm-4">
                        <label for="isCurrent">Current/NonCurrent </label>

                        <input type="text" name="isCurrent[]" placeholder="isCurrent" id="isCurrent-0" />
                    </div>
                    <div class="col-sm-3" style="">
                        <label for="cadadr_number">Cash Flow </label>

                        <select id="cash_flow_id-0" name="cash_flow_id[]" style="width: 100% ;display:none">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="cadadr_number">Changes in Net Asset and Equity </label>

                        <select id="isEquity-0" name="isEquity[]" style="width: 100% ;display:none">
                            <option value="null"></option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; gap: 2rem; margin-top: 1rem">
                    <div style="width: 500px">
                        <select id="chart-0" required name="chart_of_account_id[]" class="chart-of-account" style="width: 100%" onchange="isCurrent(this,0)">
                            <option></option>
                        </select>
                    </div>
                    <!-- debit -->
                    <input type="text" name="debit[]" placeholder="Debit">

                    <!-- credit -->
                    <input type="text" name="credit[]" placeholder="Credit">



                </div>
            </div>
            <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />

        </form>

    </div>
    <style>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
    <script>
        <?php SweetAlertAsset::register($this); ?>
        // global variable
        var accounts = [];
        var fund_clusters = [];
        var r_center = [];
        var payee = [];
        var cashflow = [];
        var net_asset = [];
        var arr_form = [0];
        var vacant = 0;
        var i = 1;

        function removeItem(index) {
            // $(`#form-${index}`).remove();
            // arr_form.splice(index, 1);
            // vacant = index
            // $('#form' + index + '').remove();

            document.getElementById(`form-${index}`).remove()
            // console.log(index)

        }

        function isCurrent(index, i) {

            // var chart_id = document.getElementById('chart-0').val()
            // console.log(index)
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=jev-preparation/is-current',
                data: {
                    chart_id: index.value
                },
                dataType: 'json',
                success: function(data) {
                    $('#isCurrent-' + i).val(data.result.current_noncurrent)
                    // data.isCashEquivalent ? : $('#cash_flow_id-' + i).hide()
                    data.isEquity ? $('#isEquity-' + i).show() : $('#isEquity-' + i).hide()
                    // console.log(data)
                    if (data.isCashEquivalent == true) {
                        $('#cash_flow_id-' + i).select2({
                            data: cashflow,
                            placeholder: 'Select Cash Flow'
                        })

                        $('#cash_flow_id-' + i).show()
                    } else {

                        $('#cash_flow_id-' + i).val(null).trigger('change');
                        document.getElementById('isEquity-' + i).value = 'null'
                        $('#cash_flow_id-' + i).select2().next().hide();


                    }
                    if (data.isEquity == true) {
                        $('#isEquity-' + i).select2({
                            data: net_asset,
                            placeholder: 'Net Asset'
                        })

                        $('#isEquity-' + i).show()
                    } else {

                        $('#isEquity-' + i).val(null).trigger('change');
                        document.getElementById('isEquity-' + i).value = 'null'
                        $('#isEquity-' + i).select2().next().hide();


                    }
                }
            })
        }



        $(document).ready(function() {

            // GET ALL CHART OF accounts
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=chart-of-accounts/get-all-account')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id + '-' + val.object_code + '-' + val.lvl,
                            text: val.object_code + ' ' + val.title
                        })
                    })
                    accounts = array
                    $('#chart-0').select2({

                        data: accounts,
                        placeholder: "Select Chart of Account",

                    })
                })

            // GET ALL FUND CLUSTER CODES
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=fund-cluster-code/get-all-cluster')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    fund_clusters = array
                    $('#fund_cluster_code').select2({
                        data: fund_clusters,
                        placeholder: "Select a state",
                        containerCssClass: function(e) {
                            return $(e).attr('required') ? 'required' : '';
                        }

                    })
                })
            // GET ALL RESPONSIBILITY CENTERS
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=responsibility-center/get-responsibility-center')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    r_center = array
                    $('#r_center_id').select2({
                        data: r_center,
                        placeholder: 'Select Responsibility Center'
                    })
                })
            // GET ALL PAYEE
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=payee/get-payee')

                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.account_name
                        })
                    })
                    payee = array
                    $('#payee').select2({
                        data: payee,
                        placeholder: "Select Payee",

                    })

                })

            // GET ALL CASHFLOW
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=cash-flow/get-all-cashflow')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.major_cashflow
                        })
                    })
                    cashflow = array


                })
            // GET ALL NETASSETS
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=net-asset-equity/get-all-netasset')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.specific_change
                        })
                    })
                    net_asset = array


                })
            // REFERENCE
            reference = ["ADADJ", "CDJ", "CKDJ", "CRJ", "GJ"]
            $('#reference').select2({
                data: reference,
                placeholder: "Select Reference",

            })


            // CHECK ADA NUMBER 
            ada_number = ['Check', 'ADA', 'Non Cash']
            $('#check_ada').select2({
                data: ada_number,
                placeholder: 'Select CHECK/ADA'

            })



            $('#add-btn').click(function() {
                var latest = arr_form[arr_form.length - 1]
                // console.log('index: '+latest)
                $(`#form-${latest}`)
                    .after(`<div id="form-${i}" style="border: 1px solid gray;width:100%; padding: 2rem; margin-top: 1rem;background-color:white;border-radius:5px" class="control-group input-group" class="accounting_entries">
                    <!-- chart of accounts -->
                    <div class="row"  >
                        <div>
                            <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;" onClick="removeItem(${i})"><i class="glyphicon glyphicon-minus"></i></button>
                        
                            </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-4">
                    <label for="isCurrent">Current/NonCurrent </label>

                    <input type="text" name="isCurrent[]" id="isCurrent-${i}" placeholder="isCurrent"/>
                    </div>
                    <div class="col-sm-3" style="">
                    <label for="isCurrent">Cash Flow </label>

                        <select id="cash_flow_id-${i}" name="cash_flow_id[]" style="width: 100% ;display:none" >
                        <option></option>
                        </select>
                    </div>
                    <div class="col-sm-3" >
                    <label for="isCurrent">Changes in Net Asset and Equity </label>

                            <select id="isEquity-${i}" name="isEquity[]" style="width: 100% ;display:none" >
                            <option value="null"></option>
                            </select>
                    </div>
             
                     </div>
                    <div style="display: flex; gap: 2rem; margin-top: 1rem">
                        <div style="width: 500px">
                            <select id="chart-${i}" name="chart_of_account_id[]" required class="chart-of-accounts" onchange=isCurrent(this,${i}) style="width: 100%">
                            <option></option>
                            </select>
                        </div>
                            <!-- debit -->
                        <div style="flex-grow: 1;">  <input type="text" style=" width: 100%;font-size: 15px;padding: 5px;border-radius: 5px;border: 1px solid black;" name="debit[]" placeholder="Debit"></div>
                        <!-- credit -->
                        <div style="flex-grow: 1;">  <input type="text" name="credit[]" placeholder="Debit"></div>
                        
                        </div>
                </div>`)
                $(`#chart-${i}`).select2({
                    data: accounts,
                    placeholder: "Select Chart of Account",

                })

                // arr_form.splice(latest, 0, latest + 1)
                i++
                // console.log(i)

            })

            // SUBMIT DATA
            // $('#submit').click(function() {
            //     $.ajax({
            //         url: window.location.pathname + '?r=jev-preparation/insert-jev',
            //         method: "POST",
            //         data: $('#add_data').serialize(),
            //         success: function(data) {
            //             //  alert(data);  
            //             //  $('#add_name')[0].reset();  
            //             console.log(data)
            //             $('#add_data')[0].reset();
            //         }
            //     });
            // });

            // function insertData() {

            // }
            $('#add_data').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=jev-preparation/insert-jev',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        //  alert(data);  
                        //  $('#add_name')[0].reset();  
                        var res = JSON.parse(data)
                        // console.log(JSON.parse(data))

                        if (res == "success") {
                            console.log(data)
                            swal({
                                title: "Success",
                                // text: "You will not be able to undo this action!",
                                type: "success",
                                timer:3000,
                                button:false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                               console.log('qwe')
                               window.location.href = window.location.pathname + '?r=jev-preparation/create'
                            });

                        } else {
                            // var date = JSON.parse(data).date;
                            // var reporting_period = JSON.parse(data).reporting_period;
                            console.log(reporting_period)
                            // swal({
                            //     title:date?+date:''+' ' +reporting_period?reporting_period:'',
                            //     type: "error",
                            //     timer: 5000,
                            //     closeOnConfirm: false,
                            //     closeOnCancel: false
                            // })
                        }
                        // console.log(JSON.parse(data).)
                        $('#add_data')[0].reset();
                        // setTimeout(function () {
                        //     window.location.href = window.location.pathname + '?r=jev-preparation/create'
                        // }, 300);

                    }
                });
            })

        })
    </script>
</div>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::className()]]);
?>

<?php

$script = <<< JS
  
    JS;
$this->registerJs($script);
?>