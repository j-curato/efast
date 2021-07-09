<?php

use app\models\Raouds;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Dv Aucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dv-aucs-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="container panel panel-default">
        <p>
            <?= Html::a('Print', ['dv-form', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Add Link</button>

            <?php

            if (!empty($model->cashDisbursement)) {

                $t = yii::$app->request->baseUrl . "/index.php?r=cash-disbursement/view&id={$model->cashDisbursement->id}";
                echo  Html::a('Cash Disbursement Link', $t, ['class' => 'btn btn-success ']);
            }
            if ($model->is_cancelled) {
                echo "<button class='btn btn-success' id='cancel' style='margin:5px'>Activate</button>";
            } else {
                echo "<button class='btn btn-danger' id='cancel' style='margin:5px'>Cancel</button>";
            }
            echo "<input type='text' id='cancel_id' value='$model->id' style='display:none;'/>";
            $dv_link = '';
            if (!empty($model->dv_link)) {
                $dv_link = $model->dv_link;
                echo Html::a('Soft Copy Link', $dv_link, ['class' => 'btn btn-info ']);;
            }
            ?>

        </p>

        <div class="modal fade" id="uploadmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <form id="add_link">
                        <div class='modal-body'>
                            <hr>
                            <label for="ledger"> Insert Link</label>

                            <input type="text " style="display: none;" class="form-control" name="id" value='<?= $model->id ?>'>

                            <input type="text " class="form-control" name="link" value='<?= $dv_link ?>'>
                        </div>
                        <div class="row" style="margin: 10px;padding:12px">
                            <div class="col-sm-3">

                                <button type="submit" id='add_link_save' class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <table class="table table-striped">

            <tbody>
                <thead>
                    <th>
                        Obligation Number
                    </th>
                    <th>
                        DV Number
                    </th>
                    <th>
                        Reporting Period
                    </th>
                    <th>
                        Payee
                    </th>
                    <th>
                        Amount Disbursed
                    </th>
                    <th>
                        2306
                        (VAT / Non-Vat)
                    </th>
                    <th>
                        2307
                        (EWT Goods / Services)
                    </th>
                    <th>
                        1601C
                        (Compensation)
                    </th>
                    <th>
                        Tax Withheld
                    </th>
                    <th>
                        Other Trust Liabilities
                    </th>
                </thead>
            <tbody>

                <?php

                foreach ($model->dvAucsEntries as $val) {
                    $total_withheld = $val->compensation + $val->ewt_goods_services + $val->vat_nonvat;
                    $ors_serial_number = '';
                    $ors_serial_number = !empty($val->process_ors_id) ? $val->processOrs->serial_number : '';
                    $t = '';
                    if (!empty($val->process_ors_id)) {

                        $q = Raouds::find()
                            ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' =>  $val->process_ors_id])
                            ->one();
                        // $q = !empty($val->process_ors_id) ? $val->process_ors_id : '';
                        $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$q->id";
                    }

                    echo "
                    <tr>
                    <td>
                        {$ors_serial_number}
                    </td>
                    <td>
                        {$val->dvAucs->dv_number}
                    </td>
                    <td>
                        {$val->dvAucs->reporting_period}
                    </td>
                    <td>
                        {$val->dvAucs->payee->account_name}
                    </td>
                    <td >"
                        . number_format($val->amount_disbursed, 2) .
                        "</td>
                    <td>
                        " . number_format($val->vat_nonvat, 2) . "
                    </td>
                    <td>
                       " . number_format($val->ewt_goods_services, 2) . " 
                    </td>
                    <td>
                       " . number_format($val->compensation, 2) . " 
                    </td>
                    <td>
                        " . number_format($total_withheld, 2) . "
                    </td>
                    <td>
                        " . number_format($val->other_trust_liabilities, 2) . "
                    </td>
                    <td class='link'>" .

                        Html::a('ORS', $t, ['class' => 'btn-xs btn-success '])
                        . "
                
                </td>
                    </tr>
                    ";
                }
                // echo $model->dvAucsEntries;
                ?>
            </tbody>

            </tbody>
        </table>

    </div>
    <div class=" container panel panel-default">

        <table class="table table-striped">
            <thead>
                <th>Object Code</th>
                <th>Account Title</th>
                <th style='text-align:right'>Debit</th>
                <th style='text-align:right'>Credit</th>
            </thead>
            <tbody>


                <?php
                $total_debit = 0;
                $total_credit = 0;
                foreach ($model->dvAccountingEntries as $val) {

                    $account_title = '';
                    $total_debit += $val->debit;
                    $total_credit += $val->credit;

                    $debit = number_format($val->debit, 2);
                    $credit = number_format($val->credit, 2);
                    // if ($val->lvl === 2) {
                    //     $x = SubAccounts1::find()->where('object_code =:object_code', ['object_code' => $val->object_code])
                    //         ->one();
                    //     $account_title = $x->name;
                    // } else if ($val->lvl === 3) {
                    //     $y = SubAccounts2::find()->where('object_code =:object_code', ['object_code' => $val->object_code])
                    //         ->one();
                    //     $account_title = $y->name;
                    // } else if ($val->lvl === 1) {
                    //     $account_title = $val->chartOfAccount->general_ledger;
                    // }

                    $account_title = Yii::$app->db->createCommand("SELECT account_title FROM account_codes where object_code =:object_code")
                        ->bindValue(':object_code', $val->object_code)
                        ->queryOne();
                    // ob_clean();
                    // echo "<pre>";
                    // var_dump($val->object_code);
                    // echo "</pre>";
                    // return ob_get_clean();
                    echo "<tr>
                        <td>{$val->object_code}</td>
                        <td>{$account_title['account_title']}</td>
                        <td style='text-align:right'>$debit</td>
                        <td style='text-align:right'>$credit</td>
                    
                    </tr>";
                }
                ?>

                <tr>
                    <?php
                    echo "<tr>
                <td colspan='2' style='font-weight:bold'>Total</td>
                <td style='text-align:right'>" . number_format($total_debit, 2) . "</td>
                <td style='text-align:right'>" . number_format($total_credit, 2) . "</td>
                </tr>";
                    ?>
                </tr>
            </tbody>

        </table>
    </div>
    <style>
        .head {
            font-weight: bold;
        }

        .container {
            padding: 15px
        }

        .checkbox {

            margin-right: 4px;
            margin-top: 6px;
            height: 20px;
            width: 20px;
            border: 1px solid black;
        }

        /* td {
            border: 1px solid black;
            padding: 1rem;
            white-space: nowrap;
        } */

        table {
            margin: 12px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        @media print {
            .actions {
                display: none;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                font-size: 10px;
            }

            @page {
                size: auto;
                margin: 0;
                margin-top: 0.5cm;
            }

            .container {
                margin: 0;
                top: 0;
            }

            .entity_name {
                font-size: 5pt;
            }

            table,
            th,
            td {
                border: 1px solid black;
                padding: 5px;
                background-color: white;
            }

            .container {

                border: none;
            }

            table {
                page-break-after: auto
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto
            }

            /* thead {
        display: table-header-group
    } */

            .main-footer {
                display: none;
            }
        }
    </style>

</div>
<script>

</script>
<?php
SweetAlertAsset::register($this);
$script = <<<JS
    $('#add_link').submit((e) => {
        e.preventDefault();
        console.log('qwe')
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=dv-aucs/add-link',
            data: $("#add_link").serialize(),
            success: function(data) {
                $('#uploadmodal').modal('toggle');
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
                                        title:"Error Adding Fail",
                                        type:'error',
                                        button:false,
                                        timer:3000,
                                    })
                            }
            }
        })
    })
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
                        url:window.location.pathname + "?r=dv-aucs/cancel",
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