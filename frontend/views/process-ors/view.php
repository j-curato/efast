<?php

use app\models\ProcessOrsNewView;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrs */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Process Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="process-ors-view">

    <?php
    $ors  = $model;
    $entries = ProcessOrsNewView::find()->where('id = :id', ['id' => $model->id])->all();
    ?>


    <div class="panel panel-default container" style="background-color: white;">
        <h4 style=""><?= Html::encode($this->title) ?></h4>
        <p>
            <?= Html::a('Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update/Re-Align', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php
            if ($ors->is_cancelled) {
                echo "
                <button class='btn btn-success' id='cancel'>
                    Activate
                </button>
                ";
            } else {
                echo "
                <button class='btn btn-danger' id='cancel'>
                    Cancel
                </button>
                ";
            }
            echo "<input type='text' id='cancel_id' value='$ors->id' style='display:none'/>";
            $t = yii::$app->request->baseUrl . "/index.php?r=transaction/view&id=$ors->transaction_id";
            echo  Html::a('Transaction', $t, ['class' => 'btn btn-info']);
            $adjust = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/re-align&id=$model->id";
            ?>
        </p>
        <table class="table" style="margin-bottom: 2rem;">
            <thead>
                <tr class="info ">
                    <th colspan="9" class="center">
                        <h4> <b>Transaction Allotments </b></h4>
                    </th>
                </tr>
                <th>Responsible Center</th>
                <th>Particular</th>
                <th>Payee</th>
                <th>Book</th>
                <th> Allotment Number</th>
                <th> MFO/PAP</th>
                <th> Fund Source</th>
                <th> UACS</th>
                <th> Amount</th>
            </thead>
            <tbody>

                <?php
                $txnTtl  = 0;
                foreach ($orsTxnAllotments as $item) {
                    $particular = $item['particular'];
                    $allotment_number = $item['allotment_number'];
                    $responsibilityCenter = $item['responsibilityCenter'];
                    $mfo_name = $item['mfo_name'];
                    $fund_source_name = $item['fund_source_name'];
                    $account_title = $item['account_title'];
                    $uacs = $item['uacs'];
                    $book_name = $item['book_name'];
                    $payee = $item['payee'];
                    $itemAmt = $item['itemAmt'];

                    echo "<tr>
                        <td>$responsibilityCenter</td>
                        <td>$particular</td>
                        <td>$payee</td>
                        <td>$book_name</td>
                        <td>$allotment_number</td>
                        <td>$mfo_name</td>
                        <td>$fund_source_name</td>
                        <td>$uacs - $account_title</td>
                        <td class='amount'> 
                           " . number_format($itemAmt, 2) . " 
                        </td>
                    </tr>";
                    $txnTtl += floatval($itemAmt);
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="warning">

                    <th colspan="8" class="center">Total</th>
                    <th class="amount"><?= number_format($txnTtl, 2) ?></th>
                </tr>
            </tfoot>
        </table>
        <table class="table table-striped">

            <thead>
                <tr class="info ">
                    <th colspan="9" class="center">
                        <h4> <b>ORS Entries </b></h4>
                    </th>
                </tr>

                <th>
                    Reporting Period
                </th>
                <th>
                    Allotment Number
                </th>
                <th>MFO/PAP</th>
                <th>Fund Source</th>
                <th>
                    Allotment UACS
                </th>
                <th>
                    Entry UACS
                </th>

                <th style='text-align:right'>
                    Amount
                </th>
            </thead>
            <tbody>
                <?php
                $orsItmTtl = 0;
                // foreach ($entries as $key => $val) {
                //     $count = $key + 1;
                //     echo "
                // <tr>
                //     <td>
                //        {$count}
                //     </td>
                //     <td>
                //        {$val->reporting_period}
                //     </td>
                //     <td>
                //        {$val->reporting_period}
                //     </td>
                //     <td>
                //        {$val->payee}
                //     </td>
                //     <td>
                //        {$val->particular}
                //     </td>
                //     <td>
                //        {$val->allotment_uacs}
                //     </td>

                //     <td>
                //        {$val->allotment_account_title}
                //     </td>

                //     <td>
                //        {$val->ors_uacs}
                //     </td>

                //     <td>
                //        {$val->ors_account_title}
                //     </td>

                //     <td class='amount'>" . number_format($val->amount, 2) . "

                //     </td>


                // </tr>

                // ";
                //     $total += $val->amount;
                // }
                foreach ($GetOrsItems as $orsItm) {
                    echo "<tr>
                            <td>{$orsItm['reporting_period']}</td>
                            <td>{$orsItm['serial_number']}</td>
                            <td>{$orsItm['mfo_code']}-{$orsItm['mfo_name']}</td>
                            <td>{$orsItm['fund_source']}</td>
                            <td>{$orsItm['allotment_uacs']}-{$orsItm['allotment_ledger']}</td>
                            <td>{$orsItm['uacs']}-{$orsItm['general_ledger']}</td>
                            <td class='amount'>" . number_format($orsItm['amount'], 2) . "</td>
                        </tr>";
                    $orsItmTtl += floatval($orsItm['amount']);
                }
                ?>
                <tr>
                <tr class="warning">

                    <td colspan="6" style='text-align:center;font-weight:bold;'>Total</td>
                    <td style='text-align:right;font-weight:bold;'><?php echo number_format($orsItmTtl, 2); ?></td>
                </tr>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container dv_links">


        <h4>List of DV's Using This ORS</h4>
        <table class="table">
            <thead>
                <th>
                    DV Number
                </th>
                <th>
                    Link
                </th>
            </thead>
            <tbody>

                <?php
                if (!empty($ors->dvAucsEntries)) {
                    $dv_id = 0;
                    foreach ($ors->dvAucsEntries as $val) {
                        if (intval($val->is_deleted) === 0) {

                            $x = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/view&id={$val->dvAucs->id}";
                            echo "<tr>
                        <td>{$val->dvAucs->dv_number}</td>
                        <td>" .
                                Html::a('Dv Link', $x, ['class' => 'btn-xs btn-danger '])
                                . "</td>
                        </tr>";
                        }
                    }

                    // http://10.20.17.33/dti-afms-2/frontend/web/index.php?r=dv-aucs%2Fview&id=6878
                    // echo  
                }
                ?>
            </tbody>

        </table>
    </div>

</div>

<style>
    .container {
        background-color: white;
        padding: 12px;
    }

    .amount {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    @media print {

        .btn,
        .main-footer,
        .dv_links {
            display: none;
        }
    }
</style>
<?php

SweetAlertAsset::register($this);
$script = <<< JS

    $("#cancel").click(function(){
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this imaginary file!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#DD6B55',
        confirmButtonText: 'Yes, I am sure!',
        cancelButtonText: "No, cancel it!",
        closeOnConfirm: false,
        closeOnCancel: true
    },
    function(isConfirm){

    if (isConfirm){
                $.ajax({
                    type:"POST",
                    url:window.location.pathname + "?r=process-ors-entries/cancel",
                    data:{
                        id:$("#cancel_id").val()
                    },
                    success:function(data){
                        var res = JSON.parse(data)
                        // swal({
                        //     title:"Success",
                        //     type:'success',
                        //     button:false,
                        //     timer:3000,
                        //  })
                        console.log(data)
                        var cancelled = res.cancelled?"Successfuly Cancelled":"Successfuly Activated";
                        if(res.isSuccess){
                            swal({
                                title:cancelled,
                                type:'success',
                                button:false,
                                timer:3000,
                            },function(){
                                location.reload(true)
                            })
                        }else{
                            swal({
                                title:"Error",
                                text:res.error,
                                type:'error',
                                button:false,
                                timer:3000,
                            })
                        }
                    }
                })


        } 
    })

    })
    $(document).ready(function(){
        
    })

JS;
$this->registerJs($script);

?>