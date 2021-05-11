<!-- <link href="/dti-afms-2/frontend/web/js/select2.min.js" rel="stylesheet" />
<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" /> -->
<!-- <link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-exp.min.css">
<link rel="stylesheet" href="/dti-afms-2/frontend/web/spectre-0.5.9/dist/spectre-icons.min.css"> -->
<?php

use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>
<div class="test">


    <div id="container" class="container">

        <form name="add_data" id="add_data">
            <?php
            $q = 0;
            if (!empty($model)) {
                $q = $model;
            }
            echo " <input type='text' id='update_id' name='update_id'  value='$q' style='display:none'>";
            // echo " <input type='text' id='duplicate' name='duplicate'  value='$duplicate' style='display:none'>";
            ?>
            <div class="row">
                <div class="col-sm-3">
                    <h4 id="have_jev" style='color:red'></h4>
                </div>
                <div class="col-sm-3">
                    <label for="dv">Select DV Number</label>
                    <?php
                    echo Select2::widget([
                        'name' => "dv",
                        'id' => 'dv',
                        'value' => !empty($model->book_id) ? $model->book_id : '',
                        'data' => ArrayHelper::map((new \yii\db\Query())
                            ->select(['cash_disbursement.id as cash_id', 'dv_aucs.dv_number'])
                            ->from('cash_disbursement')
                            ->join('LEFT JOIN', 'dv_aucs', 'cash_disbursement.dv_aucs_id  = dv_aucs.id')
                            ->where('cash_disbursement.is_cancelled = :is_cancelled', ['is_cancelled' => false])
                            ->all(), "cash_id", "dv_number"),
                        'options' => [
                            'placeholder' => "Select DV Number",
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="total_disbursed"> Total Disbursed</label>
                    <h4 id="total_disbursed"></h4>
                </div>

            </div>
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

                    <select id="reference" name="reference" class="reference select" style="width: 100% ;margin-top:50px">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="row">


                <!-- <div class="col-sm-2" style="height:60x">
                    <label for="fund_cluster_code">Fund Cluster Code</label>
                    <select id="fund_cluster_code" name="fund_cluster_code" class="fund_cluster_code select" style="width: 100%; margin-top:50px" >
                        <option></option>
                    </select>
                </div> -->
                <div class="col-sm-3" style="height:60x">
                    <label for="book">Book</label>
                    <select id="book" name="book" class="book select" style="width: 100%; margin-top:50px" required>
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="r_center_id">Responisibility Center</label>
                    <select id="r_center_id" name="r_center_id" class="r_center_id select" style="width: 100%">
                        <option></option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="check_ada">Check ADA</label>

                    <select id="check_ada" name="check_ada" class="check_ada select" style="width: 100%">
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


                <div class="col-sm-3">
                    <label for="lddap">LDDAP</label>

                    <input type="text" name="lddap" id="lddap" placeholder="LDDAP">
                </div>
                <div class="col-sm-3">
                    <label for="dv_number">DV Number</label>

                    <input type="text" name="dv_number" id="dv_number" placeholder="DV NUMBER">
                </div>
                <div class="col-sm-3">
                    <label for="cadadr_number">CADADR </label>

                    <input type="text" name="cadadr_number" id="cadadr_number" placeholder="CADADR NUMBER">
                </div>
                <div class="col-sm-3">
                    <label for="ada_number">Check/ADA Number </label>
                    <input type="text" name="ada_number" id="ada_number" placeholder="Check/ADA Number ">
                </div>

            </div>
            <div class="row">
                <div class="col-sm-12">
                    <!-- <input type="text" name="particular" id="particular" placeholder="PARTICULAR" required> -->
                    <textarea name="particular" name="particular" id="particular" placeholder="PARTICULAR" required cols="151" rows="3"></textarea>
                </div>
            </div>

            <!-- BUTTON -->
            <!-- <div style="width: 100%; margin-bottom:50px;margin-right:25px;">

                <button type="button" class=" add-btn btn btn-success btn-md" style="float:right;margin-right:20px"><i class="glyphicon glyphicon-plus"></i></button>
                <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;" onClick="removeItem(0)"><i class="glyphicon glyphicon-minus"></i></button>
            </div> -->
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
                            <select id="chart-0" required name="chart_of_account_id[]" class="chart-of-account" onchange=isCurrent(this,0) style="width: 100%">
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
            <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />

        </form>


    </div>

    <style>
        textarea {
            max-width: 100%;
            width: 100%;
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

    <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" ></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" type="text/css" rel="stylesheet" /> -->
    <link href="/dti-afms-2/frontend/web/js/select2.min.js" />
    <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
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
        var books = [0];
        var dv = [];

        var vacant = 0;
        var i = 1;
        var x = [0];
        var update_id = undefined;

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
                    $('#isCurrent-' + i).val(data.result.current_noncurrent)
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

        function q(q) {
            // add()
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
                                <select id="chart-${i}" name="chart_of_account_id[]" required class="chart-of-accounts" onchange=isCurrent(this,${i}) style="width: 100%">
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
            // $.getJSON('/dti-afms-2/frontend/web/index.php?r=fund-cluster-code/get-all-cluster')
            // .then(function(data) {
            //     var array = []
            //     $.each(data, function(key, val) {
            //         array.push({
            //             id: val.id,
            //             text: val.name
            //         })
            //     })
            //     fund_clusters = array
            //     $('#fund_cluster_code').select2({
            //         data: fund_clusters,
            //         placeholder: "Select Fund Cluster Code",
            //         containerCssClass: function(e) {
            //             return $(e).attr('required') ? 'required' : '';
            //         }

            //     })
            // })
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
                            text: val.specific_cashflow
                        })
                    })
                    cashflow = array
                    $('#cashflow-0').select2({
                        data: cashflow,
                        placeholder: 'Select Cash Flow'
                    }).next().hide()


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
                    $('#isEquity-0').select2({
                        data: net_asset,
                        placeholder: 'Select Net Asset'

                    }).next().hide();


                })
            // GET ALL BOOKS WITH SELECT2 DROPDOWN
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=books/get-books')
                .then(function(data) {

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
                        placeholder: 'Select Book'

                    });


                })
            // GET ALL DV 
            // $.getJSON('/dti-afms-2/frontend/web/index.php?r=cash-disbursement/get-all-dv')
            //     .then(function(data) {

            //         var array = []
            //         $.each(data, function(key, val) {
            //             array.push({
            //                 id: val.cash_id,
            //                 text: val.dv_number
            //             })
            //         })
            //         dv = array
            //         $('#dv').select2({
            //             data: dv,
            //             placeholder: 'Select dv'

            //         });


            //     })


            // REFERENCE
            reference = ["CDJ", "CRJ", "GJ"]
            $('#reference').select2({
                data: reference,
                placeholder: "Select Reference",

            })


            // CHECK ADA NUMBER 
            ada_number = ['Non Cash', 'Check', 'ADA', ]
            $('#check_ada').select2({
                data: ada_number,
                // placeholder: 'Select CHECK/ADA'

            })


            // ADD ENTRY
            $('.add-btn').click(function() {
                add()
            })

            // SUBMIT DATA


            // INSERT DATA TO DATABASE
            $('#add_data').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=jev-preparation/insert-jev',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        //  alert(data);  
                        // //  $('#add_name')[0].reset();  
                        var res = JSON.parse(data)

                        if (res.isSuccess == "success") {
                            swal({
                                title: "Success",
                                // text: "You will not be able to undo this action!",
                                type: "success",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                                window.location.href = window.location.pathname + '?r=jev-preparation/view&id=' + res.id
                            });
                            $('#add_data')[0].reset();


                        } else if (res.isSuccess == false) {
                            swal({
                                title: res.error,
                                // text: "You will not be able to undo this action!",
                                type: "error",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                            });
                        }

                    }
                });
            })

        })

        function getTotal() {
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
            getTotal()
        })

        function thousands_separators(num) {

            var number = Number(Math.round(num + 'e2') + 'e-2')
            var num_parts = number.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");
        }

    </script>
