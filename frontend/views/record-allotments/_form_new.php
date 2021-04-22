<!-- <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" /> -->
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
            <?php
            $q = 0;
            if (!empty($model)) {
                $q = $model;
            }
            echo " <input type='text' id='update_id' name='update_id'  value='$q' style='display:none;'>";
            ?>
            <div class="row">

                <div class="col-sm-3">
                    <label for="date_issued">Date Issued</label>

                    <?php
                    echo DatePicker::widget([
                        'name' => 'date_issued',
                        'id' => 'date_issued',
                        // 'value' => '12/31/2010',
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-sm-3">
                    <label for="valid_until">Valid Until</label>
                    <?php
                    echo DatePicker::widget([
                        'name' => 'valid_until',
                        'id' => 'valid_until',
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
                        // 'options' => ['required' => true],
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

                </div>
            </div>
            <div class="row">

                <div class="col-sm-3" style="height:60x">
                    <label for="fund_cluster_code_id">Fund Cluster Code</label>
                    <select id="fund_cluster_code_id" name="fund_cluster_code_id" class="fund_cluster_code_id select" style="width: 100%; margin-top:50px" required>
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="document_recieve">Document Recieve</label>
                    <select id="document_recieve" name="document_recieve" class="document_recieve select" style="width: 100%">
                        <option></option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="financing_source_code">Financing Source Codes</label>
                    <select id="financing_source_code" name="financing_source_code" class="financing_source_code select" style="width: 100%">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="authorization_code">Authorization Codes</label>
                    <select id="authorization_code" name="authorization_code" class="authorization_code select" style="width: 100%">
                        <option></option>
                    </select>
                </div>
            </div>

            <div class=" row">
                <div class="col-sm-3">
                    <label for="mfo_pap_code">MFO/PAP Code</label>
                    <select id="mfo_pap_code" name="mfo_pap_code" class="mfo_pap_code select" style="width: 100%">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="fund_source">Fund Source</label>
                    <select id="fund_source" name="fund_source" class="fund_source select" style="width: 100%">
                        <option></option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="fund_classification_code">Fund Classification Code</label>
                    <input type="text" id="fund_classification_code" name="fund_classification_code" placeholder="Fund Classification Code">
                </div>
                <div class="col-sm-3">
                    <label for="book">Book</label>
                    <select id="book" name="book" class="book select" style="width: 100%" required>
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <textarea name="particular" id="particular" placeholder="PARTICULAR" cols="151" rows="3" style="max-width:100%"></textarea>
                </div>
            </div>

            <div id="form-0" class="accounting_entries">
                <!-- chart of accounts -->

                <div class="row">
                    <!--ADD AND REMOVE  BUTTON -->

                    <div>
                        <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;" onClick="removeItem(0)"><i class="glyphicon glyphicon-minus"></i></button>
                        <button type="button" class=' btn btn-success btn-xs' style=" text-align: center; float:right;margin-right:5px" onClick="add()"><i class="glyphicon glyphicon-plus"></i></button>
                    </div>
                </div>

                <div class="row gap-1">
                    <div class="col-sm-5 ">
                        <div>
                            <select id="chart-0" required name="chart_of_account_id[]" class="chart-of-account" style="width: 100%">
                                <option></option>
                            </select>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <input type="text" id="amount-0" name="amount[]" class="amount" placeholder="amount">
                    </div>
                </div>
            </div>
            <!-- total row -->
            <input type="submit" name="submit" id="submit" class="btn btn-info submit-btn" value="Submit" />

        </form>

    </div>
    <style>
        .select {
            width: 500px;
            height: 2rem;
        }

        #submit {
            margin-top: 10px;

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
            width: 96%;
            margin-left: auto;
            margin-right: auto;
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
    <link href="/dti-afms-2/frontend/web/js/select2.min.js" />
    <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

    <!-- <script src="/dti-afms-2/frontend/web/js/select2.min.js"></script> -->
    <script>
        <?php SweetAlertAsset::register($this); ?>
        // global variable
        var accounts = [];
        var fund_clusters = [];
        var document_recieve = [];
        var financing_source_code = [];
        var authorization_code = [];
        var fund_source = [];
        var mfo_pap_code = [];
        var arr_form = [0];
        var books = [0];
        var vacant = 0;
        var i = 1;
        var x = [0];

        var update_id = undefined;

        function removeItem(index) {
            document.getElementById(`form-${index}`).remove()
            // console.log(index)
            for (var y = 0; y < x.length; y++) {
                if (x[y] === index) {
                    delete x[y]
                    x.splice(y, 1)
                }
            }
            console.log(x, Math.max.apply(null, x))
        }




        function add() {

            var latest = Math.max.apply(null, x)
            // console.log('index: '+latest)
            $(`#form-${latest}`)
                .after(`<div id="form-${i}" style="border: 1px solid gray;width:100%; padding: 2rem; margin-top: 1rem;background-color:white;border-radius:5px;width: 96%;
            margin-left: auto;
            margin-right: auto;" class="control-group input-group" class="accounting_entries">
                    <!-- chart of accounts -->
                    <div class="row"  >
                        <div>
                            <button type="button" class='remove btn btn-danger btn-xs' style=" text-align: center; float:right;" onClick="removeItem(${i})"><i class="glyphicon glyphicon-minus"></i></button>
                            <button type="button" class=' btn btn-success btn-xs' style=" text-align: center; float:right;margin-right:5px" onClick="add()"><i class="glyphicon glyphicon-plus"></i></button>
                        </div>
                    </div>
                     <div class="row gap-1">
                            <div class="col-sm-5 ">
                                <select id="chart-${i}" name="chart_of_account_id[]" required class="chart-of-accounts" style="width: 100%">
                                <option></option>
                                </select>
                        </div>

                    
                        <div class="col-sm-3">
                            <div >  <input type="text"   id="amount-${i}" name="amount[]" class="amount" placeholder="amount"></div>
                        </div>
                    </div>

                        
                </div>
                `)
            $(`#chart-${i}`).select2({
                data: accounts,
                placeholder: "Select Chart of Account",

            });

            var deb = document.getElementsByName('debit[]');
            // arr_form.splice(latest, 0, latest + 1)
            // deb[1].value = 123
            // console.log(deb[1].value)
            x.push(i)

            i++
            // console.log(i)

        }
        $(document).ready(function() {



            // GET ALL DOCUMENT RECIEVES
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=document-recieve/get-document-recieves')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    document_recieve = array
                    $('#document_recieve').select2({
                        data: document_recieve,

                        placeholder: 'Select Document Recieve'
                    })
                })


            // GET ALL AUTHORIZATION CODES
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=authorization-code/get-authorization-codes')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name +' - '+ val.description
                        })
                    })
                    authorization_code = array
                    $('#authorization_code').select2({
                        data: authorization_code,
                        placeholder: 'Select Authorization Codes'
                    })


                })
            // GET ALL FUND SOURCE
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=fund-source/get-fund-sources')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name
                        })
                    })
                    fund_source = array
                    $('#fund_source').select2({
                        data: fund_source,
                        placeholder: 'Select Fund Source'

                    });

                })
            // GET ALL MFO/PAP CODES
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=mfo-pap-code/get-mfo-pap-codes')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text:val.code + ' - '+val.name
                        })
                    })
                    mfo_pap_code = array
                    $('#mfo_pap_code').select2({
                        data: mfo_pap_code,
                        placeholder: 'Select Fund Source'

                    });

                })
            // GET ALL FUND CLUSTER CODES
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=fund-cluster-code/get-all-cluster')
                .then(function(data) {

                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.name +' - '+ val.description
                        })
                    })
                    books = array
                    $('#fund_cluster_code_id').select2({
                        data: books,
                        placeholder: 'Select Book'

                    });


                })




            // ADD ENTRY
            $('.add-btn').click(function() {
                add()
            })

            // SUBMIT DATA


            // INSERT DATA TO DATABASE SA RECORD ALLOTMENTS
            $('#add_data').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: window.location.pathname + '?r=record-allotments/create-record-allotments',
                    method: "POST",
                    data: $('#add_data').serialize(),
                    success: function(data) {
                        // //  $('#add_name')[0].reset();  
                        var res = JSON.parse(data)
                        console.log(res)
                        if (res.isSuccess) {
                            swal({
                                title: "Success",
                                // text: "You will not be able to undo this action!",
                                type: "success",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                                window.location.href = window.location.pathname + '?r=record-allotments/view&id=' + res.view_id
                            });
                            $('#add_data')[0].reset();
                        } else {
                            swal({
                                title: "Error",
                                // text:[res.error.reporting_period[0],res.error.serial_number[0]],
                                type: "error",
                                timer: 3000,
                                button: false
                                // confirmButtonText: "Yes, delete it!",
                            }, function() {
                                // window.location.href = window.location.pathname + '?r=record-allotments/view&id=' + res.view_id
                            });
                        }

                    }
                });
            })

        })
    </script>
