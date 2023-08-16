<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

SweetAlertAsset::register($this);
$this->title = $model->aoq_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-aoq-view">

    <div class="" style="background-color: white;padding:1rem">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php
            if (Yii::$app->user->can('super-user')) {
                $btn_color = $model->is_cancelled ? 'btn btn-success' : 'btn btn-danger';
                $cncl_txt = $model->is_cancelled ? 'UnCancel' : 'Cancel';
                if (!$model->is_cancelled) {
                    echo  Html::a($cncl_txt, ['cancel', 'id' => $model->id], [
                        'class' => $btn_color,
                        'id' => 'cancel'

                    ]);
                }
            }
            echo   Html::a('RFQ Link ', ['pr-rfq/view', 'id' => $model->pr_rfq_id], ['class' => 'btn btn-warning ', 'style' => 'margin:3px'])
            ?>
        </p>
        <?php
        $for_print = ArrayHelper::index($aoq_items_query, null, 'rfq_item_id');
        $result = ArrayHelper::index($aoq_items_query, null, 'rfq_item_id');
        $qqq = ArrayHelper::index($aoq_items_query, 'payee', [function ($element) {
            return $element['rfq_item_id'];
        }]);
        $aoq_items_array  = ArrayHelper::index($aoq_items_query, 'payee');
        $header_count = count($aoq_items_array) * 2 + 5;



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
                        $date = DateTime::createFromFormat('Y-m-d', $model->rfq->deadline);
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
                    <th rowspan="3">Item No.</th>
                    <th rowspan="3">Qty</th>
                    <th rowspan="3">Unit</th>
                    <th rowspan="3">Description</th>

                </tr>
                <?php
                $payeeRow = "<tr>";
                $payeeHeaderRow = "<tr>";
                $payee_position = [];
                $payee_count  = 5;
                $payee_head_query = Yii::$app->db->createCommand("SELECT IFNULL(payee.registered_name,payee.account_name) as payee
                    FROM `pr_aoq_entries`
                    LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
                    WHERE pr_aoq_entries.pr_aoq_id = :id
                    GROUP BY payee.account_name
                    ")
                    ->bindValue(':id', $model->id)
                    ->queryAll();

                foreach ($payee_head_query as $i => $val) {

                    $payee = $val['payee'];
                    $payeeRow .= "<th style='text-align:center' colspan='2'>
                                <span>$payee</span>
                            </th>";
                    $payeeHeaderRow .= "<th class='center'>Unit Cost</th><th class='center'>Gross Amount</th>";
                    $payee_position[$payee_count] = $payee;

                    $payee_count += 2;
                }
                echo $payeeRow . "<th class='center'>Lowest</th>";
                echo $payeeHeaderRow . "<th></th>";
                ?>



            </thead>
            <tbody></tbody>
            <tfoot>
                <tr>
                    <td colspan="<?= $header_count ?>" style='border:none;padding-top:0'>
                        Based on the above abstract of canvass, it is recommended that the award be made to the Lowest Calculated and Responsive Bidder,
                    </td>
                </tr>

                <tr>
                    <td colspan="<?= $header_count ?>" class='no-border' style='padding-top:2em'>

                        <div class="bac-members">

                            <?php
                            $i = 1;
                            foreach ($bac_compositions as $val) {

                                if (strtolower($val['position']) === 'member') {

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
                    <td colspan="<?= $header_count  ?>" class='no-border'>
                        <div style="float: left;margin-left:20%;text-align:center;margin-top:2em">
                            <?php
                            $search_vice =   array_search('vice-chairperson', array_column($bac_compositions, 'position'));;
                            // var_dump($bac_compositions);
                            // var_dump($search_vice);
                            // $vice_chairperson = '';
                            // if (!empty($search_vice)) {
                            //     $vice_chairperson =   strtoupper($bac_compositions[$search_vice]['employee_name']);
                            // }
                            $vice_chairperson =   strtoupper($bac_compositions[$search_vice]['employee_name'] ?? '');
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
                            $chairperson =   strtoupper($bac_compositions[$search_chairperson]['employee_name'] ?? '');
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
        <table class="links_table table table-stripe">

            <tbody>

                <tr class="danger">
                    <th colspan="3" style="text-align: center;">PO Links</th>
                </tr>

                <?php
                $po = YIi::$app->db->createCommand("SELECT id,po_number,pr_purchase_order.is_cancelled FROM pr_purchase_order WHERE fk_pr_aoq_id= :id")
                    ->bindValue(':id', $model->id)
                    ->queryAll();
                foreach ($po as $val) {
                    $isCancelled = $val['is_cancelled'] ? 'Cancelled' : '';
                    echo "<tr>
                        <td>{$val['po_number']}</td>
                        <td>" . Html::a('PO Link ', ['pr-purchase-order/view', 'id' => $val['id']], ['class' => 'btn btn-warning ', 'style' => 'margin:3px']) . "</td>
                        <td>$isCancelled</td>
                   </tr>";
                }
                ?>
            </tbody>
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
        position: relative;
        top: 2rem;
        left: 0px;
        right: 0px;
        bottom: 0px;
        display: table-cell;
        text-align: center;
        height: 100%;


    }

    .center {
        text-align: center;
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

    .links_table td,
    .links_table th {
        border: none;
    }

    tfoot {
        display: table-row-group
    }

    @media print {
        .links_table {
            display: none;
        }

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
        $("#cancel").click((e) => {
            e.preventDefault();
            let ths = $(e.target)
            let link = ths.attr('href');
            swal({
                title: "Are you sure you want to " + ths.text() + " this AOQ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Confirm',
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true,
                width: "500px",
                height: "500px",
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: link,
                        method: 'POST',
                        data: {
                            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                        },
                        success: function(response) {
                            const res = JSON.parse(response)
                            if (!res.error) {
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
                                    title: 'Error',
                                    type: 'error',
                                    text: res.message,
                                    button: false,
                                    timer: 5000,
                                })
                            }
                        },
                        error: function(error) {
                            console.error('Cancel failed:', error);
                        }
                    });
                }
            })

        });
        const q = <?php echo json_encode($for_print) ?>;
        const payee_position = JSON.parse(`<?php echo json_encode($payee_position) ?>`);
        let ttlAmtPerPayee = []
        let row_number = 0
        let purpose = ''
        let remark_arr = []
        let remark_arr_index = 0
        let row_number_index = 1
        $.each(q, function(x, val) {
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
            </td>`;
            $.each(payee_position, function(key, val2) {
                row += `<td class='fooed' style='text-align:center;vertical-align:middle' ></td>`;
                row += `<td class='fooed' style='text-align:center;vertical-align:middle' ></td>`;
            })
            row += `<td style='vertical-align:top'></td>`;
            $("#table tbody").append(row)

            let lowest = ''

            $.each(val, function(key, val2) {
                let key_pos = '';

                $.each(payee_position, function(payee_pos_key, payee) {
                    if (payee == val2.payee) {
                        key_pos = parseInt(payee_pos_key)
                        return false
                    }
                })
                if (key_pos != '') {
                    let rem = val2.remark
                    if ($.trim(val2.remark) != '') {
                        rem += ` Item No. ${row_number+1}`
                    }
                    remark_arr[remark_arr_index] = {
                        'key_pos': key_pos,
                        'remark': rem
                    };
                    remark_arr_index++
                }

                if (parseInt(val2.is_lowest) == 1) {
                    lowest = lowest + val2.payee
                }
                let to_display = ''
                if (val2.amount.toLowerCase() != '-') {

                    to_display = thousands_separators(val2.amount)
                } else if (val2.amount.toLowerCase() == '-') {
                    to_display = '-'
                }
                let key_pos_1 = 5
                const amount = `${to_display}<br>`
                const remark = `<span>${val2.remark}</span>`
                const ttl = parseFloat(val2.amount) * parseInt(val2.quantity);
                if (ttlAmtPerPayee[key_pos + 1]) {
                    let ttlPerItm = ttl ? parseFloat(ttl) : 0
                    ttlAmtPerPayee[key_pos + 1] = parseFloat(ttlAmtPerPayee[key_pos + 1]) + ttlPerItm
                } else {
                    ttlAmtPerPayee[key_pos + 1] = parseFloat(ttl)
                }

                $("#table tbody").find(`td:nth-child(${key_pos})`).eq(row_number).append(amount)
                $("#table tbody").find(`td:nth-child(${key_pos+1})`).eq(row_number).append(thousands_separators(ttl))

            })
            $("#table tbody ").find(`td:last-child`).eq(row_number).text(lowest)
            row_number++


        })

        let colCount = 5
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
        let ttlRow = `<tr>
        <th></th>
        <th></th>
        <th></th>
        <th>TOTAL</th>`


        //   ADD REMARKS  COL
        $.each(payee_position, function(key, val2) {
            remark_row += `<td ></td>`;
            remark_row += `<td ></td>`;
            ttlRow += "<td></td><td  class='center'></td>"
            colCount += 2
        })
        remark_row += `<td style='vertical-align:top'></td>`;
        ttlRow += `<td ></td>`;
        $("#table tbody").append(ttlRow)
        $("#table tbody").append(remark_row)
        let posNeg = 0
        let arrSibRemove = []
        $.each(remark_arr, function(key, val) {

            let pos = 0
            pos = parseInt(val.key_pos)
            if (ttlAmtPerPayee[val.key_pos + 1]) {
                // console.log(ttlAmtPerPayee[val.key_pos + 1])
                $("#table tbody ").find(`td:nth-child(${pos+1})`).eq(row_number).text(thousands_separators(ttlAmtPerPayee[val.key_pos + 1]))
            }


            const remark = val.remark
            const table_col = $("#table tbody").find(`td:nth-child(${pos})`).eq(row_number + 1)
            if (table_col.text() != '  ') {
                if ($.trim(remark) != '') {
                    if (table_col.text() != '')
                        table_col.append(',<br>')
                }
            }
            table_col.append(val.remark)
            table_col.attr('colspan', 2)
            // console.log(pos + '-' + !arrSibRemove.includes(pos))

            if (!arrSibRemove.includes(pos)) {

                arrSibRemove.push(pos)
            }


        })
        $.each(arrSibRemove.sort((a, b) => b - a), (key, val) => {
            console.log(val)
            $("#table tbody").find(`td:nth-child(${val})`).eq(row_number + 1).next('td').remove()
        })


        const purpose_row = `<tr><td colspan='${colCount}'><span style='font-weight:bold'>Purpose:  </span><span>${purpose}</span></td></tr>`;
        $("#table tbody").append(purpose_row)


    })
</script>