</div>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?php

$script = <<< JS
 
        // ADD COMMA IN NUMBER

      function thousands_separators(num) {

        var number = Number(Math.round(num + 'e2') + 'e-2')
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
        }
    // POPULATE DATA ON DV CHANGE
    $("#dv").change(function(){
        $("#check_ada option:not(:selected)").attr("disabled", false)

        if ($('#dv').val()===''){
            // $('#add_data')[0].reset();
            // $('#book').val('').trigger('change') 
            // $('#payee').val('').trigger('change')
            // $('#r_center_id').val('').trigger('change')
            // $('#particular').val('').trigger('change')
            // $("#check_ada").val('').trigger('change')
            $('#total_disbursed').text('')
            $('#have_jev').text('')
            $('#submit').prop('disabled',false)
            $("#book option:not(:selected)").attr("disabled", false)
            $("#r_center_id option:not(:selected)").attr("disabled", false)
            $("#payee option:not(:selected)").attr("disabled", false)
            $("#check_ada option:not(:selected)").attr("disabled", false)
          }
      else{

        let eee = undefined;
        let bbb = undefined;
        $.ajax({
            type:"POST",
            url:window.location.pathname + "?r=cash-disbursement/get-dv",
            data:{cash_id:$('#dv').val()},
            success:function(data){
                var res = JSON.parse(data)
                $('#dv_number').val(res.results.dv_number)
                $('#book').val(res.results.book_id).trigger('change')
                $("#book option:not(:selected)").attr("disabled", true)
                $('#payee').val(res.results.payee_id).trigger('change')
                $("#payee option:not(:selected)").attr("disabled", true)
                $('#r_center_id').val(res.results.rc_id).trigger('change')
                $("#r_center_id option:not(:selected)").attr("disabled", true)
                $('#particular').val(res.results.particular)
                $('#ada_number').val(res.results.check_or_ada_no)
                $('#check_ada_date').val(res.results.issuance_date)
                $('#date').val(res.results.issuance_date)
                $('#total_disbursed').text(thousands_separators(res.results.total_disbursed))
                $('#reporting_period').val(res.results.reporting_period)
                if (update_id == 0) {
                    if (res.results.jev_id){

                    $('#have_jev').text('This DV Naa nay JEV ')
                    eee = window.location.pathname +"?r=jev-preparation/view&id="+res.results.jev_id
                    
                     bbb = $(`<a type="button" href='`+ eee+`' >link here</a>`);
                                 bbb.appendTo($("#have_jev"));

                    $('#submit').prop('disabled',true)
                  
                }else
                {
                    $('#have_jev').text('')
                    $('#submit').prop('disabled',false)
                }

                }
         
                if (res.results.mode_of_payment ==='ADA'){
                    $("#check_ada").val('ADA').trigger('change')
                }
                else {
                    
                    $("#check_ada").val('Check').trigger('change')
                }
                $("#check_ada option:not(:selected)").attr("disabled", true)


                var x=0
                var dv_accounting_entries = res.dv_accounting_entries;

                        for (x; x<dv_accounting_entries.length;x++){
                            $("#debit-"+x).val(dv_accounting_entries[x]['debit'])
                            $("#credit-"+x).val(dv_accounting_entries[x]['credit'])
                            var chart = dv_accounting_entries[x]['id'] +"-" +dv_accounting_entries[x]['object_code']+"-"+dv_accounting_entries[x]['lvl']
                            
                            var cashflow = dv_accounting_entries[x]['cashflow_id'];
                            var net_asset= dv_accounting_entries[x]['net_asset_equity_id'];
                            $("#chart-"+x).val(chart).trigger('change');
                            $("#isEquity-"+x).val(dv_accounting_entries[x]['net_asset_equity_id']).trigger('change');
                            $("#cashflow-"+x).val(cashflow).trigger('change');
                            if ($( "#cashflow-"+x ).length ){
                            }
                            else{
                            }
                            if (x < dv_accounting_entries.length -1){
                                add()
                            }
                        }
                
            }
        })
      }

    })
     $(document).ready(function() {
        update_id = $('#update_id').val();
        // KUNG NAAY SULOD ANG UPDATE ID KUHAON ANG IYANG MGA DATA
        if (update_id > 0) {
        $('#container').hide();

                $.ajax({
                    url: window.location.pathname + '?r=jev-preparation/update-jev',
                    method: 'POST',
                    data: {
                        update_id: update_id
                    },
                    beforeSend: function () {
                 
                    },
                    success: function(data) {

                        var jev = JSON.parse(data).jev_preparation
                        var jev_accounting_entries = JSON.parse(data).jev_accounting_entries
                        var d = "2020-12-01"
                        // document.querySelector("#reporting_period").value=jev['reporting_period']
                        $('#dv').val(jev['cash_disbursement_id']).trigger('change');
                        $('#reporting_period').val(jev['reporting_period'])
                        $('#check_ada_date').val(jev['check_ada_date'])
                        $('#particular').val(jev['explaination'])
                        $('#date').val(jev['date'])
                        $('#ada_number').val(jev['check_ada_number'])
                        $('#lddap').val(jev['lddap_number'])
                        $('#dv_number').val(jev['dv_number'])
                        $('#cadadr_number').val(jev['cadadr_serial_number'])
                        // $('#reference').val(jev['reference'])
                        $('#reference').val(jev['ref_number']).trigger('change');
                        $('#r_center_id').val(jev['responsibility_center_id']).trigger('change');
                        $('#check_ada').val(jev['check_ada']);
                        $('#check_ada').trigger('change');
                        $('#payee').val(jev['payee_id']);
                        $('#payee').trigger('change');
                        $('#book').val(jev['book_id']).trigger('change');
                        var x=0
                        // for (i; i < jev_accounting_entries.length;) {
                        // }
                        for (x; x<jev_accounting_entries.length;x++){
                            $("#debit-"+x).val(jev_accounting_entries[x]['debit'])
                            $("#credit-"+x).val(jev_accounting_entries[x]['credit'])
                            var chart = jev_accounting_entries[x]['id'] +"-" +jev_accounting_entries[x]['object_code']+"-"+jev_accounting_entries[x]['lvl']
                            
                            var cashflow = jev_accounting_entries[x]['cashflow_id'];
                            var net_asset= jev_accounting_entries[x]['net_asset_equity_id'];
                            $("#chart-"+x).val(chart).trigger('change');
                            $("#isEquity-"+x).val(jev_accounting_entries[x]['net_asset_equity_id']).trigger('change');
                            $("#cashflow-"+x).val(cashflow).trigger('change');
                            if ($( "#cashflow-"+x ).length ){
                            }
                            else{
                            }
                            if (x < jev_accounting_entries.length -1){
                                add()
                            }
                        }

                    },
                    complete: function(){
                        $('#container').show();
                        $('#loader').hide();
                        getTotal()
                    },
        
                })
            }

        })

    JS;
$this->registerJs($script);
?>