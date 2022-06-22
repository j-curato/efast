<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PreRepairInspection */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Pre Repair Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$accountable_person = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id")->bindValue(':employee_id', $model->fk_accountable_person)->queryOne();
$requested_by = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :employee_id")->bindValue(':employee_id', $model->fk_requested_by)->queryOne();
?>
<div class="pre-repair-inspection-view">




    <div class="container">
        <p>

            <?= Html::button(
                'Update',
                [
                    'value' => Url::to(Yii::$app->request->baseUrl . '?r=pre-repair-inspection/update&id=' . $model->id),
                    'class' => 'btn btn-primary modalButtoncreate'
                ]
            ); ?>

        </p>
        <table>

            <tbody>
                <tr>
                    <th colspan="4">HRAS-PROPERTY</th>
                </tr>
                <tr>
                    <th colspan="4">PRE/POST REPAIR INSPECTION</th>
                </tr>
                <tr>
                    <th colspan="4" style="border-bottom:0;">PRE REPAIR INSPECTION</th>
                </tr>
                <tr>
                    <th style="border-top:0;border-right:0;">

                        <span>
                            Equipment Type
                        </span>
                        <br>
                        <br>
                        <span< /span>
                    </th>
                    <th style="border-top:0;border-right:0;border-left:0;">

                        <span>
                            Serial No.
                        </span>
                        <br>
                        <br>
                        <span><?= $model->serial_number ?></span>
                    </th>
                    <th style="border-top:0;border-left:0;text-align:center;" colspan="2">
                        <span> End-User/Accountable Person</span>
                        <br>
                        <span><?php echo $accountable_person['employee_name'] ?></span>
                        <br>
                        <span><?php echo $accountable_person['position'] ?></span>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">FINDINGS</th>
                    <th colspan="2">RECOMMENDATION</th>
                </tr>
                <tr>
                    <td colspan="2"><?= $model->findings ?></td>
                    <td colspan="2"><?= $model->recommendation ?></td>
                </tr>
                <tr>
                    <td class="center">

                        <span class="pull-left" style='font-weight:bold;'> Requested by:</span>
                        <br>
                        <br>
                        <br>
                        <span style="font-weight: bold;text-decoration:underline;text-transform:uppercase;"><?= $requested_by['employee_name'] ?></span>
                        <br>
                        <span><?= $requested_by['position'] ?></span>

                    </td>
                    <td class="center">
                        <span class="pull-left" style='font-weight:bold;'>Reviewed by:</span>
                        <br>
                        <br>
                        <br>
                        <span>_______________________</span>
                    </td>
                    <td class="center">
                        <span class="pull-left" style='font-weight:bold;'>Inspected by:</span>
                        <br>
                        <br>
                        <br>
                        <span>_______________________</span>
                    </td>
                    <td class="center">
                        <span class="pull-left" style='font-weight:bold;'>Approved by:</span>
                        <br>
                        <br>
                        <br>
                        <span>_______________________</span>
                    </td>
                </tr>
                <tr>
                    <th>End-User</th>
                    <th>Property</th>
                    <th>Technical Inspector</th>
                    <th></th>
                </tr>
                <tr>
                    <td style="padding-top: 1rem;">
                        <br>
                        Date:_____________________
                    </td>
                    <td style="padding-top: 1rem;">
                        <br>
                        Date:_____________________
                    </td>
                    <td style="padding-top: 1rem;">
                        <br>
                        Date:_____________________
                    </td>
                    <td style="padding-top: 1rem;">
                        <br>
                        Date:_____________________
                    </td>
                </tr>
                <tr>
                    <th colspan="4" style="border-bottom: 0;">
                        <span>CERTIFICATION</span>

                    </th>
                </tr>
                <tr>

                    <td colspan="4" style="border-bottom:0 ;border-top:0;">
                        <br>
                        <p>
                            &emsp; As end-user/accountable person for the above equipment. I Certify that the Repair/Work/Replacement of parts done were satisfactorily completed and I hereby accept term. subject to post-repair inspection

                        </p>
                    </td>
                </tr>
                <tr>

                    <th colspan="2" style="border-top:0 ;border-right:0;padding-top:3rem;">
                        _____________________
                        <br>
                        Date
                    </th>
                    <td colspan="2" style="border-top:0 ;border-left:0;padding-top:3rem; text-align:center;">
                        <span style="text-decoration:underline;font-weight:bold;">__<?php echo $accountable_person['employee_name'] ?>__</span>
                        <br>
                        <span><?php echo $accountable_person['position'] ?></span>
                        <br>
                        End-user/Accountable Person
                    </td>
                </tr>
                <tr>
                    <th colspan="4">POST REPAIR INSPECTION</th>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom:0 ;border-right:0">Invoice No.</td>
                    <td style="border-bottom:0 ; border-left:0;border-right:0;">Date:</td>
                    <td style="border-bottom:0 ; border-left:0;">Amount:</td>
                </tr>
                <tr>
                    <td colspan="2" style="border-top:0 ;border-right:0">Work Order No.</td>
                    <td style="border-top:0 ; border-left:0;border-right:0;">Date</td>
                    <td style="border-top:0 ; border-left:0;">Amount:</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <p> &emsp;Inspected and found that the repair/work done/replacement of parts were satisfactorily completed in accordance with the specification and that the same is duly accepted by the end-user/accountable person.</p>
                    </td>

                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <span class="pull-left">
                            Inspected by:
                        </span>
                        <br>
                        <br>
                        <br>
                        <span>________________________</span>
                        <br>
                        <span>Technical Inspector</span>
                    </td>
                    <td colspan="2" style="text-align: center;">

                        <span class="pull-left">
                            NOTED:
                        </span>
                        <br>
                        <br>
                        <br>
                        <span>__________________________________________________________</span>
                        <br>
                        <br>
                    </td>

                </tr>

            </tbody>
        </table>
    </div>
</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    table {
        width: 100%;
    }

    .center {
        text-align: center;
    }

    th,
    td {
        border: 1px solid black;
        padding: 1rem;
    }

    th {
        text-align: center;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

        .container {
            padding: 0;
        }

        th,
        td {
            padding: 5px;
        }
    }
</style>
<?php
$script = <<<JS
        $('.modalButtoncreate').click(function(){
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('value'));
        });
JS;
$this->registerJs($script);
?>