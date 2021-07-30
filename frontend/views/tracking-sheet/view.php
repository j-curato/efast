<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrackingSheet */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tracking Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tracking-sheet-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary','id'=>'update']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    $ors_number = !empty($model->process_ors_id) ? $model->processOrs->serial_number : '';
    $date = date('M d, Y', strtotime($model->created_at));
    $time = date('h:i A', strtotime($model->created_at));
    ?>
    <div class="container ">
        <table>
            <tbody>

                <tr>
                    <td colspan="" style="width:50px;" class="header"></td>
                    <td colspan="3" style="text-align: left;" class="header">Payee:
                        <span>

                            <?php echo $model->payee->account_name; ?> </span>
                    </td>
                    <td colspan="1" rowspan="2" class="header"> <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png', ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 70px;height:70px;margin-left:auto']); ?></td>
                </tr>
                <tr>
                    <td colspan="" style="width:50px;" class="header"></td>
                    <td colspan="3" class="header">Gross Amount:</td>
                </tr>

                <tr>
                    <td colspan="" style="width:50px;" class="header"></td>
                    <td colspan="3" class="header">Net Amount:<?php echo $ors_number ?></td>
                    <td class="header" style="padding-top: 10px;"><span>Particular</span></td>
                </tr>

                <tr>
                    <td colspan="" style="width:50px;" class="header"></td>
                    <td colspan="3" class="header">DV No. :</td>
                    <td colspan="1" rowspan="2">
                        <span>

                            <?php
                            echo $model->particular;

                            ?>
                        </span>

                    </td>
                </tr>
                <tr>
                    <td colspan="" style="width:50px;" class="header"></td>
                    <td colspan="3" class="header">ORS NO.:</td>
                </tr>

                <tr>

                    <td></td>
                    <td style="width:80px;" class="bold">DATE</td>
                    <td style="width:80px;" class="bold">TIME-IN</td>
                    <td style="width:80px;" class="bold">TIME-OUT</td>
                    <td class="bold">REMARKS</td>
                </tr>
                <tr>
                    <td style="width: 230px;" class="bold">Accounting Staff <br>
                        <span style="font-size: 9px;">
                            (Date and Time for Acknowledgin Reciept of DV's with complete documents)
                        </span>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold">Budget Officer <span></span>

                    </td>
                    <td><?php echo $date ?></td>
                    <td><?php echo $time ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold">Accountant II
                        <br>
                        <span style="font-size: 9px;">
                            Time in when the DV's were acknowledged to be complete and consistend or upon compliance of lacking documents, whichever is later.

                        </span><br>
                        <span style="font-size: 9px;">Time out left blank unless if Accountant II acts as OIC Chief Accountant</span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold">Chief Accountant <br>
                        <span style="font-size: 9px;">TIme in left blank unless if Accountant II is on leave or upon compliance of lacking documents, if any</span><br>
                        <span style="font-size: 9px;">Time out when the DV's were acknowledged to be complete,correct and consistent or upon compliance of lacking documents, whichever is later</span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="bold"><span>Note: Accounting Staff,then encodes to the web-based system the time-in and time-out from Accountants II and II, before Forwarding the DV's for RD's Signature. The "Process DV" module Shall be used for this.</span></td>

                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;font-weight:bold" class="bold"> Voucher at Cash Unit</td>

                </tr>
                <tr>

                    <td class="bold">Cashier</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                    <td class="bold">Cashier</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quas, in fuga dolore aspernatur molestias dolorem quidem dolor optio adipisci eius? Eligendi soluta perferendis excepturi placeat, vitae facilis commodi fugiat quia!</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <span style="float:right" class="bold">

                            TURN AROUND TIME:_____________________________
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


</div>
<style>
    table {
        padding: 20px;
    }

    .bold {
        font-weight: bold;
    }

    table,
    td,
    th {
        padding: 20px;
        border: 1px solid black;
    }

    .header {
        text-align: left;
        border: none;
        padding: 0;
    }
    .container{
        background-color: white;
        margin-bottom: 20px;
    }
    
    @media print {

        table,
        td,
        th {
            padding: 8px;
        }

        .btn {
            display: none;
        }
        .main-footer{
            display: none;
        }
        table{
           margin-bottom: 10px;
        }
    }
</style>
<?php
$script = <<< JS

        $('#update').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
JS;
$this->registerJs($script);
?>