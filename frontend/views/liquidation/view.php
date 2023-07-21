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
// date_default_timezone_set('Asia/Manila');
// $period = !empty($model->cancel_reporting_period) ? $model->cancel_reporting_period : '';

// $id = $model->id;

// Modal::begin(
//     [
//         //'header' => '<h2>Create New Region</h2>',
//         'id' => 'cancelModal',
//         'size' => 'modal-md',
//         'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE, 'class' => 'modal modal-primary '],
//         'options' => [
//             'tabindex' => false // important for Select2 to work properly
//         ],
//     ]
// );
// echo "<div class='box box-success' id='modalContent'></div>";
// echo "<form id='cancelForm'>";
// echo "<div class='row'>";
// echo "<input type='hidden' name='cancelId' value='$id' style='display:none;'/>";
// echo "<label for='date' style='text-align:center'>Reporting Period</label>";
// echo DatePicker::widget([
//     'name' => 'reporting_period',
//     'id' => 'reporting_period',
//     'value'=>$period,
//     'options' => [
//         'readOnly' => true,
//         'style' => 'background-color:white;'
//     ],
//     'pluginOptions' => [
//         'format' => 'yyyy-M',
//         'autoclose' => true,
//         'startView' => 'months',
//         'minViewMode' => 'months'
//     ]
// ]);
// echo "</div>";

// echo "<button type='submit' class='btn btn-success' id='save'>Save</button>";
// echo '<form>';
// Modal::end();

$transmittal_id = Yii::$app->db->createCommand("SELECT 
po_transmittal_entries.fk_po_transmittal_id
FROM po_transmittal_entries

 WHERE po_transmittal_entries.liquidation_id = :id
AND po_transmittal_entries.is_deleted = 0")
    ->bindValue(':id', $model->id)
    ->queryScalar();
?>
<div class="liquidation-view">



    <div class="">

        <?php if (\Yii::$app->user->can('create_liquidation')) { ?>
            <p>
                <?= Html::a('Re-Align/Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php
                if (Yii::$app->user->can('super-user')) {
                    echo Html::button(empty($model->document_link) ? 'Add File Link' : 'Update File Link', ['class' => "btn btn-success", 'data-target' => "#uploadmodal", 'data-toggle' => "modal"]);
                }
                if ($transmittal_id) {
                    echo Html::a('Transmital Link', ['po-transmittal/view', 'id' => $transmittal_id], ['class' => "btn btn-link"]);
                }
                $btn_clr = 'btn-danger';
                $text = 'Exclude ';

                if ($model->exclude_in_raaf === 1) {
                    $btn_clr = 'btn-success';
                    $text = 'Include';
                }


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
                $document_link = '';
                if (!empty($model->document_link)) {
                    $document_link = $model->document_link;
                    echo Html::a('Soft Copy Link', $document_link, ['class' => 'btn btn-link ', 'target' => '_blank']);
                }
                ?>
                <?= Html::a($text . ' in RAAF', ['exclude-raaf', 'id' => $model->id], [
                    'class' => 'btn ' . $btn_clr,
                    'data' => [
                        'confirm' => 'Are you sure you want to exclude this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <?php } ?>

        <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="add_link">
                        <div class='modal-body'>
                            <hr>
                            <label for="ledger"> Link</label>

                            <input type="text " style="display: none;" class="form-control" name="id" value='<?= $model->id ?>'>

                            <input type="text " class="form-control" name="link" value='<?= $document_link ?>'>
                        </div>
                        <div class="row" style="margin: 10px;padding:12px">
                            <div class="col-sm-3 col-sm-offset-5">

                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <form id='new_uacs'>

            <table class="table table-striped">
                <thead>
                    <th>ID</th>
                    <th>Reporting Period</th>
                    <th>NFT Number</th>
                    <th>Report Type</th>
                    <th>Fund Source Type</th>
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
                        $fund_source_type = '';
                        $uacs = '';
                        $general_ledger = '';
                        $total_withdrawal += $val->withdrawals;
                        $total_vat_nonvat += $val->vat_nonvat;
                        $total_ewt += $val->expanded_tax;
                        $total_liquidation_damages += $val->liquidation_damage;
                        if (!empty($val->advances_entries_id)) {
                            $nft_number =  $val->advancesEntries->advances->nft_number;
                            $report_type = $val->advancesEntries->report_type;
                            $province = $val->advancesEntries->advances->province;
                            $fund_source =  $val->advancesEntries->fund_source;
                            $fund_source_type =  $val->advancesEntries->fund_source_type;
                        }
                        if (!empty($val->new_object_code)) {

                            $q = Yii::$app->db->createCommand("SELECT object_code,account_title FROM accounting_codes WHERE object_code = :object_code")
                                ->bindValue(':object_code', $val->new_object_code)
                                ->queryOne();
                            $uacs = $q['object_code'] ?? '';
                            $general_ledger =  $q['account_title'] ?? '';
                        } else if (!empty($val->new_chart_of_account_id)) {

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
                <td>{$fund_source_type}</td>
                <td>{$fund_source}</td>
                <td>{$payee}</td>
                <td>{$particular}</td>
                <td>{$responsibility_center}</td>
                <td>{$uacs}</td>
                <td>{$general_ledger}</td>
                <td class='number'>" . number_format($val->withdrawals ?? 0, 2) . "</td>
                <td class='number'>" . number_format($val->vat_nonvat ?? 0, 2) . "</td>
                <td class='number'>" . number_format($val->expanded_tax ?? 0, 2) . "</td>
                <td class='number'>" . number_format($val->liquidation_damage ?? 0, 2) . "</td>
                <td class='number'>" . number_format($gross, 2) . "</td>
                
                </tr>";
                    }

                    echo "<tr>
                <td colspan='11' style='text-align:center;font-weight:bold;'>Total</td>
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

<script>

</script>
<?php
SweetAlertAsset::register($this);
$script = <<<JS

    // $('#cancel').click(function(e) {
    //     e.preventDefault();    
    //     $('#cancelModal').modal('show').find('#link').attr('href',$(this).attr('value'))
    // });
    
    // $('#cancelForm').submit(function(e){
    //     e.preventDefault();
        
    //     $.ajax({
    //         type:'POST',
    //         url:window.location.pathname + '?r=liquidation/cancel',
    //         data:$('#cancelForm').serialize(),
    //         success:function(data){
    //             var res = JSON.parse(data)
           
    //        if (res.isSuccess) {
    //            swal({
    //                title: 'Success',
    //                type: 'success',
    //                button: false,
    //                timer: 3000,
    //            }, function() {
    //                location.reload(true)
    //            })
    //        } else {
    //            swal({
    //                title: "Error Adding Fail",
    //                type: 'error',
    //                button: false,
    //                timer: 3000,
    //            })
    //        }
                
    //         }

    //     })
    // })
    $('#add_link').submit((e) => {
        e.preventDefault();
        console.log('qwe')
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=liquidation/add-link',
            data: $("#add_link").serialize(),
            success: function(data) {
                $('#uploadmodal').modal('toggle');
                var res = JSON.parse(data)
           
                if (res.isSuccess) {
                    swal({
                        title: 'Success',
                        type: 'success',
                        button: false,
                        timer: 3000,
                    }, function() {
                        location.reload(true)
                    })
                } else {
                    swal({
                        title: "Error Adding Fail",
                        type: 'error',
                        button: false,
                        timer: 3000,
                    })
                }
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