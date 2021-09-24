<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<?php
date_default_timezone_set('Asia/Manila');
$time = date('h:i:s A');
$date = date('Y-M-d');
$id = $model->id;
Modal::begin(
    [
        //'header' => '<h2>Create New Region</h2>',
        'id' => 'cancelModal',
        'size' => 'modal-md',
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
    ]
);
echo "<div class='box box-success' id='modalContent'></div>";
echo "<form id='cancelForm'>";
echo "<div class='row'>";
echo "<input type='hidden' name='cancelId' value='$id' style='display:none;'/>";
echo "<label for='date' style='text-align:center'>Date</label>";
echo DatePicker::widget([
    'name' => 'reporting_period',
    'id' => 'reporting_period',
    'options' => [
        'readOnly' => true,
        'style' => 'background-color:white;'
    ],
    'pluginOptions' => [
        'format' => 'yyyy-M',
        'autoclose' => true,
        'startView' => 'months',
        'minViewMode' => 'months'
    ]
]);
echo "</div>";

echo "<button type='submit' class='btn btn-success' id='save'>Save</button>";
echo '<form>';
Modal::end();
?>
<div class="liquidation-view">



    <div class="">

        <?php if (\Yii::$app->user->can('create_liquidation')) { ?>
            <p>
                <?= Html::a('Re-Align/Update', ['re-align', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php

                if ($model->is_cancelled) {
                    echo "<button class='btn btn-success' id='cancel' style='margin:5px'>Activate</button>";
                } else {
                    echo "<button class='btn btn-danger' id='cancel' style='margin:5px'>Cancel</button>";
                }
                echo "<input type='text' id='cancel_id' value='$model->id' style='display:none;'/>";


                $total_withdrawal = 0;
                $total_vat_nonvat = 0;
                $total_liquidation_damages = 0;
                $total_ewt = 0;
                $display = 'display:none';
                $new_uacs = '';

                $charts = Yii::$app->db->createCommand("SELECT id,CONCAT(uacs,'-',general_ledger) as account_title  FROM chart_of_accounts where is_active =1")->queryAll();

                if (Yii::$app->user->can('update_liquidation_account')) {
                    $display = '';
                    $new_uacs = Select2::widget([
                        'data' => ArrayHelper::map($charts, 'id', 'account_title'),
                        'name' => 'new_chart_of_account[]',
                        'pluginOptions' => [
                            'placeholder' => 'Select Account'
                        ]
                    ]);
                }
                ?>
            </p>
        <?php } ?>
        <form id='new_uacs'>

            <table class="table table-striped">
                <thead>
                    <th>ID</th>
                    <th>Reporting Period</th>
                    <th>NFT Number</th>
                    <th>Report Type</th>
                    <th>Fund Source</th>
                    <th>Payee</th>
                    <th>Particulars</th>
                    <th>Responsibility Center</th>
                    <th>UACS Object Code</th>
                    <th>General Ledger</th>
                    <th class='number'>Withdrawals</th>
                    <th class='number'>Vat/Non-Vat</th>
                    <th class='number'>Expanded Tax</th>
                    <th class='number'>Liquidation Damages</th>
                    <th class='number'>Gross Amount</th>
                </thead>
                <tbody>

                    <?php
                    $payee =  '';
                    if (empty($model->po_transaction_id)) {
                        $payee  = !empty($model->payee) ? $model->payee : '';
                    } else {
                        $payee  =  $model->poTransaction->payee;
                    }

                    $particular = '';
                    if (empty($model->po_transaction_id)) {
                        $particular  = !empty($model->particular) ? $model->particular : '';
                    } else {
                        $particular  =  $model->poTransaction->particular;
                    }
                    $responsibility_center = !empty($model->po_transaction_id) ? $model->poTransaction->poResponsibilityCenter->name : '';
                    $total_gross = 0;
                    $gross = 0;

                    foreach ($model->liquidationEntries as $val) {
                        $gross = $val->withdrawals +   $val->vat_nonvat + $val->expanded_tax + $val->liquidation_damage;
                        $total_gross += $gross;

                        $nft_number = '';
                        $report_type = '';
                        $province = '';
                        $fund_source = '';
                        $uacs = '';
                        $general_ledger = '';
                        $total_withdrawal += $val->withdrawals;
                        $total_vat_nonvat += $val->vat_nonvat;
                        $total_ewt += $val->expanded_tax;
                        $total_liquidation_damages += $val->liquidation_damage;
                        if (!empty($val->advances_entries_id)) {
                            $nft_number =  $val->advancesEntries->advances->nft_number;
                            $report_type = $val->advancesEntries->advances->report_type;
                            $province = $val->advancesEntries->advances->province;
                            $fund_source =  $val->advancesEntries->fund_source;
                        }
                        if (!empty($val->new_chart_of_account_id)) {

                            $uacs = $val->newChartOfAccount->uacs;
                            $general_ledger =  $val->newChartOfAccount->general_ledger;
                        } else if (!empty($val->chart_of_account_id)) {
                            $uacs = $val->chartOfAccount->uacs;
                            $general_ledger =  $val->chartOfAccount->general_ledger;
                        }



                        echo "<tr>
                
                <td>{$val->id}</td>
                <td>{$val->reporting_period}</td>
                <td>{$nft_number}</td>
                <td>{$report_type}</td>
                <td>{$fund_source}</td>
                <td>{$payee}</td>
                <td>{$particular}</td>
                <td>{$responsibility_center}</td>
                <td>{$uacs}</td>
                <td>{$general_ledger}</td>
                <td class='number'>" . number_format($val->withdrawals, 2) . "</td>
                <td class='number'>" . number_format($val->vat_nonvat, 2) . "</td>
                <td class='number'>" . number_format($val->expanded_tax, 2) . "</td>
                <td class='number'>" . number_format($val->liquidation_damage, 2) . "</td>
                <td class='number'>" . number_format($gross, 2) . "</td>
                
                </tr>";
                    }

                    echo "<tr>
                <td colspan='9' style='text-align:center;font-weight:bold;'>Total</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_withdrawal, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_vat_nonvat, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_ewt, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_liquidation_damages, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_gross, 2) . "</td>
                </tr>";
                    ?>
                </tbody>
            </table>

            <?php
            if (Yii::$app->user->can('update_liquidation_account')) {
                // echo "<button type='submit' class='btn btn-success'>Save</button>";
            }
            ?>
        </form>

    </div>

</div>

<style>
    .number {
        text-align: right;
    }

    .container {
        padding: 12px;
    }
</style>


<?php
SweetAlertAsset::register($this);
$script = <<<JS

    $('#cancel').click(function(e) {
        e.preventDefault();    
        $('#cancelModal').modal('show').find('#link').attr('href',$(this).attr('value'))
    });
    
    $('#cancelForm').submit(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=liquidation/cancel',
            data:$('#cancelForm').serialize(),
            success:function(data){
                console.log(data)
            }
        })
    })
    // $("#cancel").click(function(){
    //     swal({
    //         title: "Are you sure?",
    //         text: "You will not be able to recover this imaginary file!",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: '#DD6B55',
    //         confirmButtonText: 'Yes, I am sure!',
    //         cancelButtonText: "No, cancel it!",
    //         closeOnConfirm: false,
    //         closeOnCancel: true
    //      },
    //      function(isConfirm){

    //        if (isConfirm){
    //                 $.ajax({
    //                     type:"POST",
    //                     url:window.location.pathname + "?r=liquidation/cancel",
    //                     data:{
    //                         id:$("#cancel_id").val()
    //                     },
    //                     success:function(data){
    //                         console.log(data)
    //                         var res = JSON.parse(data)
    //                         var cancelled = res.cancelled?"Successfuly Cancelled":"Successfuly Activated";
    //                         if(res.isSuccess){
    //                             swal({
    //                                     title:cancelled,
    //                                     type:'success',
    //                                     button:false,
    //                                     timer:3000,
    //                                 },function(){
    //                                     location.reload(true)
    //                                 })
    //                         }
    //                         else{
    //                             swal({
    //                                     title:"Error Cannot Cancel",
    //                                     text:"Dili Ma  Cancel ang Disbursment Niya",
    //                                     type:'error',
    //                                     button:false,
    //                                     timer:3000,
    //                                 })
    //                         }

    //                     }
    //                 })


    //         } 
    //     })

    

    // })

JS;

$this->registerJs($script);

?>