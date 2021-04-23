<?php

/* @var $this yii\web\View */

use yii\helpers\ArrayHelper;

$this->title = 'Dashboard';
?>
<div class="site-index">

    <?php

    $query = (new \yii\db\Query())
        ->select([
            'SUM(raoud_entries.amount) as total_obligated',
            'SUM(record_allotment_entries.amount) as total_allotment'
        ])
        ->from('raouds')
        ->join('LEFT JOIN', 'raoud_entries', 'raouds.id = raoud_entries.raoud_id')
        ->join('LEFT JOIN', 'record_allotment_entries', 'raouds.record_allotment_entries_id = record_allotment_entries.id')
        ->where("raouds.process_ors_id IS NOT NULL ")
        // ->andWhere("raouds.reporting_period LIKE :reporting_period", ['reporting_period' => '2021%'])
        ->one();

    $query1 = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total_obligated,
            (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed FROM `dv_aucs_entries` where dv_aucs_entries.process_ors_id IS NOT NULL) as total_disbursed
            FROM process_ors,raouds,raoud_entries
            WHERE process_ors.id = raouds.process_ors_id
            AND raouds.id = raoud_entries.raoud_id
            ")
        ->queryOne();
    $query3 = Yii::$app->db->createCommand("SELECT SUM(dv_aucs_entries.vat_nonvat) as total_vat_nonvat,
    SUM(dv_aucs_entries.ewt_goods_services) as total_ewt,
    SUM(dv_aucs_entries.compensation) as total_compensation
    FROM dv_aucs_entries
    ")->queryOne();

    $total_cash_disbursed = Yii::$app->db->createCommand("SELECT books.`name`, SUM(dv_aucs_entries.amount_disbursed)as total_disbursed 
    FROM cash_disbursement,dv_aucs,dv_aucs_entries,books
    WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
    AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
    AND cash_disbursement.book_id = books.id
    GROUP BY cash_disbursement.book_id")->queryAll();

    // -- AND process_ors.reporting_period LIKE '2021%'

    ?>
    <div class="body-content">

        <div class="row justify-content-around">
            <!-- Allotment/Appropriations and Obligations -->
            <div class="panel panel-default col-sm-5">
                <div class="panel-heading" style="background-color:white;width:100%">

                    <h4>
                        Allotment/Appropriations and Obligations

                    </h4>
                </div>

                <div class="panel-content">
                    <table class="table">

                        <tr>
                            <td>

                                <span style="white-space: pre;">Total Allotments and Appropriations Received</span>

                            </td>
                            <td style="text-align: right;">
                                <span style=" margin-left: auto;">
                                    <?php
                                    echo number_format($query['total_allotment'], 2);
                                    ?>
                                </span>

                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span>Total Obligations</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo number_format($query['total_obligated'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Unobligated Balance</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo  number_format($query['total_allotment'] - $query['total_obligated'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div>

                </div>


            </div>
            <!-- END Allotment/Appropriations and Obligations -->

            <!-- Obligations and Disbursements -->
            <div class="panel panel-default col-sm-5">
                <div class="panel-heading " style="background-color:white;width:100%">
                    <h4>
                        Obligations and Disbursements
                    </h4>
                </div>
                <div class="panel-content">
                    <table class="table">

                        <tr>
                            <td>

                                <span style="white-space: pre;">Total Amount Obligated</span>

                            </td>
                            <td style="text-align: right;">
                                <span style=" margin-left: auto;">
                                    <?php
                                    echo number_format($query1['total_obligated'], 2);
                                    ?>
                                </span>

                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span>Less: Total Disbursement</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo number_format($query1['total_disbursed'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Less: Total Tax Remittance</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    // echo  number_format($query['total_allotment'] - $query['total_obligated'], 2);

                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Unpaid Obligations</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo  number_format($query1['total_obligated'] - $query1['total_disbursed'], 2);

                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--END  Obligations and Disbursements -->

        </div>
        <div class="row">
            <!-- Taxes Withheld and Remitted -->

            <div class="panel panel-default  col-sm-5">
                <div class="panel-heading" style="background-color:white;width:100%">
                    <h4>
                        Taxes Withheld and Remitted
                    </h4>
                </div>
                <div class="panel-content">

                    <table class="table">

                        <tr>
                            <td>

                                <span style="white-space: pre;">Total Taxes Withheld</span>

                            </td>
                            <td style="text-align: right;">
                                <span style=" margin-left: auto;">
                                    <?php
                                    $total_withheld = $query3['total_vat_nonvat'] + $query3['total_ewt'] + $query3['total_compensation'];
                                    echo number_format($total_withheld, 2);
                                    ?>
                                </span>

                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span>Less: Total Tax Remittance</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo number_format($query['total_obligated'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Balance for Remittance</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo  number_format($query['total_allotment'] - $query['total_obligated'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--END Taxes Withheld and Remitted -->
            <!-- Cash Received and Disbursed -->
            <div class="panel panel-default  col-sm-5">
                <div class="panel-heading" style="background-color:white;width:100%">

                    <h4>
                        Cash Received and Disbursed
                    </h4>
                </div>
                <div class="panel-content">

                    <table class="table">
                        <thead>

                        <th>qwe</th>
                        <th style="text-align: right;">Fund 01</th>
                        <th style="text-align: right;">Rapid LP </th>
                        </thead>
                        <tr>
                            <td>

                                <span style="white-space: pre;">Cash Received</span>

                            </td>
                            <td style="text-align: right;">
                                <span style=" margin-left: auto;">
                                    <?php
                                    $cash_recieved = Yii::$app->db->createCommand("SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved")->queryOne();

                                    echo number_format($cash_recieved['total_cash_recieved'], 2);
                                    ?>
                                </span>

                            </td>

                        </tr>
                        <tr>
                            <td>
                                <span>Cash Dibursements</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    $result = ArrayHelper::index($total_cash_disbursed, null, 'name');
                                    echo number_format($result['Fund 01'][0]['total_disbursed'], 2);
                                    // ob_clean();
                                    // echo "<pre>";
                                    // var_dump($result['Fund 01'][0]['total_disbursed']);
                                    // echo "</pre>";
                                    ?>
                                </span>
                            </td>
                            <?php
                            foreach ($total_cash_disbursed as $val) {
                            }
                            ?>
                        </tr>
                        <tr>
                            <td>
                                <span>Cash Balance</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo  number_format($cash_recieved['total_cash_recieved'] - $result['Fund 01'][0]['total_disbursed'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- END Cash Received and Disbursed -->

        </div>


    </div>
</div>
<style>
    .panel {
        background-color: white;
        box-shadow: 20px;
        margin: 5px;
        border-radius: 10px;
    }


    td {
        padding: 12px;
    }
</style>