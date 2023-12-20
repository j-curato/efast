<?php


use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Not Transmitted DV,'s";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transmittal-summary" style="background-color: white;">

    <div class="container">


        <table id="not-transmitted">
            <thead>
                <th>DV Number</th>
                <th>Check Number</th>
                <th>Issuance Date</th>
                <th>Particular</th>
                <th>Amount Disbursed</th>
                <th>Gross Amount</th>
                <th>Link</th>
            </thead>
            <tbody>
                <?php
                foreach ($not_transmitted  as $itm) {

                    echo "<tr>
                            <td>{$itm['dv_number']}</td>
                            <td>{$itm['check_or_ada_no']}</td>
                            <td>{$itm['issuance_date']}</td>
                            <td>{$itm['particular']}</td>
                            <td class='amount'>" . number_format($itm['ttlDisbursed'], 2) . "</td>
                            <td class='amount'>" . number_format($itm['grossAmt'], 2) . "</td><td>";
                    echo Html::a('Link', ['dv-aucs/view', 'id' => $itm['id']], ['class' => 'btn btn-link']);
                    echo "</td></tr>";
                }
                ?>

            </tbody>
        </table>
    </div>
</div>
<style>
    .amount {
        text-align: right;
    }

    table {
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 5px;
    }

    @media print {


        .main-footer {
            display: none;
        }
    }
</style>

<?php
?>
<script>
    $(document).ready(function() {
        // notTransmitted()


    })

    function notTransmitted() {
        const not_transmitted = <?php echo json_encode($not_transmitted) ?>;
        $('#not-transmitted tbody').html('')
        $.each(not_transmitted, function(key, val) {
            const dv_number = val.dv_number
            const issuance_date = val.issuance_date
            const particular = val.particular
            const check_or_ada_no = val.check_or_ada_no
            const amount_disbursed = val.ttlDisbursed
            const grossAmt = val.grossAmt


            const rowData = `<tr>
                               <td>${dv_number}</td>
                               <td>${check_or_ada_no}</td>
                               <td>${issuance_date}</td>
                               <td>${particular}</td>
                               <td class='amount'>${thousands_separators(amount_disbursed)}</td>
                               <td class='amount'>${thousands_separators(grossAmt)}</td>
                            </tr>`
            $('#not-transmitted tbody').append(rowData)
        })
    }
</script>