<?php

use app\models\Raouds;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Dv Aucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dv-aucs-view ">

    <div class="modal fade" id="dvLinkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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

                        <input type="text " class="form-control" name="link" value='<?= $model->dv_link ?? '' ?>'>
                    </div>
                    <div class="row" style="margin: 10px;padding:12px">
                        <div class="col-sm-1 col-sm-offset-5">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cashLInksModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <table class="table">

                    <tr>
                        <th>Check No.</th>
                        <th>Good/Cancelled</th>
                        <th>Link</th>
                    </tr>
                    <?php foreach ($cashIds as $itm) {

                        echo "<tr>
                            <td class='ctr'>{$itm['check_or_ada_no']}</td>
                            <td class='ctr'>";
                        echo  $itm['is_cancelled'] == 0 ? 'Good' : 'Cancelled';
                        echo "</td>
                            <td class='ctr'>" . Html::a('Link', ['cash-disbursement/view', 'id' => $itm['id']]) . "</td>
                        </tr>";
                    } ?>
                </table>
            </div>
        </div>
    </div>
    <div class="container card">
        <h3><?= Html::encode($this->title) ?></h3>

        <p>
            <?= Html::a('Print', ['dv-form', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>



            <?php

            if (!empty($cashIds)) {

                // $t = yii::$app->request->baseUrl . "/index.php?r=cash-disbursement/view&id={$model->cashDisbursement->id}";
                // echo  Html::a('Cash Disbursement Link', $t, ['class' => 'btn btn-success ']);
                echo Html::button('Cash Disbursement Links', ['class' => 'btn btn-info', 'data-target' => "#cashLInksModal", 'data-toggle' => "modal"]);
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
                echo Html::a('Soft Copy Link', $dv_link, ['class' => 'btn btn-info ']);
            }

            ?>
            <?php
            $jev_link = yii::$app->request->baseUrl . "/index.php?r=jev-preparation/dv-to-jev&id={$model->id}";

            if ($model->is_payable === 1) {
                echo "<button class='btn btn-success' id='is_payable' style='margin:5px'>Not Payable</button>";


                $exist  = Yii::$app->db->createCommand("SELECT id FROM jev_preparation WHERE dv_number = :dv_number
                    
                ")->bindValue(':dv_number', $model->dv_number)
                    ->queryOne();
                if (!empty($exist)) {
                    $j = yii::$app->request->baseUrl . "/index.php?r=jev-preparation/view&id={$exist['id']}";
                    echo Html::a('Payable JEV', $j, ['class' => 'btn btn-primary']);
                } else {
                    echo Html::a('To JEV', $jev_link, ['class' => 'btn btn-primary']);
                }
            } else {
                echo "<button class='btn btn-danger' id='is_payable' style='margin:5px'>Payable</button>";
            }
            // if (!empty($model->dvAucsFile->id)) {
            //     $dv_number =  "/scanned-dv" . "/" . $model->dv_number;
            //     $path =  Url::base() . "/frontend"  . $dv_number . "/" . $model->dvAucsFile->file_name;
            //     echo Html::a('Download Soft Copy ', $path, ['class' => 'btn btn-link ']);
            // } else {
            //     echo '<button class="btn btn-success" data-target="#uploadmodal" data-toggle="modal">Upload Soft Copy</button>';
            // }

            echo Html::a(empty($model->dv_link) ? 'Add File Link' : 'Update File Link', ['add-link', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']);
            if (!empty($model->dv_link)) {
                echo Html::a('DV Scanned Copy Link ', $model->dv_link, ['class' => 'btn btn-link', 'target' => '_blank']);
            }
            if (!empty($transmittalId)) {
                echo Html::a('Transmittal Link ', ['transmittal/view', 'id' => $transmittalId], ['class' => 'btn btn-link', 'target' => '_blank']);
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
        <table class="">

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
                        Particular
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
                    if (intval($val->is_deleted) != 1) {

                        $total_withheld = $val->compensation + $val->ewt_goods_services + $val->vat_nonvat;
                        $ors_serial_number = '';
                        $ors_serial_number = !empty($val->process_ors_id) ? $val->processOrs->serial_number : '';
                        $t = '';
                        if (!empty($val->process_ors_id)) {

                            // $q = Raouds::find()
                            //     ->where('raouds.process_ors_id = :process_ors_id', ['process_ors_id' =>  $val->process_ors_id])
                            //     ->one();
                            // $q = !empty($val->process_ors_id) ? $val->process_ors_id : '';
                            $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/view&id=$val->process_ors_id";
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
                    <td>
                        {$val->dvAucs->particular}
                    </td>
                    <td  class='amount'>"
                            . number_format($val->amount_disbursed, 2) .
                            "</td>
                    <td class='amount'>
                        " . number_format($val->vat_nonvat, 2) . "
                    </td>
                    <td class='amount'>
                       " . number_format($val->ewt_goods_services, 2) . " 
                    </td>
                    <td class='amount'>
                       " . number_format($val->compensation, 2) . " 
                    </td>
                    <td class='amount'>
                        " . number_format($total_withheld, 2) . "
                    </td>
                    <td class='amount'>
                        " . number_format($val->other_trust_liabilities, 2) . "
                    </td>
                    <td class='link'>" .

                            Html::a('ORS', $t, ['class' => ' btn btn-xs btn-success '])
                            . "
                
                </td>
                    </tr>
                    ";
                    }
                }
                // echo $model->dvAucsEntries;
                ?>
            </tbody>

            </tbody>
        </table>

    </div>
    <?php
    $advances = Yii::$app->db->createCommand("SELECT 
            advances.province,
          advances.nft_number,
          advances_entries.report_type,
          advances_entries.fund_source_type,
          advances_entries.fund_source,
          accounting_codes.object_code,
          accounting_codes.account_title,
          advances_entries.amount,
          CONCAT(bank_account.account_number,'-',bank_account.account_name) as bank_account
          
          FROM advances
          LEFT JOIN advances_entries ON advances.id = advances_entries.advances_id
          LEFT JOIN accounting_codes ON advances_entries.object_code = accounting_codes.object_code
          LEFT JOIN bank_account ON advances.bank_account_id = bank_account.id
          WHERE advances.dv_aucs_id = :dv_id
          AND advances_entries.is_deleted !=1
           ")
        ->bindValue(':dv_id', $model->id)
        ->queryAll();
    if (!empty($advances)) {

    ?>
        <div class=" container card ">

            <table class="">
                <thead>
                    <th>Province</th>
                    <th>NFT Number</th>
                    <th>Bank Account</th>
                    <th>Report Type</th>
                    <th>Fund Source Type</th>
                    <th>Fund Source</th>
                    <th>Object Code</th>
                    <th>Account Title</th>
                    <th>Amount</th>

                </thead>
                <tbody>
                    <?php


                    foreach ($advances as $i => $val) {
                        $amount = number_format($val['amount'], 2);
                        echo "<tr>
                        <td>{$val['province']}</td>
                        <td>{$val['nft_number']}</td>
                        <td>{$val['bank_account']}</td>
                        <td>{$val['report_type']}</td>
                        <td>{$val['fund_source_type']}</td>
                        <td>{$val['fund_source']}</td>
                        <td>{$val['object_code']}</td>
                        <td>{$val['account_title']}</td>
                        <td style='text-align:right'>{$amount}</td>
                    
                    </tr>";
                    }


                    ?>

                </tbody>



            </table>

        </div>
    <?php } ?>
    <div class=" container card panel-default">

        <table class="">
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
                $is_even = '';

                foreach ($model->dvAccountingEntries as $i => $val) {

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

                    $find_account_title = Yii::$app->db->createCommand("SELECT IFNULL(account_title,'') as account_title  FROM accounting_codes
                        where object_code =:object_code")
                        ->bindValue(':object_code', $val->object_code)
                        ->queryOne();
                    $object_code = !empty($val->object_code) ? $val->object_code : '';
                    $account_title = !empty($find_account_title['account_title']) ? $find_account_title['account_title'] : '';

                    if ($i % 2 === 0) {
                        $is_even = 'even';
                    } else {
                        $is_even = '';
                    }
                    echo "<tr class='$is_even'>
                        <td>{$object_code}</td>
                        <td>{$account_title}</td>
                        <td  class='amount'>$debit</td>
                        <td  class='amount'>$credit</td>
                    </tr>";
                }

                echo "<tr class='total'>
                        <td colspan='2' style='font-weight:bold'>Total</td>
                        <td style='text-align:right'>" . number_format($total_debit, 2) . "</td>
                        <td style='text-align:right'>" . number_format($total_credit, 2) . "</td>
                    </tr>";
                ?>

            </tbody>



        </table>
        <table class='assig' style="border: 0;">
            <thead>
                <tr>

                    <td class='q' style="text-align: center;">

                        <span>
                            <h6>
                                Certified Correct:
                            </h6>
                        </span>
                        <br>
                        <span style="margin-left: 25em; text-decoration:underline;font-weight:bold">
                            CHARLIE C. DECHOS, CPA</h5>
                        </span>
                        <br>
                        <span style="margin-left: 25em;">
                            Accountant III
                        </span>
                        <!-- <div style="text-align: center;">
                            <div style="width: 70px;height:50px;margin-left:auto;margin-right:auto">

                            </div>
             
                            <h5>
                                CHARLIE C. DECHOS, CPA
                            </h5>

                            <h6>
                                Accountant III, Designate
                            </h6>
                        </div> -->
                    </td>
                </tr>
            </thead>
        </table>
    </div>

</div>
<style>
    .ctr {
        text-align: center;
    }

    .head {
        font-weight: bold;
    }

    #asig>td,
    th {
        border: 0;
    }

    .total td {
        font-weight: bold;
    }

    .amount {
        text-align: right;
    }

    .total {
        background-color: #d5ff80;
    }

    .even {
        background-color: #cce6ff;
    }

    .container {
        padding: 15px
    }

    .q {
        margin-top: 3rem;
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

    table,
    th,
    td {
        padding: 10px;
    }

    th {
        text-align: center;
    }

    @media print {
        .actions {
            display: none;
        }

        .link {
            display: none;
        }

        .btn {
            display: none;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            /* font-size: 10px; */
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

        .assig td {
            border: 0;
        }

        .assig {
            border: 0;
        }

        h1 {
            font-size: 12px;
        }


        .main-footer {
            display: none;
        }
    }
</style>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>
<script>
    $(document).ready(function() {

        $('#download_soft_copy').click((e) => {
            e.preventDefault()
            console.log($('#download_soft_copy').attr('file-url'))
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=report/q',
                data: {
                    url: $('#download_soft_copy').attr('file-url')
                },
                success: function(res) {
                    window.open(res)
                }
            })
        })
        $('#add_link').submit((e) => {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=dv-aucs/add-link',
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
    })
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
            // text: "You will not be able to recover this imaginary file!",
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
                                        text:res.cancelled,
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
    $("#is_payable").click(function(){
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
         },
         function(isConfirm){

           if (isConfirm){
                    $.ajax({
                        type:"POST",
                        url:window.location.pathname + "?r=dv-aucs/is-payable",
                        data:{
                            id:$("#cancel_id").val()
                        },
                        success:function(data){
                            var res = JSON.parse(data)
                            var cancelled = res.cancelled?"Successfuly Cancelled":"Successfuly Activated";
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

<?php
$script = <<<JS
            var i=false;
            $('#import').on('beforeSubmit',function(e){
                // $(this).unbind();
                e.preventDefault();
                    
                //  $("#employee").on("pjax:success", function(data) {
                    //   console.log(data)
                    // });
                    
                    if (!i){
                        i=true;
                        $.ajax({
                            url: window.location.pathname + "?r=dv-aucs/file",
                            type:'POST',
                            data:  new FormData(this),
                            contentType: false,
                            cache: false,
                            processData:false,
                            success:function(data){
                                var res = JSON.parse(data)
                                if (res.isSuccess){
                                    swal( {
                                        icon: 'success',
                                        title: "Successfuly Added",
                                        type: "success",
                                        timer:3000,
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    },function(){
                                        location.reload();
                                    })
                                }
                                else{
                                    const error_message = res.error_message.file[0]
                                    swal( {
                                        icon: 'error',
                                        title: error_message,
                                        type: "error",
                                        timer:10000,
                                        closeOnConfirm: false,
                                        closeOnCancel: false
                                    })
                                    i=false;
                                }
                    },
                    
                    
                    
                    // data:$('#import').serialize()
                })
                
                 return false; 
                }
                
            })

             
        
JS;
$this->registerJs($script);
?>