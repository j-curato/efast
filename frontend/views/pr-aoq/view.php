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
        REPLACE(pr_purchase_request_item.specification,'[n]','<br>') as specification,
        payee.account_name as payee,
        IFNULL(pr_aoq_entries.amount,'') as amount,
        pr_purchase_request.purpose,
        pr_aoq_entries.remark,
        pr_aoq_entries.is_lowest,
        unit_of_measure.unit_of_measure,
        pr_rfq.bac_composition_id
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
        $for_print = ArrayHelper::index($aoq_items_query, null, 'description');

        $result = ArrayHelper::index($aoq_items_query, null, 'rfq_item_id');
        $qqq = ArrayHelper::index($aoq_items_query, 'payee', [function ($element) {
            return $element['rfq_item_id'];
        }]);
        $aoq_items_array  = ArrayHelper::index($aoq_items_query, 'payee');
        $header_count = count($aoq_items_array) + 5;

        $bac_compositions = Yii::$app->db->createCommand("SELECT 
            employee_search_view.employee_name,
            LOWER(bac_position.position)as position
            FROM bac_composition
            LEFT JOIN bac_composition_member ON bac_composition.id  = bac_composition_member.bac_composition_id
            LEFT JOIN bac_position ON bac_composition_member.bac_position_id = bac_position.id
            LEFT JOIN employee_search_view ON bac_composition_member.employee_id = employee_search_view.employee_id
            WHERE bac_composition.id = :bac_id
            ")
            ->bindValue(':bac_id', $aoq_items_query[0]['bac_composition_id'])
            ->queryAll();

        ?>

        <table id="table">

            <thead>
                <tr>
                    <th colspan="<?= $header_count ?>" style='text-align:center;border:none;'>
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
                    <th colspan="<?= $header_count ?>" style='text-align:center;border:none;'>
                        <span>
                            ABSTRACT OF CANVASS AND ACTION OF AWARDS
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="<?= $header_count ?>" style='padding:0;border:none;'>
                        <?php
                        $date = DateTime::createFromFormat('Y-m-d', $model->pr_date);
                        echo $date->format('F d, Y');

                        ?>
                        <span style="float: right;">
                            <?php
                            echo $model->aoq_number;
                            ?>
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
                    $payee_count  = 5;
                    $payee_head_query = Yii::$app->db->createCommand("SELECT payee.account_name as payee
                    FROM `pr_aoq_entries`
                    LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                    WHERE pr_aoq_entries.pr_aoq_id = :id
                    GROUP BY payee.id
                    ")
                        ->bindValue(':id', $model->id)
                        ->queryAll();

                    foreach ($payee_head_query as $i => $val) {

                        $payee = $val['payee'];
                        echo "<th style='text-align:center'>
                                <span style='float:right'>$payee</span>
                            </th>";
                        $payee_position[$payee_count] = $payee;
                        $payee_count++;
                    }
                    ?>

                    </th>
                    <th>Lowest</th>

                </tr>
                <?php
                // foreach ($result as $i => $val) {
                //     $description = $val[0]['description'];
                //     $specification =  $specs = preg_replace('#\[n\]#', "<br>", $val[0]['specification']);
                //     $quantity = $val[0]['quantity'];
                //     $unit_of_measure = $val[0]['unit_of_measure'];
                //     echo " <tr><td></td><td> {$quantity}</td>
                //         <td> {$unit_of_measure}</td>
                //         <td><span>$description</span>
                //         <br>
                //         <span>$specification</span>
                //         </td>
                //         ";
                //     $min_amount   = min(array_column($val, 'amount'));
                //     $lowest = '';
                //     $comma_counter = 0;

                //     foreach ($payee_position as $index => $payee) {
                //         $x = !empty($qqq[$i][$index]['amount']) ? $qqq[$i][$index]['amount'] : '';
                //         // var_dump( $qqq[$i]);
                //         // if (intval(($qqq[$i][$index]['is_lowest']))) {
                //         //     if ($comma_counter > 0) {
                //         //         $lowest .= ',<br>';
                //         //     }
                //         //     $lowest .= $index . ' ';
                //         //     $comma_counter++;
                //         // }
                //         echo "<td>";
                //         echo $x;
                //         echo '<br>';
                //         echo '<br>';
                //         echo '<br>';
                //         // echo $qqq[$i][$index]['remark'];
                //         echo "</td>";
                //     }

                //     // echo "<td style='text-align:center'>$lowest</td>";
                //     foreach ($val as $q) {
                //         $amount = $q['amount'];
                //         $remark = $q['remark'];
                //         echo "<td style='text-align:center'>
                //         <span style='float:right'>$amount</span>
                //         <br>
                //         <br>
                //         <br>
                //         <span >$remark</span>

                //     </td>";
                //     }
                //     echo "</tr>";
                // } 
                ?>


            </thead>
            <tbody></tbody>
            <tfoot>
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

                                    $member_name =  strtoupper($val['employee_name']);
                                    $member_position = ucwords($val['position']);
                                    echo "<div style='text-align:center'>
                                            <span style='text-decoration:underline;font-weight:bold'>$member_name</span>
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
                            <?php
                            $search_vice =  array_search('vice-chairperson', array_column($bac_compositions, 'position'));
                            $vice_chairperson = '';
                            if (!empty($search_vice)) {
                                $vice_chairperson =   strtoupper($bac_compositions[$search_vice]['employee_name']);
                            }

                            echo  "<span style='text-decoration:underline;font-weight:bold'>{$vice_chairperson}</span>";

                            echo '<br>';
                            echo 'Vice-Chairperson';
                            ?>
                        </div>
                        <div style="float: right;margin-right:20%;text-align:center;margin-top:2em">
                            <?php
                            $search_chairperson =  array_search('chairperson', array_column($bac_compositions, 'position'));
                            $chairperson =  '';
                            // var_dump(array_column($bac_compositions, 'position'));
                            // var_dump($search_chairperson);

                            // if (!empty($search_chairperson)) {
                            $chairperson =   strtoupper($bac_compositions[$search_chairperson]['employee_name']);
                            // }
                            echo  "<span style='text-decoration:underline;font-weight:bold'>{$chairperson}</span>";
                            echo '<br>';
                            echo 'Chairperson';
                            ?>
                        </div>

                    </td>
                </tr>
            </tfoot>
        </table>

    </div>

</div>
<style>
    .no-border {
        border: 0;
    }

    td.fooed {
        position: relative;

        display: table-cell;
        vertical-align: text-bottom;
    }

    .foo {
        position: absolute;
        top: 2rem;
        left: 0px;
        right: 0px;
        bottom: 0px;
        display: table-cell;
        text-align: center;
        height: 100%;


    }

    .amount {
        text-align: center;
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
            min-width: 90%;
        }

        table {
            width: 100%;
        }

        th,
        td {
            padding: 6px;
        }

        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        const q = <?php echo json_encode($for_print) ?>;
        const payee_position = JSON.parse(`<?php echo json_encode($payee_position) ?>`);
        // console.log(payee_position)
        let row_number = 0
        let purpose = ''
        let remark_arr = []
        let remark_arr_index = 0
        $.each(q, function(key, val) {
            let min_key = ''
            $.each(val, function(key, val2) {
                min_key = key
                return false
            })
            const quantity = val[min_key]['quantity']
            const unit_of_measure = val[min_key]['unit_of_measure']
            const description = val[min_key]['description']
            const specification = val[min_key]['specification']
            purpose = val[min_key]['purpose']

            let row = `<tr>
            <td class='amount' style='vertical-align:top'>${row_number+1}</td>
            <td style='vertical-align:top'>${quantity}</td>
            <td style='vertical-align:top'>${unit_of_measure}</td>
            <td>
            <span style='font-weight:bold;vertical-align:top'>
            ${description}
            </span>
            </br>
            <span style='vertical-align:text-bottom;font-style:italic;'>
            ${specification}
            </span>
      
            </td>

          `;
            $.each(payee_position, function(key, val2) {
                row += `<td class='fooed' style='vertical-align:bottom' ></td>`;
            })
            row += `<td style='vertical-align:top'></td>`;
            $("#table tbody").append(row)

            let lowest = ''

            $.each(val, function(key, val2) {
                let key_pos = '';

                $.each(payee_position, function(key, payee) {
                    if (payee == val2.payee) {
                        key_pos = parseInt(key)

                        return false
                    }
                })
                if (key_pos != '') {
                    remark_arr[remark_arr_index] = {
                        'key_pos': key_pos,
                        'remark': val2.remark
                    };
                    remark_arr_index++
                }

                if (parseInt(val2.is_lowest) == 1) {
                    lowest = lowest + val2.payee
                }
                let key_pos_1 = 5
                const amount_to_display = ''
                if (val2.amount != '') {
                    amount_to_display = thousands_separators(val2.amount)
                }
                const amount = `<div class=foo><div >${ thousands_separators(val2.amount)}</div></div><br>`
                const remark = `<span>${val2.remark}</span>`

                $("#table tbody").find(`td:nth-child(${key_pos})`).eq(row_number).append(amount)
                // $("#table tbody").find(`td:nth-child(${key_pos})`).eq(row_number).append(remark)

            })
            $("#table tbody ").find(`td:last-child`).eq(row_number).text(lowest)
            row_number++
        })

        let colCount = 5
        console.log(row_number)
        let remark_row = `<tr>
            <td class='amount' style='vertical-align:top'></td>
            <td style='vertical-align:top'></td>
            <td style='vertical-align:top'></td>
            <td>
            <span style='font-weight:bold;vertical-align:top'>
            </span>
            </br>
            <span style='vertical-align:text-bottom;font-style:italic;'>
            </span>
      
            </td>

          `;

        $.each(payee_position, function(key, val2) {
            remark_row += `<td class='fooed' style='vertical-align:bottom' ></td>`;
            colCount++
        })
        remark_row += `<td style='vertical-align:top'></td>`;
        $("#table tbody").append(remark_row)
        $.each(remark_arr, function(key, val) {

            const pos = parseInt(val.key_pos)
            const remark = val.remark
            const table_col = $("#table tbody").find(`td:nth-child(${pos})`).eq(row_number)
            if (table_col.text() != '') {
                table_col.append(',<br>')
            }
            table_col.append(val.remark)
        })


        const purpose_row = `<tr><td colspan='${colCount}'><span style='font-weight:bold'>Purpose:  </span><span>${purpose}</span></td></tr>`;
        $("#table tbody").append(purpose_row)


    })
</script>