<?php

use yii\helpers\Html;
use common\models\User;
use yii\web\JqueryAsset;
use kartik\select2\Select2Asset;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransmittal */

$this->title = $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Po Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$user_data = User::getUserDetails();
// $query->where('province = :province', ['province' => $user_data->employee->office->office_name]);
$approvedBy  = !empty($model->fk_approved_by) ? $model->approvedBy->getEmployeeDetails() : [];
$officerInCharge  = !empty($model->fk_officer_in_charge) ? $model->officerInCharge->getEmployeeDetails() : [];

?>
<div class="po-transmittal-view">



    <div class="container card">
        <div class="row as">
            <p>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

                <?php
                echo Html::a(empty($model->file_link) ? 'Add File Link' : 'Update File Link', ['add-file-link', 'id' => $model->transmittal_number], ['class' => 'btn btn-primary mdModal']);
                if (!empty($model->file_link)) {
                    echo Html::a('DV Scanned Copy Link ', $model->file_link, ['class' => 'btn btn-link', 'target' => '_blank']);
                }
                if (!empty($model->poTransmittalToCoa->fk_po_transmittal_to_coa_id) && YIi::$app->user->can('ro_accounting_admin')) {
                    echo  Html::a('Transmittal to Coa Link', ['/po-transmittal-to-coa/view', 'id' => $model->poTransmittalToCoa->fk_po_transmittal_to_coa_id], ['class' => 'btn btn-link']);
                }
                ?>
                <?php
                $color = '';
                $action = '';
                if ($model->is_accepted === 0) {
                    $color = 'btn-success';
                    $action = 'Accept';
                } else {
                    $color = 'btn-danger';
                    $action = 'Pending';
                }
                if (Yii::$app->user->can('ro_accounting_admin')) {
                    echo Html::a($action, ['accept', 'id' => $model->id], [
                        'class' => "btn $color",
                        'data' => [
                            'confirm' => "Are you sure you want to $action this item?",
                            'method' => 'post',
                        ],
                    ]);
                }
                $prov = '';
                $provinces = [
                    'adn' => 'Agusan Del Norte',
                    'ads' => 'Agusan Del Sur',
                    'sdn' => 'Surigao Del Norte',
                    'sds' => 'Surigao Del Sur',
                    'pdi' => 'Dinagat Islands',
                ];
                if (!Yii::$app->user->can('ro_accounting_admin')) {
                    $prov = $user_data->employee->office->office_name;
                }
                ?>
            </p>

        </div>

        <div class="row" style="float:right">
            <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/images/dti_header.png', [
                'alt' => 'some', 'class' => 'pull-left img-responsive float-right',
                'style' => 'width: 14em;margin-left:auto'
            ]); ?>
        </div>

        <table id="header" style="border:none">

            <tbody style="border:none">

                <tr style="height:110px;">

                    <td style="width: 110px;">
                        For
                    </td>
                    <td>
                        <span class="head bold">
                            GAY A. TIDALGO, CESO IV
                        </span> <br>
                        <span>
                            Regional Director, DTI-CARAGA
                        </span>
                    </td>
                </tr>
                <tr style="height:70px;">
                    <td>
                        From
                    </td>
                    <td>


                        <b class="font-weight-bold"><?= !empty($approvedBy['fullName']) ? strtoupper($approvedBy['fullName']) : '' ?></b>
                        <span>
                            <p><?= !empty($approvedBy['position']) ? $approvedBy['position'] : '' ?></p>
                        </span>

                    </td>
                </tr>

                <tr>
                    <td>
                        Date
                    </td>
                    <td>
                        <span>

                            <?php
                            echo date('F d, Y', strtotime($model->date));
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Subject
                    </td>
                    <td class="bold">
                        <span>DV Transmittal No. </span>
                        <span>
                            <?php
                            echo $model->transmittal_number;
                            ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <hr>

        <span style="font-size: 15px;">
            We are hereby submitting the following DV, with assigned Transmittal# <?php echo $model->transmittal_number ?> of DTI <?php echo $prov ?>.
        </span>

        <table class="data_table">
            <thead style="border-top: 1px solid black;border-bottom: 1px solid black;">
                <th>No.</th>
                <th>DV Number</th>
                <th>Check/ADA</th>
                <th>Check/ADA Date</th>
                <th>Payee</th>
                <th>Particulars</th>
                <th>Amount</th>
                <th class='status'>Status</th>
            </thead>

            <tbody>

                <?php
                $total = 0;
                $q = 1;
                $query  = Yii::$app->db->createCommand("SELECT 
                liquidation.dv_number,
                liquidation.check_number,
                liquidation.check_date,
                IFNULL(po_transaction.payee,liquidation.payee) as payee,
                IFNULL(po_transaction.particular,liquidation.particular) as particular,
                liquidation_total.total_withdrawals,
                liquidation.status as liquidation_status,
                liquidation.id as liquidation_id,
                po_transmittal_entries.id,
                po_transmittal_entries.status

                
                FROM 
                po_transmittal_entries
                LEFT JOIN liquidation ON po_transmittal_entries.liquidation_id = liquidation.id
                LEFT JOIN po_transaction ON liquidation.po_transaction_id = po_transaction.id
                LEFT JOIN (SELECT SUM(liquidation_entries.withdrawals) as total_withdrawals,
                liquidation_entries.liquidation_id
                FROM liquidation_entries 
                GROUP BY liquidation_entries.liquidation_id 
                )as liquidation_total ON liquidation.id  = liquidation_total.liquidation_id
                WHERE
                po_transmittal_entries.po_transmittal_number  = :id
                ORDER BY liquidation.check_number
                ")
                    ->bindValue(':id', $model->transmittal_number)
                    ->queryAll();

                foreach ($items as $i => $itm) {

                    $qwe = '';
                    $display = 'display:none;';
                    echo "<tr>
                        <td>$q</td>
                        <td>{$itm['dv_number']}</td>
                        <td>{$itm['check_number']}</td>
                        <td>{$itm['check_date']}</td>
                        <td>{$itm['payee']}</td>
                        <td>{$itm['particular']}</td>
                        <td style='text-align:right'>" . number_format($itm['total_withdrawal'] ?? 0, 2) . "</td>
                    ";
                    if (Yii::$app->user->can('ro_accounting_admin')) {

                        $status = 'Remove';
                        $color = 'btn-danger';
                        if ($itm['is_returned'] == 1) {
                            $status = 'Ibalik';
                            $color = 'btn-success';
                        }
                        $qwe = Html::a($status, ['return', 'id' => $itm['item_id']], [
                            'class' => "btn $color ",
                            'data' => [
                                'confirm' => "Are you sure you want to  this item?",
                                'method' => 'post',
                            ],
                        ]);
                        echo "  <td class='status'>" . $qwe . " </td>";
                    }
                    if ($itm['is_returned'] == 1) {
                        echo "<td class='status'> Returned</td>";
                    }
                    echo " </tr>";
                    $total += floatval($itm['total_withdrawal']);
                    $q++;
                }
                // }
                ?>
                <tr>

                    <td colspan="6" style="font-weight: bold;text-align:center"> Total</td>
                    <td class="text-right"> <?php echo number_format($total, 2); ?></td>

                </tr>
                <tr>
                    <td colspan="8" class="border-bottom-0 border-right-0 border-left-0">
                        <br>
                        Thank you.
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="border-0 pt-0">
                        <p class="mt-4">Very truly yours,</p>
                        <br>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="border-0">
                        <u class="font-weight-bold"><?= !empty($approvedBy['fullName']) ? strtoupper($approvedBy['fullName']) : '' ?></u>
                        <span>
                            <p><?= !empty($approvedBy['position']) ? $approvedBy['position'] : '' ?></p>
                        </span>
                    </td>
                </tr>

                <?php if (!empty($model->fk_officer_in_charge)) : ?>
                    <tr>
                        <td colspan="8" class="border-0">
                            <br>
                            <p> For the Regional Director</p>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="8" class="border-0">
                            <u class="font-weight-bold"><?= !empty($officerInCharge['fullName']) ? strtoupper($officerInCharge['fullName']) : '' ?></u>
                            <br> <span><?= !empty($officerInCharge['position']) ? $officerInCharge['position'] : '' ?></span>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>
<style>
    .data_table {
        width: 100%;
        padding: 10px;
    }

    .bold {
        font-weight: bold;
    }

    #header {
        width: 100%;
        margin-bottom: 20px;
        padding: 10px;
    }

    #header td {
        border: none
    }

    .container {
        padding: 50px;
    }

    td,
    th {
        background-color: white;
        border: 1px solid black;
        padding: 8px;
        line-height: 1.42857143;
        vertical-align: top;

    }

    .container {
        background-color: white;
        width: 80%;
    }

    .row {
        margin-left: 0;
        margin-right: 0;
    }

    .main-footer {
        display: none;
    }

    .head {
        font-size: 12pt;
    }

    @page {
        margin: 30px;
    }

    @media print {


        @page {
            margin: 12in 12in 12in 12in;
        }

        .container {
            padding: 25px 100px 0 100px;
            width: 100%;
        }

        .status,
        .assignatory,
        .as,
        .actions,
        .links,
        .btn {
            display: none;
        }

        header.onlyprint {
            position: fixed;
            /* Display only on print page (each) */
            top: 0;
            /* Because it's header */
        }

        footer.onlyprint {
            position: fixed;
            bottom: 0;
            /* Because it's footer */
        }

        .select2-container--default .select2-selection--single,
        .select2-selection .select2-selection--single {
            /* border: 1px solid #d2d6de; */
            /* border-radius: 0; */
            padding: 0;

        }

        .select2-container {
            height: 20px;
        }

        .krajee-datepicker {
            border: 1px solid white;
            font-size: 10px;
            padding-left: 9px;
        }


        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: none;
            padding-left: 0;
        }

        .select2-selection__arrow {
            display: none;
        }


        .select2-selection {
            border: 1px solid white;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            text-indent: 1px;
            text-overflow: '';
            border: none;
        }

        th,
        td {
            padding: 5px;
            /* font-size: 10px; */
        }


        .main-footer {
            display: none;
        }
    }