</div>


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<?php

$script = <<< JS
      


     $(document).ready(function() {
         $('#financing_source_code').change(function(){
             console.log($(this).val())
         })
             // GET ALL CHART OF accounts
             $.getJSON('/dti-afms-2/frontend/web/index.php?r=chart-of-accounts/get-general-ledger')
                .then(function(data) {
                    var array = []
                    $.each(data, function(key, val) {
                        array.push({
                            id: val.id,
                            text: val.object_code + ' ' + val.title
                        })
                    })
                    accounts = array
                    $('#chart-0').select2({
                        data: accounts,
                        placeholder: "Select Chart of Account",

                    })
                });
            // GET FINANCING SOURCE CODES
            $.getJSON('/dti-afms-2/frontend/web/index.php?r=financing-source-code/get-financing-source-codes')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.name + ' - ' + val.description
                    })
                })
                financing_source_code = array
                $('#financing_source_code').select2({
                    data: financing_source_code,
                    placeholder: "Select Financing Source Code",

                })

            });
            // GET BOOKS
            var books=[];
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
                    placeholder: "Select Books",

                })

            });
        update_id = $('#update_id').val()
 
    // MAG API REQUEST KUNG NAAY SULOD ANG UPDATE_ID
        if (update_id > 0) {
            
            // console.log(update_id)
        
            $.ajax({
                url: window.location.pathname + '?r=record-allotments/update-record-allotment',
                method: 'POST',
                data: {
                    update_id: update_id
                },
                    success: function(data) {
                        var res = JSON.parse(data)
                        var record_allotment = res.record_allotments
                        var record_allotment_entries = res.record_allotment_entries
                        console.log(record_allotment)
                        $('#reporting_period').val(record_allotment['reporting_period'])
                        $('#date_issued').val(record_allotment['date_issued']);
                        $('#valid_until').val(record_allotment['valid_until']);
                        $('#fund_classification_code').val(record_allotment['fund_classification']);
                        $('#fund_cluster_code_id').val(record_allotment['fund_cluster_code_id']).trigger('change');
                        $('#document_recieve').val(record_allotment['document_recieve_id']).trigger('change');
                        $('#financing_source_code').val(record_allotment['financing_source_code_id']).trigger('change');
                        $('#authorization_code').val(record_allotment['authorization_code_id']).trigger('change');
                        $('#mfo_pap_code').val(record_allotment['mfo_pap_code_id']).trigger('change');
                        $('#fund_source').val(record_allotment['fund_source_id']).trigger('change');
                        $('#book').val(record_allotment['book_id']).trigger('change');
                        $('#particular').val(record_allotment['particulars']);

                        // console.log(record_allotment['particulars']) 
                        for (var y=0; y<record_allotment_entries.length;y++){
                            if (y>0){
                                add();
                            }
                            $("#amount-"+y).val(record_allotment_entries[y]['amount']);
                            console.log(record_allotment_entries[y]['chart_of_account_id'])
                            // $("#credit-"+x).val(jev_accounting_entries[x]['credit'])
                           
                            
                            $("#chart-"+y).val(record_allotment_entries[y]['chart_of_account_id']).trigger('change');
                        }

                    }
                })
            }
        })

    JS;
$this->registerJs($script);
?>