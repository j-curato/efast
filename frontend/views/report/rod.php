<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php


use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "FUR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">
            <?php
            if (Yii::$app->user->can('ro_accounting_admin')) {

            ?>
                <div class="col-sm-2">
                    <label for="province">Province</label>
                    <?php

                    echo Select2::widget([
                        'name' => 'province',
                        'id' => 'province',
                        'data' => [
                            'adn' => 'ADN',
                            'ads' => 'ADS',
                            'sdn' => 'SDN',
                            'sds' => 'SDS',
                            'pdi' => 'PDI',
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'placeholder' => 'Select Province'
                        ]
                    ]);

                    ?>
                </div>
            <?php } ?>
            <div class="col-sm-7">
                <label for="fund_source">Fund Source</label>
                <?php
                // echo Select2::widget([
                //     'name' => 'fund_source',
                //     'id' => 'fund_source',
                //     'initValueText' => 1001,
                //     'options' => ['multiple' => true, 'placeholder' => 'Search for a Fund Source ...'],
                //     'pluginOptions' => [
                //         'allowClear' => true,
                //         'minimumInputLength' => 1,
                //         'language' => [
                //             'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                //         ],
                //         'ajax' => [
                //             'url' => Yii::$app->request->baseUrl . '?r=report/fund',
                //             'dataType' => 'json',
                //             'delay' => 250,
                //             'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                //             'cache' => true
                //         ],
                //         'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                //         'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                //         'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                //     ],
                // ]);

                ?>
                <select class="js-data-example-ajax" style="width: 100%;" name="fund_source[]" multiple='multiple'></select>
                <select class="chart-of-accounts" style="width: 100%;" name="chart-of-account[]" multiple='multiple'></select>
            </div>

            <div class="col-sm-2" style="margin-top: 2.5rem;">
                <button class="btn btn-primary" id="generate">Generate</button>
                <button class="btn btn-success" id="save" type="submit"> Save</button>
            </div>

        </div>
        <!-- <select class="js-data-example-ajax" style="width: 100%;" name="fund_source[]" multiple="multiple"></select> -->
    </form>

    <!-- <div id="con"> -->

    <div id='con'>

        <table class="" id="rod_table" style="margin-top: 30px;">

            <thead>
                <tr>

                    <th colspan="6">
                        <div class="head" style="margin-left: auto;margin-right:auto;text-align:center;">
                            <h5 style="font-weight: bold;">REPORT OF DISBURSEMENTS</h5>
                            <h6> Department of Trade and Industry</h6>
                            <h6 id="prov"> Provincial Office of Surigao del Sur</h6>
                        </div>
                    </th>
                </tr>
                <tr>

                    <th colspan="4">Period Covered:</th>
                    <th colspan="2">Report No.:</th>
                </tr>
                <tr>

                    <th colspan="4"></th>
                    <th colspan="2">Sheet No.:</th>
                </tr>

                <th>Date</th>
                <th>DV/Payroll No.</th>
                <th>Responsibility Center Code</th>
                <th>Payee</th>
                <th>Nature of Payment</th>
                <th class="amount">Amount</th>

            </thead>
            <tbody>
                <tr id="start"></tr>
                <tr>
                    <td colspan="5">
                        Total
                    </td>
                    <td class='amount' id="total_amount">

                    </td>

                </tr>
                <tr>
                    <td colspan="6">
                        CERTIFICATION
                    </td>

                </tr>
                <tr>


                    <td colspan="6">
                        <h1 id="pageCounter">
                        </h1>
                        <span>
                            I herby certify that this Report of Disbursemets in <span class="total"></span> sheet is a full, true and correct statement of the disbursements made by
                            me and that this is in liquidation of the following cash advances granted to the Provincial Office, to with:
                        </span>
                        <table id="fund_source_table">

                            <thead>
                                <th>Fund Source</th>
                                <th>Check Number</th>
                                <th>Check Date</th>
                                <th>Fund Source Amount</th>
                                <th>Total Disbursed</th>
                                <th>Balance</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-right:none">
                        <span>______________</span>
                        <br>
                        <span>Disbursing Officer</span>
                    </td>
                    <td colspan="3" style="text-align: left; border-left:none">
                        <span>______________</span>
                        <br>
                        <span style="margin-left: 30px;">date</span>
                    </td>
                </tr>

            </tbody>


        </table>

    </div>
    <!-- </div> -->