</style>
<?php

Select2Asset::register($this);
?>

<script>
    var reference = []


    function oicRd(x) {
        $("#oic").text(x.value)
    }

    function regionalDirector(x) {
        $("#asig_1").text(x.value.toUpperCase())
        if (x.value == '') {
            $("#for_rd").text('')
            $("#asig_1_position").text('')
        } else {
            var qwer = json_assignatory.filter(record => record.name === x.value)

            // console.log(toTitleCase(qwer[0].position))
            $("#asig_1_position").text(qwer[0].position)
        }
    }

    function toTitleCase(str) {
        return str.replace(/\w\S*/g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    function sample(q) {



        $("#ass").text(q.value.toUpperCase())
        if (q.value == '') {
            $("#for_rd").text('')
            $("#position").text('')
        } else {
            var qwer = json_assignatory.filter(record => record.name === q.value)

            console.log(qwer[0].position)
            $("#position").text(qwer[0].position)
            $("#for_rd").text('For the Provincial Director')
        }


    }
    var json_assignatory = undefined
    $(document).ready(function() {
        var oic_rd = ['Officer-in-Charge', 'Provincial Director']
        $('#oic_rd').select2({
            data: oic_rd,
            placeholder: "Select OIC",
            allowClear: true,
            closeOnSelect: true
        })
        $.getJSON(window.location.pathname + '?r=po-assignatory/get-all-assignatory')

            .then(function(data) {

                var array = []
                json_assignatory = data
                $.each(data, function(key, val) {
                    array.push({
                        id: val.name,
                        text: val.name
                    })
                })

                assignatory = array

                $('.asignatory').select2({
                    data: assignatory,
                    placeholder: "Select ",
                    allowClear: true,
                    closeOnSelect: true
                })

            })
    })
</script>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>