<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="cash-disbursement-view">

    <div class=" panel panel-default">

        <p>
            <?= Html::a('Create Cash Disbursement', ['create'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>


            <?php
            // if ($model->is_cancelled) {
            //     echo "<button class='btn btn-success' id='cancel' style='margin:5px'>Activate</button>";
            // } else {
            //     echo "<button class='btn btn-danger' id='cancel' style='margin:5px'>Cancel</button>";
            // }
            echo "<input type='text' id='cancel_id' value='$model->id' style='display:none;'/>";
            $t = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/view&id=$model->dv_aucs_id";
            // echo  Html::a('DV Link', $t, ['class' => 'btn btn-info ', 'style' => 'margin:3px']);
            if (!empty($model->jevPreparation)) {
                $jev_link = yii::$app->request->baseUrl . "/index.php?r=jev-preparation/view&id={$model->jevPreparation->id}";
                echo  Html::a('JEV ', $jev_link, ['class' => 'btn btn-warning ', 'style' => 'margin:3px']);
            }
            if (!empty($model->transmittal->transmittal_id)) {
                $transmittal_link = yii::$app->request->baseUrl . "/index.php?r=transmittal/view&id={$model->transmittal->transmittal_id}";
                echo  Html::a('Transmittal ', $transmittal_link, ['class' => 'btn btn-link ', 'style' => 'margin:3px']);
            }
            if (!empty($model->sliie->id)) {
                echo  Html::a('SLIIE ', ['sliies/view', 'id' => $model->sliie->id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']);
            }
            if (!empty($model->lddapAda->id)) {
                echo  Html::a('LDDAP-ADA ', ['lddap-adas/view', 'id' => $model->lddapAda->id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']);
            }
            ?>
        </p>
        <table id="check_details_tbl" style="float: left; margin-right:2rem;margin-bottom:4rem">

            <th colspan="4" class="ctr">
                Disbursement Details
            </th>

            <tr>
                <th>Reporting Period:</th>
                <th><?= $model->reporting_period ?></th>
                <th>Book:</th>
                <th><?= $model->book->name ?></th>

            </tr>
            <tr>
                <th>Mode of Payment:</th>
                <th><?= $model->modeOfPayment->name ?></th>
                <th>Check No.:</th>
                <th><?= $model->check_or_ada_no ?></th>
            </tr>
            <tr>
                <th>Issunce Date: </th>
                <th><?= $model->issuance_date ?></th>
                <th>ADA No.:</th>
                <th><?= $model->ada_number ?></th>
            </tr>
            <tr>
                <th>Begin timer:</th>
                <th><?= date('h:i A', strtotime($model->begin_time)) ?></th>
                <th>Out Time:</th>
                <th><?= date('h:i A', strtotime($model->out_time)) ?></th>
            </tr>
        </table>
        <table id="summary_tbl">

            <th colspan="3" class="ctr">
                Summary per UACS
            </th>
            <?php

            foreach ($summary as $sum) {

                echo "<tr>
                    <th>{$sum['general_ledger']}</th>
                    <th class='amt'>" . number_format($sum['total'], 2) . "</th>
                </tr>";
            }
            ?>
        </table>




        <table class=" items_tbl table table striped" style="margin-top: 8rem;">

            <thead>
                <tr class="success">
                    <th colspan="9" class="ctr">
                        <h3>
                            DV'S

                        </h3>
                    </th>
                </tr>
                <th>Book</th>
                <th>DV No.</th>
                <th>Particular</th>
                <th>Payee</th>
                <th>ORS</th>
                <th>UACS</th>
                <th>Amount Disbursed</th>
                <th>Withholding Tax</th>
                <th>Gross Amount</th>
            </thead>
            <tbody>

                <?php
                $grndTtlAmtDisbursed = 0;
                $grndTtlTax = 0;
                $grndGrossAmt = 0;
                foreach ($items as $itm) {
                    $grndTtlAmtDisbursed += floatval($itm['ttlAmtDisbursed']);
                    $grndTtlTax += floatval($itm['ttlTax']);
                    $grndGrossAmt += floatval($itm['grossAmt']);
                    echo "<tr>
                        <td>{$itm['book_name']}</td>
                        <td>{$itm['dv_number']}</td>
                        <td>{$itm['particular']}</td>
                        <td>{$itm['payee']}</td>
                        <td>{$itm['orsNums']}</td>
                        <td >
                        {$itm['chart_of_acc']}
                    </td>
                        <td>" . number_format($itm['ttlAmtDisbursed'], 2) . "</td>
                        <td>" . number_format($itm['ttlTax'], 2) . "</td>
                        <td>" . number_format($itm['grossAmt'], 2) . "</td>
                    
                    </tr>";
                }
                echo "<tr class='warning'>
                
                <th colspan='6' style='text-align:center'>Total</th>
                <th>" . number_format($grndTtlAmtDisbursed, 2) . "</th>
                <th>" . number_format($grndTtlTax, 2) . "</th>
                <th>" . number_format($grndGrossAmt, 2) . "</th>
                </tr>";
                ?>
            </tbody>
        </table>
    </div>

</div>

<style>
    .amt {
        text-align: right;
    }

    .panel {
        padding: 2rem;
    }

    .ctr {
        text-align: center;
    }

    .items_tbl>th,
    .items_tbl>td {
        text-align: center;
    }

    .ctr {
        text-align: center;
    }

    #summary_tbl th,
    #check_details_tbl th {
        padding: 1rem;
        border: 1px solid black;
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
                        url:window.location.pathname + "?r=cash-disbursement/cancel",
                        data:{
                            id:$("#cancel_id").val()
                        },
                        success:function(data){
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