</div>
<div id="dots5" style="display: none;">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<style>
    @page {
        size: A4;
        margin: 0;
    }

    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }



    .amount {
        text-align: right;
    }




    @media print {
        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    const size = 1122; // roughly A4



    $(document).ready(function() {

        $('.js-data-example-ajax').select2({
            ajax: {
                url: window.location.pathname + '?r=report/fund',
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
            }
        });
        // SERVER SIDE SEARCH
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
            }
        });
        var studentSelect = $('.js-data-example-ajax');
        var data = [{
                id: 0,
                text: 'enhancement'
            },
            {
                id: 1,
                text: 'bug'
            },
            {
                id: 2,
                text: 'duplicate'
            },
            {
                id: 3,
                text: 'invalid'
            },
            {
                id: 4,
                text: 'wontfix'
            }
        ];

        var option = new Option(['duplicate'], [2], true, true);
        studentSelect.append(option).trigger('change');
        var option = new Option(['bug'], [1], true, true);
        studentSelect.append(option).trigger('change');

        // manually trigger the `select2:select` event
        studentSelect.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });


        var _docHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight;
        var table = $("#rod_table");
        // alert(table.offsetHeight);
        var thead = $('#rod_table thead')
        var qwe = 0;
        var pages = Math.ceil(table.innerHeight() / size)
        var table_size = parseFloat(table.innerHeight(), 2)
        var thead_size = parseFloat(thead.innerHeight(), 2)
        if (pages > 1) {
            qwe = table_size + (thead_size * pages);
        }

        console.log(qwe)
        $('.total').text(Math.ceil(parseFloat(table_size, 2) / size))
    })
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
    function thousands_separators(num) {

    var number = Number(Math.round(num + 'e2') + 'e-2')
    var num_parts = number.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
    }

    var month= ''
    var year=''
    var province={
        'adn' : 'Agusan Del Norte',
        'ads' : 'Agusan Del Sur',
        'sdn' : 'Surigao Del Norte',
        'sds' : 'Surigao Del Sur',
        'pdi' : 'Province of Dinagat Islands',
    }

    $(document).ready(()=>{
        var startDate = new Date(2021,07,13, 09,17,04);

        var endDate   = new Date(2021,07,12, 18,39,09);
        var seconds = (endDate.getTime() - startDate.getTime())
        var diff =seconds/ 60;
        // console.log(seconds)
    })

    $('#generate').click((e)=>{
        e.preventDefault();


        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=report/rod',
            data:$("#filter").serialize(),
            success:function(data){
                var res = JSON.parse(data)
                var liquidation = res.liquidations
                var conso_fund_source = res.conso_fund_source
                addData(liquidation)
                fundSource(conso_fund_source)
                setTimeout(() => {
                    $('#dots5').hide()
                    $('#con').show()
                }, 1000);
             
            }
      
        })
    })
    function fundSource(conso_fund_source){
        console.log(conso_fund_source)
        $("fund_source_table tbody").html('');
        for (var i = 0; i<conso_fund_source.length;i++){
            row =  `<tr class='data_row'>
                        <td>`+conso_fund_source[i]['fund_source']+`</td>
                        <td>`+conso_fund_source[i]['check_or_ada_no']+`</td>
                        <td>`+conso_fund_source[i]['issuance_date']+`</td>
                        <td>`+conso_fund_source[i]['amount']+`</td>
                        <td>`+conso_fund_source[i]['total_withdrawals']+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['balance']))+`</td>
                        </tr>`
                $('#fund_source_table').append(row)
        }        
    }
    function addData(rod) {
        $(".data_row").remove();
        var total = 0
        for (var x = 0;x<rod.length;x++){
                row =  `<tr class='data_row'>
                        <td>`+rod[x]['check_date']+`</td>
                        <td >`+rod[x]['dv_number']+`</td>
                        <td >`+rod[x]['reponsibility_center_name']+`</td>
                        <td >`+rod[x]['payee']+`</td>
                        <td></td>
                        <td class='amount'>`+thousands_separators(parseFloat(rod[x]['withdrawals']))+`</td>
                        </tr>`
                $('#rod_table').find('#start').after(row)
            total +=parseFloat(rod[x]['withdrawals'])
        }
      
        $('#total_amount').text(  thousands_separators(total))


    }






JS;
$this->registerJs($script);
?>