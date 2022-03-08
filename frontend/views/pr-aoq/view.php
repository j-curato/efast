<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

$this->title = $model->aoq_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-aoq-view">

    <div class="container">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
        <?php
        // $aoq_items_array = [];
        $aoq_items_query = Yii::$app->db->createCommand("SELECT 
        pr_rfq_item.id as rfq_item_id,
    pr_purchase_request_item.quantity,
    pr_stock.stock_title as `description`,
    pr_purchase_request_item.specification,
    payee.account_name as payee,
    pr_aoq_entries.amount,
    pr_purchase_request.purpose,
    pr_aoq_entries.remark,
    unit_of_measure.unit_of_measure,pr_rfq.bac_composition_id
    FROM `pr_aoq_entries`
    LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
    LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
    LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id= pr_purchase_request_item.id
    LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
    LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id  = pr_stock.id
    LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
    LEFT JOIN pr_rfq ON pr_rfq_item.pr_rfq_id = pr_rfq.id
    WHERE pr_aoq_entries.pr_aoq_id = :id
    ")

            ->bindValue(':id', $model->id)
            ->queryAll();
        $result = ArrayHelper::index($aoq_items_query, null, 'rfq_item_id');
        $qqq = ArrayHelper::index($aoq_items_query, null, [function ($element) {
            return $element['rfq_item_id'];
        }, 'payee']);
        // var_dump($qqq[33]['PD, DTI-PDI']);
        $aoq_items_array  = ArrayHelper::index($aoq_items_query, 'payee');
        $header_count = count($aoq_items_array) + 5;
        $bac_compositions = Yii::$app->db->createCommand("SELECT 
            employee_search_view.employee_name,
            bac_position.position
            FROM bac_composition
            LEFT JOIN bac_composition_member ON bac_composition.id  = bac_composition_member.bac_composition_id
            LEFT JOIN bac_position ON bac_composition_member.bac_position_id = bac_position.id
            LEFT JOIN employee_search_view ON bac_composition_member.employee_id = employee_search_view.employee_id
            WHERE bac_composition.id = :bac_id
            ")
            ->bindValue(':bac_id', $aoq_items_query[0]['bac_composition_id'])
            ->queryAll();

        ?>

        <table>

            <thead>
                <tr>
                    <th colspan="<?= $header_count ?>" style='text-align:center'>
                        <span>
                            Department of Trade and Industry - Caraga
                        </span>
                        <br>
                        <span>
                            Regional Office XIII
                        </span>
                        <br>
                        <span>
                            Butuan City
                        </span>

                    </th>
                </tr>
                <tr>
                    <th colspan="<?= $header_count ?>" style='text-align:center'>
                        <span>
                            ABSTRACT OF CANVASS AND ACTION OF AWARDS
                        </span>
                    </th>
                </tr>
                <tr>
                    <th>Item No.</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Description</th>



                    <?php
                    $payee_position = [];
                    $payee_count  = 1;
                    foreach ($qqq as $i => $val) {

                        foreach ($val as $x => $q) {
                            $payee = $x;
                            echo "<td style='text-align:center'>
                                <span style='float:right'>$payee</span>
                            </td>";
                            $payee_position[$payee] = $payee_count;
                            $payee_count++;
                        }
                    }
                    ?>

                    </th>
                    <th>Lowest</th>

                </tr>
                <?php foreach ($result as $i => $val) {
                    $description = $val[0]['description'];
                    $specification =  $specs = preg_replace('#\[n\]#', "<br>", $val[0]['specification']);
                    $quantity = $val[0]['quantity'];
                    $unit_of_measure = $val[0]['unit_of_measure'];
                    echo " <tr><td></td><td> {$quantity}</td>
                        <td> {$unit_of_measure}</td>
                        <td><span>$description</span>
                        <br>
                        <span>$specification</span>
                        </td>
                        ";
                    foreach ($payee_position as $index => $payee) {
                        $x = !empty($qqq[$i][$index][0]['amount']) ? $qqq[$i][$index][0]['amount'] : '';
                        // var_dump( $qqq[$i]);
                        echo "<td>$x</td>";
                    }
                    echo "<td style='text-align:center'></td>";
                    // foreach ($val as $q) {
                    //     $amount = $q['amount'];
                    //     $remark = $q['remark'];
                    //     echo "<td style='text-align:center'>
                    //     <span style='float:right'>$amount</span>
                    //     <br>
                    //     <br>
                    //     <br>
                    //     <span >$remark</span>

                    // </td>";
                    // }
                    echo "</tr>";
                } ?>

            </thead>
            <tbody>
                <tr>
                    <td colspan="<?= $header_count ?>" style='border:none;padding-top:0'>
                        Based on the aboove abstract of canvass, it is recommended that the award be made to the Lowest Calculated and Responsive Bidder,
                    </td>
                </tr>

                <tr>
                    <td colspan="<?= $header_count ?>" class='no-border' style='padding-top:2em'>

                        <div class="bac-members">

                            <?php
                            $i = 1;
                            foreach ($bac_compositions as $val) {

                                if ($val['position'] === 'member') {

                                    $member_name =  ucwords($val['employee_name']);
                                    $member_position = ucwords($val['position']);
                                    echo "<div style='text-align:center'>
                                            <span style='text-decoration:underline'>$member_name</span>
                                            <br>
                                            <span >$member_position</span>
                                    </div>";
                                }
                            };
                            ?>
                        </div>
                    </td>

                </tr>
                <tr>
                    <td colspan="<?= $header_count ?>" class='no-border'>
                        <div style="float: left;margin-left:20%;text-align:center;margin-top:2em">
                            <?php $vice_chairperson =   $bac_compositions[array_search('vice chairperson', array_column($bac_compositions, 'position'))];
                            echo  "<span style='text-decoration:underline'>{$vice_chairperson['employee_name']}</span>";
                            echo '<br>';
                            echo 'Vice-Chairperson';
                            ?>
                        </div>
                        <div style="float: right;margin-right:20%;text-align:center;margin-top:2em">
                            <?php $chairperson =   $bac_compositions[array_search('chairperson', array_column($bac_compositions, 'position'))];
                            echo  "<span style='text-decoration:underline'>{$chairperson['employee_name']}</span>";
                            echo '<br>';
                            echo 'Chairperson';
                            ?>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>
<style>
    .no-border {
        border: 0;
    }

    .bac-members {
        display: flex;
        justify-content: space-evenly;

    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 2rem;
        border: 1px solid black;
    }

    .container {
        background-color: white;
        padding: 3em;
    }

    @media print {
        .main-footer {
            display: none;
        }

        .btn {
            display: none;
        }

        .container {
            padding: 0;
        }
    }
</style>