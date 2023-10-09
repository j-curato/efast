<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use Mpdf\Tag\Select;
use app\models\Books;
use yii\widgets\Pjax;
use app\models\DvAucs;
use common\models\User;
use kartik\grid\GridView;
use app\models\BankAccount;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\MajorAccounts;
use kartik\export\ExportMenu;
use app\models\AdvancesEntries;
use yii\data\ActiveDataProvider;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "FUR";
$this->params['breadcrumbs'][] = $this->title;
$reporting_period = '';
$province = '';
$bank_account_id = '';
$fur_id = '';
if (!empty($model->id)) {
    $reporting_period = $model->reporting_period;
    $province = $model->province;
    $bank_account_id = $model->bank_account_id;
    $fur_id = $model->id;
}
?>
<div class="jev-preparation-index card" style="padding:1rem">

    <form id="filter">
        <input type="hidden" name="id" value="<?= $fur_id ?>">
        <div class="row">
            <div class="col-sm-3">
                <label for="reporting_period">Reporting Peiod</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'value' => $reporting_period,
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>

            <div class="col-sm-3">
                <label for="bank_account">Bank Account</label>
                <?php
                $user_data = User::getUserDetails();
                $val = '';
                $and = '';
                $sql = '';
                $params = [];
                if (!Yii::$app->user->can('ro_accounting_admin')) {
                    $and = 'WHERE';
                    $sql = YIi::$app->db->getQueryBuilder()->buildCondition(['=', 'province', $user_data->employee->office->office_name], $params);
                }
                $bank_accounts = Yii::$app->db->createCommand("SELECT id ,CONCAT(account_number,'-',province,'-',account_name) as account FROM bank_account
                $and $sql
                ", $params)
                    ->queryAll();



                echo Select2::widget([
                    'name' => 'bank_account_id',
                    'data' => ArrayHelper::map($bank_accounts, 'id', 'account'),
                    'value' => $bank_account_id,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Bank Account'
                    ]
                ])

                ?>
            </div>
            <div class="col-sm-3" style="margin-top: 2.05rem;">
                <button class="btn btn-primary" id="generate">Generate</button>

                <?php
                if (Yii::$app->user->can('create_fur')) {
                    echo "
                    <button class='btn btn-success' type='submit' id='save'>Save</button>
                    ";
                }
                ?>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>
        <div class="head" style="margin-left: auto;margin-right:auto;text-align:center;">
            <h5 style="font-weight: bold;">Fund Utilization Report</h5>
            <h6 id="period"></h6>
            <h6 id="prov"></h6>
        </div>
        <table id="conso_fur_table" style="margin-top:20px;">
            <thead>
                <th>Report Type</th>
                <th>Beginning Balance</th>
                <th>Fund Recieved for the Month</th>
                <th>Total Disbursement for the Month</th>
                <th>Ending Balance</th>
            </thead>
            <tbody>


            </tbody>
        </table>
        <table class="" id="fur_table" style="margin-top: 30px;">

            <thead>
                <th>Fund Source</th>
                <th>Beginning Balance</th>
                <th class="amount">Cash Advance for the month</th>
                <th class="amount">Total Liquidation For the Month</th>
                <th class="amount">Ending Balance</th>
                <th>Particulars</th>
                <th>Report Type</th>
                <th>Object Code</th>
                <th>Account Title</th>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    #con {
        display: none;
    }

    .amount {
        text-align: right;
    }

    @media print {

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>

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
        console.log(seconds)
    })
    $('#generate').click((e)=>{
        e.preventDefault();
        var reporting_period = new Date($('#reporting_period').val())
        month = reporting_period.toLocaleString('default',{month:'long'})
        year = reporting_period.getFullYear()
        console.log(province['adn'])
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type:'POST',
            url:window.location.pathname +'?r=fur/generate-fur',
            data:$("#filter").serialize(),
            success:function(data){
                var res = JSON.parse(data)
                var conso_fur = res.conso_fur
                var fur = res.fur
                console.log(res)
                $("#period").text('For the Period of '+ month+','+year)
                $('#prov').text('Province of '+province[$('#province').val()])
                addData(fur,conso_fur)
                $('#dots5').hide()
                $('#con').show()
            }
      
        })
    })
    function addData(fur, conso_fur) {
        $("#conso_fur_table > tbody").html("");
        $("#fur_table > tbody").html("");
        var advances_type = ''
        var b_balance = ''
        var f_total_recieve = ''
        var f_total_disbursements = ''
        var ending_balance =0
        var row
        var total_conso_fur_b_balance=0
        var total_conso_fur_f_total_recieve=0
        var total_conso_fur_f_total_disbursements=0
        var total_conso_fur_ending_balance=0
        for (var i = 0; i < conso_fur.length; i++) {
            advances_type = conso_fur[i]['report_type']
            b_balance = conso_fur[i]['begining_balance']!=null?conso_fur[i]['begining_balance']:0
            f_total_recieve = conso_fur[i]['total_advances']
            f_total_disbursements = conso_fur[i]['total_withdrawals']
            ending_balance =  (b_balance+f_total_recieve)-f_total_disbursements
            total_conso_fur_b_balance += parseFloat(b_balance,2) 
            total_conso_fur_f_total_recieve += parseFloat(f_total_recieve,2)
            total_conso_fur_f_total_disbursements += parseFloat(f_total_disbursements,2)
            total_conso_fur_ending_balance += parseFloat(ending_balance,2)
            if (b_balance ==0 && f_total_recieve==0 && f_total_disbursements==0){

 
             }
             else{
                row = `<tr>
                        <td>`+advances_type+`</td>
                        <td class='amount'>`+thousands_separators(b_balance)+`</td>
                        <td class='amount'>`+thousands_separators(f_total_recieve)+`</td>
                        <td class='amount'>`+thousands_separators(f_total_disbursements)+`</td>
                        <td class='amount'>`+thousands_separators(ending_balance)+`</td>
                        </tr>
                        `
                $('#conso_fur_table tbody').append(row)
             }

        }
        row = `<tr>
                    <td colspan=''>Total</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_f_total_recieve)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_f_total_disbursements)+`</td>
                    <td class='amount'>`+thousands_separators(total_conso_fur_ending_balance)+`</td>
                    </tr>
                    `
        $('#conso_fur_table tbody').append(row)


        var fur_b_balance = 0
        var fur_total_advances = 0
        var fur_total_withdrawals = 0
        var fur_ending_balance=0
        var total_fur_b_balance = 0
        var total_fur_total_advances = 0
        var total_fur_total_withdrawals = 0
        var total_fur_ending_balance=0
        for (var x = 0;x<fur.length;x++){
         
                 fur_b_balance = fur[x]['begining_balance']
                 fur_total_advances = fur[x]['total_advances']==null?0:fur[x]['total_advances']
                 fur_total_withdrawals = fur[x]['total_withdrawals']==null?0:fur[x]['total_withdrawals']
                 fur_ending_balance=(parseFloat(fur_b_balance,2)+parseFloat(fur_total_advances,2))-parseFloat(fur_total_withdrawals,2)

                 total_fur_b_balance+=parseFloat(fur_b_balance,2)
                 total_fur_total_advances+=parseFloat(fur_total_advances,2)
                 total_fur_total_withdrawals+=parseFloat(fur_total_withdrawals,2)
                 total_fur_ending_balance+=parseFloat(fur_ending_balance,2)
            if (fur_b_balance==0 && fur_total_advances==0 && fur_total_withdrawals==0){

            }
            else{
                var final_fur_b_balance = fur_b_balance !=0 ?thousands_separators(fur_b_balance):''
                var final_fur_total_advances  = fur_total_advances !=0 ?thousands_separators(fur_total_advances):''
                var final_fur_total_withdrawals = fur_total_withdrawals !=0 ?thousands_separators(fur_total_withdrawals):''
                var final_fur_ending_balance = fur_ending_balance !=0 ?thousands_separators(fur_ending_balance):''
                row =  `<tr>
                        <td>`+fur[x]['fund_source']+`</td>
                        <td class='amount'>`+final_fur_b_balance+`</td>
                        <td class='amount'>`+final_fur_total_advances+`</td>
                        <td class='amount'>`+final_fur_total_withdrawals+`</td>
                        <td class='amount'> `+final_fur_ending_balance+`</td>
                        <td>`+fur[x]['particular']+`</td>
                        <td>`+fur[x]['report_type']+`</td>
                        <td>`+fur[x]['object_code']+`</td>
                        <td>`+fur[x]['account_title']+`</td>
                        </tr>`

                $('#fur_table tbody').append(row)
            }

        }
        row =  `<tr>
                    <td>Total</td>
                    <td class='amount'>`+thousands_separators(total_fur_b_balance)+`</td>
                    <td class='amount'>`+thousands_separators(total_fur_total_advances)+`</td>
                    <td class='amount'>`+thousands_separators(total_fur_total_withdrawals)+`</td>
                    <td class='amount'> `+thousands_separators(total_fur_ending_balance)+`</td>
                    <td></td>
                    <td></td>
                </tr>`

            $('#fur_table tbody').append(row)
    }
        $('#filter').submit(function(e){
            e.preventDefault();
            $.ajax({
                            type:"POST",
                            url:window.location.pathname + "?r=fur/insert-fur",
                            data:$('#filter').serialize(),
                            success:function(data){
                                var res = JSON.parse(data)
                                if(res.isSuccess){
                                    swal({
                                            title:'Success',
                                            type:'success',
                                            button:false,
                                            timer:3000,
                                        },function(){
                                            location.reload(true)
                                        })
                                }
                                else{
                                    swal({
                                            title:"Error ",
                                            text:res.error,
                                            type:'error',
                                            button:false,
                                            timer:3000,
                                        })
                                }

                            }
                        })

        })

JS;
$this->registerJs($script);
?>