<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="liquidation-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="container panel panel-default">

        <p>
            <?= Html::a('Re-Align/Update', ['re-align', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?php

            if ($model->is_cancelled) {
                echo "<button class='btn btn-success' id='cancel' style='margin:5px'>Activate</button>";
            } else {
                echo "<button class='btn btn-danger' id='cancel' style='margin:5px'>Cancel</button>";
            }
            echo "<input type='text' id='cancel_id' value='$model->id' style='display:none;'/>";
            ?>
        </p>
        <table class="table table-striped">
            <thead>
                <th>Reporting Period</th>
                <th>NFT Number</th>
                <th>Report</th>
                <th>Province</th>
                <th>Fund Source</th>
                <th>UACS Object Code</th>
                <th>General Ledger</th>
                <th class='number'>Withdrawals</th>
                <th class='number'>tax1</th>
                <th class='number'>tax2</th>
            </thead>
            <tbody>

                <?php
                $total_withdrawal = 0;
                $total_vat_nonvat = 0;
                $total_ewt = 0;
                // foreach ($model->liquidationEntries as $val) {

                //     $total_withdrawal += $val->withdrawals;
                //     $total_vat_nonvat += $val->vat_nonvat;
                //     $total_ewt += $val->ewt_goods_services;
                //     echo "<tr>
                // <td>{$val->reporting_period}</td>
                // <td>{$val->advances->nft_number}</td>
                // <td>{$val->advances->report_type}</td>
                // <td>{$val->advances->province}</td>
                // <td>{$val->advances->particular}</td>
                // <td>{$val->chartOfAccount->uacs}</td>
                // <td>{$val->chartOfAccount->general_ledger}</td>
                // <td class='number'>" . number_format($val->withdrawals, 2) . "</td>
                // <td class='number'>" . number_format($val->vat_nonvat, 2) . "</td>
                // <td class='number'>" . number_format($val->ewt_goods_services, 2) . "</td>
                
                // </tr>";
                // }

                echo "<tr>
                <td colspan='7' style='text-align:center;font-weight:bold;'>Total</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_withdrawal, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_vat_nonvat, 2) . "</td>
                <td class='number' style='font-weight:bold'>" . number_format($total_ewt, 2) . "</td>
                </tr>";
                ?>
            </tbody>
        </table>
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
                        url:window.location.pathname + "?r=liquidation/cancel",
                        data:{
                            id:$("#cancel_id").val()
                        },
                        success:function(data){
                            console.log(data)
                            var res = JSON.parse(data)
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
                            }
                            else{
                                swal({
                                        title:"Error Cannot Cancel",
                                        text:"Dili Ma  Cancel ang Disbursment Niya",
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

JS;

$this->registerJs($script);

?>