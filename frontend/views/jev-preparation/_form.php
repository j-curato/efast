<script src="/afms/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<!-- <script src="/afms/frontend/web/js/scripts.js" type="text/javascript"></script> -->
<link href="/afms/frontend/web/js/select2.min.js" />
<link href="/afms/frontend/web/css/select2.min.css" rel="stylesheet" />
<?php

use kartik\date\DatePicker;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

?>
<div class="test" style="background-color:white;border:1px solid black;padding:20px">


    <!-- <div id="container" class="container"> -->

    <form name="add_data" id="add_data">
        <?php
        $q = 0;
        if (!empty($model)) {
            $q = $model;
        }
        echo " <input type='text' id='update_id' name='update_id'  value='$q' style='display:none'>";
        if (!empty($type)) {
            echo " <input type='text' id='type' name='type'  value='$type' style='display:none'>";
        }
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
                        'style' => "padding:20px"
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
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ],
                    'options' => [
                     
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
                <textarea name="particular" name="particular" id="particular" placeholder="PARTICULAR" required cols="151" rows="3"></textarea>
            </div>
        </div>

        <!-- BUTTON -->

        <div id="form-0" class="accounting_entries" >
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


    <!-- </div> -->

    <style>
        textarea {
            max-width: 100%;
            width: 100%;
        }

        .select {
            width: 500px;
            height: 2rem;
        }

        .accounting_entries {
            max-width: 98%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            margin-bottom: 10px;
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


    <script>
        <?php SweetAlertAsset::register($this); ?>
        // global variable

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
        var accounting_entries = [0];
        var update_id = undefined;

        function removeItem(index) {


            document.getElementById(`form-${index}`).remove()
            for (var y = 0; y < accounting_entries.length; y++) {
                if (accounting_entries[y] === index) {
                    delete accounting_entries[y]
                    accounting_entries.splice(y, 1)
                }
            }
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

            var latest = Math.max.apply(null, accounting_entries)
            $(`#form-${latest}`)
                .after(`<div id="form-${i}" class="accounting_entries">
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
            accounting_entries.push(i)

            i++



        }


        var accounts = []

        $(document).ready(function() {

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
                            }, function() {});
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
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
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
         
            $('#total_disbursed').text('')
            $('#have_jev').text('')
            $('#submit').prop('disabled',false)
            $('#reference').prop('disabled',false)
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
                    console.log(res)
                    $('#dv_number').val(res.results.dv_number)
                    $('#book').val(res.results.book_id).trigger('change')
                    $('#reference').prop('disabled',true)
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
            
                    if (res.results.mode_of_payment.toLowerCase() =='ada'){
                        $("#check_ada").val('ADA').trigger('change')
                        $("#ada_number").val(res.results.ada_number).trigger('change')

                    }
                    else {
                        
                        $("#check_ada").val('Check').trigger('change')
                        $("#ada_number").val(res.results.check_or_ada_no).trigger('change')

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
                                $("#chart-"+x).val(dv_accounting_entries[x]['object_code']).trigger('change');
                         
                                $("#isEquity-"+x).val(dv_accounting_entries[x]['net_asset_equity_id']).trigger('change');
                                $("#cashflow-"+x).val(cashflow).trigger('change');
                                if ($( "#cashflow-"+x ).length ){
                                }
                                else{
                                }
                                if (x < dv_accounting_entries.length -1){
                                    add()
                                }
                                getTotal()
                            }
                        // for(var ww = ){

                        // }
                    
                }
            })
          
        }
      

    })

     $(document).ready(function() {
        update_id = $('#update_id').val();
        var type = $('#type').val();
        // KUNG NAAY SULOD ANG UPDATE ID KUHAON ANG IYANG MGA DATA
        if (update_id > 0 && type=='update' ) {
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
                        if ($('#dv').val() ==''){

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
                            $("#chart-"+x).val(jev_accounting_entries[x]['object_code']).trigger('change');
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
                    }
               

                    },
                    complete: function(){
                        $('#container').show();
                        $('#loader').hide();
                        getTotal()
                    },
        
                })
            }

            if (update_id >0 && type=='cdr'){
               $.ajax({
                   type:"POST",
                   url:window.location.pathname + "?r=report/get-cdr",
                   data:{id:update_id},
                   success:function(data){
                    var x=0 
                    var debit_value=0
                       var d= JSON.parse(data)
                       var res=d.result
                       var total_vat=0
                       var total_expanded=0
                       var total_gross=0
                       for (x; x<res.length;x++){
                            debit_value = res[x]['debit'] 
                            $("#debit-"+x).val(debit_value)
                            // $("#credit-"+x).val(jev_accounting_entries[x]['credit'])
                            // var chart = jev_accounting_entries[x]['id'] +"-" +jev_accounting_entries[x]['object_code']+"-"+jev_accounting_entries[x]['lvl']
                            
                            // var cashflow = jev_accounting_entries[x]['cashflow_id'];
                            // var net_asset= jev_accounting_entries[x]['net_asset_equity_id'];
                            $("#chart-"+x).val(res[x]['code']).trigger('change');
                            // $("#isEquity-"+x).val(jev_accounting_entries[x]['net_asset_equity_id']).trigger('change');
                            // $("#cashflow-"+x).val(cashflow).trigger('change');
                            if ($( "#cashflow-"+x ).length ){
                            }
                            else{
                            }
                            if (x < res.length -1){
                                add()
                            }
                            total_gross+=parseFloat(res[x]['total_withdrawals'])
                            total_vat+=parseFloat(res[x]['total_vat'])
                            total_expanded+=parseFloat(res[x]['total_expanded'])
                        }
                        add()
                        $("#credit-"+x).val(parseFloat(total_gross).toFixed(2))
                        // $("#chart-"+x).val(d.vat['id'] + '-'+d.vat['object_code']+'-'+2).trigger('change');
                        x++
                        add()
                        $("#credit-"+x).val(parseFloat(total_vat).toFixed(2))
                        $("#chart-"+x).val(d.expanded['id'] + '-'+d.expanded['object_code']+'-'+2).trigger('change');
                        x++
                        add()
                        $("#credit-"+x).val(parseFloat(total_expanded).toFixed(2))
                        $("#chart-"+x).val(d.vat['id'] + '-'+d.vat['object_code']+'-'+2).trigger('change');
                        x++
                        getTotal()
                   }
               })
            }
        })

    

JS;
$this->registerJs($script);
?>