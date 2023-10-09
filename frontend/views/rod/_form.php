<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\select2\Select2;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "ROD";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index card" style="padding:1rem">



    <form id="filter">
        <div class="row">
            <?php
            if (!empty($model)) {
                echo "<input type='hidden' value='$model->rod_number' name = 'rod_number' id = 'rod_number'> ";
                echo "<input type='hidden' value='update' name = 'action_type'>";
            }
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
                $data = '';
                if (!empty($model)) {
                    $dataList = Yii::$app->db->createCommand("SELECT advances_entries.id,advances_entries.fund_source FROM rod_entries 
                    LEFT JOIN advances_entries ON rod_entries.advances_entries_id = advances_entries.id
                    WHERE 

                    rod_number = :rod_number")
                        ->bindValue('rod_number', $model->rod_number)
                        ->queryAll();
                    // $dataList = AdvancesEntries::find()->andWhere(['id' => 4719])->all();
                    // $data = ArrayHelper::map($dataList, 'id', 'name');
                    $data = ArrayHelper::map($dataList, 'id', 'fund_source');
                    // ob_clean();
                    // echo "<pre>";
                    // echo "</pre>";
                    // return ob_get_clean();

                }

                echo Select2::widget([
                    'name' => 'fund_source',
                    'value' => array_column($dataList, 'id'), // value to initialize
                    'data' => $data,

                    'options' => ['multiple' => true, 'placeholder' => 'Search for a fund source ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=rod/search-fund-source',
                            'delay' => 250,
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        // 'templateResult' => new JsExpression('function(city) { return city.text; }'),
                        // 'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ]);

                ?>

            </div>

            <div class="col-sm-2" style="margin-top: 2.05rem;">
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

                    <th colspan="7">
                        <div class="head" style="margin-left: auto;margin-right:auto;text-align:center;">
                            <h5 style="font-weight: bold;">REPORT OF DISBURSEMENTS</h5>
                            <h6> Department of Trade and Industry</h6>
                            <h6 id="prov"> Provincial Office of Surigao del Sur</h6>
                        </div>
                    </th>
                </tr>
                <tr>

                    <th colspan="5">Period Covered:</th>
                    <th colspan="2">Report No.:</th>
                </tr>
                <tr>

                    <th colspan="5"></th>
                    <th colspan="2">Sheet No.:</th>
                </tr>

                <th>Date</th>
                <th>DV/Payroll No.</th>
                <th>Responsibility Center Code</th>
                <th>Payee</th>
                <th>Nature of Payment</th>
                <th class="amount">Amount</th>
                <th class="amount">Gross Amount</th>

            </thead>
            <tbody>
                <tr id="start"></tr>
                <tr>
                    <td colspan="5">
                        Total
                    </td>
                    <td class='amount' id="total_amount">

                    </td>
                    <td class='amount' id="grossTotal">

                    </td>

                </tr>
                <tr>
                    <td colspan="7">
                        CERTIFICATION
                    </td>

                </tr>
                <tr>


                    <td colspan="7">
                        <h1 id="pageCounter">
                        </h1>
                        <span>
                            I herby certify that this Report of Disbursemets in ____ sheet is a full, true and correct statement of the disbursements made by
                            me and that this is in liquidation of the following cash advances granted to the Provincial Office, to with:
                        </span>
                        <table id="fund_source_table" style="margin-left:auto;margin-right:auto;margin-top :2rem">

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
                    <td colspan="4" style="border-right:none">
                        <span>_________________________</span>
                        <br>
                        <span>Disbursing Officer</span>
                    </td>
                    <td colspan="3" style="text-align: center; border-left:none">
                        <span>_______________</span>
                        <br>
                        <span>Date</span>
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

        @page {
            margin: 10mm;
        }

        table,
        th,
        td {
            padding: 3px;
        }

    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    const size = 1122; // roughly A4
    $('#province').change(function() {

    })


    $('.js-data-example-ajax').select2({
        ajax: {
            url: window.location.pathname + '?r=report/fund',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term, // search term
                    province: $('#province').val()
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                console.log(data)
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });
    $(document).ready(function() {

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
        if ($('#rod_number').val()!=''){
            $('#generate').trigger('click');
        }

    })
    // SAVE TO DATABASE
    $('#filter').submit(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=rod/insert-rod',
            data:$('#filter').serialize(),
            success:function(data){
                var res = JSON.parse(data)
               
                if (res.isSuccess){
                    console.log(res)
                    swal({
                        type:'success',
                        button:false,
                        title:'Success',
                        timer:2000,
                    })
                }
                else{
                    swal({
                        type:'error',
                        button:false,
                        title:'Error',
                        timer:2000,
                    })
                }
            }
        })
    })
    $('#generate').click((e)=>{
        e.preventDefault();
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=rod/get-rod',
            data:$("#filter").serialize(),
            success:function(data){
                var res = JSON.parse(data)
                var liquidation = res.liquidations
                var conso_fund_source = res.conso_fund_source
                addData(liquidation)
                fundSource(conso_fund_source)
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
                console.log(table.innerHeight())
                $('.total').text(Math.ceil(parseFloat(table_size, 2) / size))
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
        var total_amount = 0
        var g_total_withdrawals = 0
        var g_total_balance = 0
        for (var i = 0; i<conso_fund_source.length;i++){
            row =  `<tr class='data_row'>
                        <td>`+conso_fund_source[i]['fund_source']+`</td>
                        <td>`+conso_fund_source[i]['check_or_ada_no']+`</td>
                        <td>`+conso_fund_source[i]['issuance_date']+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['amount']))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['total_withdrawals']))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(conso_fund_source[i]['balance']))+`</td>
                        </tr>`
                $('#fund_source_table').append(row)
                total_amount+=parseFloat(conso_fund_source[i]['amount'])
                g_total_withdrawals+=parseFloat(conso_fund_source[i]['total_withdrawals'])
                g_total_balance+=parseFloat(conso_fund_source[i]['balance'])
        }     
        row =  `<tr class='data_row'>
                        <td colspan='3'>Total</td>
          
                        <td class='amount'>`+thousands_separators(parseFloat(total_amount))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(g_total_withdrawals))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(g_total_balance))+`</td>
                        </tr>`
                $('#fund_source_table').append(row)    
    }
    function addData(rod) {
        $(".data_row").remove();
        var total = 0
        let grossTotal = 0
        var particular =''
        for (var x = 0;x<rod.length;x++){
            

            if (rod[x]['particular']==null){
                rod[x]['particular']=''
            }
            if (rod[x]['dv_number']==null){
                rod[x]['dv_number']=''
            }
                row =  `<tr class='data_row'>
                        <td>`+rod[x]['check_date']+`</td>
                        <td >`+rod[x]['dv_number']+`</td>
                        <td >`+rod[x]['reponsibility_center_name']+`</td>
                        <td >`+rod[x]['payee']+`</td>
                        <td >`+rod[x]['particular']+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(rod[x]['withdrawals']))+`</td>
                        <td class='amount'>`+thousands_separators(parseFloat(rod[x]['gross_amount']))+`</td>
                        </tr>`
                $('#rod_table').find('#start').after(row)
            total +=parseFloat(rod[x]['withdrawals'])
            grossTotal +=parseFloat(rod[x]['gross_amount'])
        }
      
        $('#total_amount').text(  thousands_separators(total))
        $('#grossTotal').text(  thousands_separators(grossTotal))


    }






JS;
$this->registerJs($script);
?>