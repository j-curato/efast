<?php


use aryelds\sweetalert\SweetAlertAsset;

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
            </thead>
            <tbody>


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
    }

    @media print {


        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/frontend/web/js/globalFunctions.js');
?>
<script>
    $(document).ready(function() {
        notTransmitted()


    })

    function notTransmitted() {
        const not_transmitted = <?php echo json_encode($not_transmitted) ?>;
        $('#not-transmitted tbody').html('')
        $.each(not_transmitted, function(key, val) {
            const dv_number = val.dv_number
            const issuance_date = val.issuance_date
            const particular = val.particular
            const check_or_ada_no = val.check_or_ada_no
            const amount_disbursed = val.amount_disbursed
            const rowData = `<tr>
                               <td>${dv_number}</td>
                               <td>${check_or_ada_no}</td>
                               <td>${issuance_date}</td>
                               <td>${particular}</td>
                               <td class='amount'>${thousands_separators(amount_disbursed)}</td>
                            </tr>`
            $('#not-transmitted tbody').append(rowData)
        })
    }
</script>