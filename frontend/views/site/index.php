<?php

/* @var $this yii\web\View */

use aryelds\sweetalert\SweetAlertAsset;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;

$this->title = 'Dashboard';
?>
<div class="site-index">
    <?php
    if (Yii::$app->user->can('super-user')) {
        echo " <button class='btn btn-success' id='update_cloud' style='margin-bottom:12px'>Update  Cloud</button>";
        echo " <button class='btn btn-warning' id='update_lan'>Update LAN</button>";
    }
    $query = (new \yii\db\Query())
        ->select([
            'SUM(raoud_entries.amount) as total_obligated',
            '(SELECT SUM(record_allotment_entries.amount) as total_allotment from record_allotment_entries) as total_allotment'
        ])
        ->from('raouds')
        ->join('LEFT JOIN', 'raoud_entries', 'raouds.id = raoud_entries.raoud_id')
        ->join('LEFT JOIN', 'record_allotment_entries', 'raouds.record_allotment_entries_id = record_allotment_entries.id')
        ->where("raouds.process_ors_id IS NOT NULL ")
        // ->andWhere("raouds.reporting_period LIKE :reporting_period", ['reporting_period' => '2021%'])
        ->one();

    $ors = Yii::$app->db->createCommand("SELECT SUM(raoud_entries.amount) as total_obligated,
                        (SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed
                        FROM cash_disbursement,dv_aucs,dv_aucs_entries
                        WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
                        AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
                        AND cash_disbursement.is_cancelled =0
                        ) as total_disbursed
                FROM process_ors,raouds,raoud_entries
                WHERE process_ors.id = raouds.process_ors_id
                AND raouds.id = raoud_entries.raoud_id
                AND process_ors.is_cancelled =0
            ")
        ->queryOne();
    $query3 = Yii::$app->db->createCommand("SELECT SUM(dv_aucs_entries.vat_nonvat) as total_vat_nonvat,
                SUM(dv_aucs_entries.ewt_goods_services) as total_ewt,
                SUM(dv_aucs_entries.compensation) as total_compensation
                FROM dv_aucs_entries
            ")->queryOne();

    $total_cash_disbursed = Yii::$app->db->createCommand("SELECT books.`name`,
                         cash_disbursement.book_id,
                        SUM(dv_aucs_entries.amount_disbursed)as total_disbursed 
                        FROM cash_disbursement,dv_aucs,dv_aucs_entries,books
                        WHERE cash_disbursement.dv_aucs_id = dv_aucs.id
                        AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
                        AND cash_disbursement.book_id = books.id
                        AND cash_disbursement.is_cancelled = 0
                        GROUP BY cash_disbursement.book_id,books.`name`")->queryAll();
    $payable = Yii::$app->db->createCommand("SELECT SUM(dv_aucs_entries.amount_disbursed) as total_payable
                FROM `dv_aucs`,dv_aucs_entries,mrd_classification 
                where dv_aucs.mrd_classification_id = mrd_classification.id
                AND dv_aucs.id = dv_aucs_entries.dv_aucs_id
                AND mrd_classification.`name` LIKE 'Prior Year Accounts Payable'
                AND dv_aucs.is_cancelled =0
                ")->queryOne();


    ?>
    <div class="body-content">
        <div style="height:400px;width:400px" id="calendar">
            <?php
            // echo edofre\fullcalendar\Fullcalendar::widget([
            //     'events' => $events,
            //     'clientOptions' => [
            //         'editable' => true,
            //         'droppable' => true,
            //         'eventDrop' => function ($data) {
            //             ob_clean();
            //             echo "<pre>";
            //             var_dump($data);
            //             echo "</pre>";
            //             return ob_get_clean();
            //         }
            //     ],

            // ]);
            ?>
        </div>

        <div class="row justify-content-around">


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
                                    echo number_format($ors['total_obligated'], 2);
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
                                    $total_disbursed = $ors['total_disbursed'] - $payable['total_payable'];
                                    echo number_format($total_disbursed, 2);
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
                                    echo  number_format($ors['total_obligated'] - $total_disbursed, 2);

                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Total Prior Year Accounts Payable</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php

                                    echo  number_format($payable['total_payable'], 2);

                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--END  Obligations and Disbursements -->
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

                            <th></th>
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
                                    $cash_recieved = Yii::$app->db->createCommand("SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved,books
                                     where cash_recieved.book_id = books.id
                                    AND books.name ='Fund 01'")->queryOne();

                                    echo number_format($cash_recieved['total_cash_recieved'], 2);
                                    ?>
                                </span>

                            </td>
                            <td style="text-align: right;">
                                <span style=" margin-left: auto;">
                                    <?php
                                    $cash_recieved2 = Yii::$app->db->createCommand("SELECT SUM(cash_recieved.amount) as total_cash_recieved from cash_recieved,books
                                     where cash_recieved.book_id = books.id
                                    AND books.name NOT LIKE 'Fund 01'")->queryOne();

                                    echo number_format($cash_recieved2['total_cash_recieved'], 2);
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

                                    // ob_clean();
                                    // echo "<pre>";
                                    // var_dump($result['Fund 01'][0]['total_disbursed']);
                                    // echo "</pre>";
                                    ?>
                                </span>
                            </td>
                            <?php
                            // foreach ($total_cash_disbursed as $val) {
                            // }
                            ?>
                        </tr>
                        <tr>
                            <td>
                                <span>Cash Balance</span>

                            </td>
                            <td style="text-align: right;">
                                <span id="total_cash_disbursed">
                                    <?php
                                    // echo  number_format($cash_recieved['total_cash_recieved'] - $result['Fund 01'][0]['total_disbursed'], 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Pending DV's for Disbursement</span>

                            </td>
                            <td style="text-align: center;" colspan="2">
                                <span id="total_amount_pending">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Cash Balance per Accounting</span>

                            </td>
                            <td style="text-align: center;" colspan="2">
                                <span id="cash_balance_per_accounting">
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>
            <!-- END Cash Received and Disbursed -->


        </div>
        <div class="row">


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
                                    // echo number_format($query['total_obligated'], 2);
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
                                    echo number_format($total_withheld, 2);
                                    ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--END Taxes Withheld and Remitted -->

        </div>
        <!-- DV -->
        <div class="row">
            <?php

            $pending_dv = Yii::$app->db->createCommand(
                "SELECT COUNT(dv_aucs.id) as total_pending from dv_aucs where dv_aucs.id   NOT IN (SELECT cash_disbursement.dv_aucs_id from cash_disbursement WHERE cash_disbursement.dv_aucs_id IS NOT NULL)"
            )->queryOne();
            ?>

            <div class="panel panel-default  col-sm-5">
                <div class="panel-heading" style="background-color:white;width:100%">
                    <h4>
                        DV's
                    </h4>
                </div>
                <div class="panel-content">
                    <?php
                    $total_dv = Yii::$app->db->createCommand("SELECT DISTINCT COUNT(cash_disbursement.id) ro_dv,
                (SELECT DISTINCT count(transmittal_entries.cash_disbursement_id) as coa_dv from transmittal_entries) as coa_dv
                
                FROM cash_disbursement 
                WHERE cash_disbursement.id NOT IN (SELECT DISTINCT transmittal_entries.cash_disbursement_id FROM transmittal_entries)")->queryOne();

                    ?>
                    <table class="table">
                        <tr>
                            <td>
                                <span style="white-space: pre;">Total DV's in Regional Office</span>
                            </td>
                            <td style="text-align: right;">
                                <span style=" margin-left: auto;">
                                    <?php
                                    echo number_format($total_dv['ro_dv']);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Total DV's in COA</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo number_format($total_dv['coa_dv']);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span>Pending DV's</span>

                            </td>
                            <td style="text-align: right;">
                                <span>
                                    <?php
                                    echo number_format($pending_dv['total_pending']);
                                    ?>
                                </span>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
<div id="dots5" style="display: none;">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>
<style>
    .panel {
        background-color: white;
        box-shadow: 20px;
        margin: 5px;
        border-radius: 10px;
    }

    #dots5 {
        display: none;
    }

    td {
        padding: 12px;
    }

    .fc-day-number {
        font-size: 12px;
    }

    .fc-center h2 {
        font-size: 20px;
    }

    .fc-button-group {
        font-size: 12px;
    }

    .fc .fc-toolbar-title {
        font-size: 1.5em;
    }

    .btn {
        position: relative;
        display: block;
        font-size: 10px;
    }
</style>
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);
?>
<?php
$csrfToken = Yii::$app->request->csrfToken;
$csrfName = Yii::$app->request->csrfParam;
?>

<script>
    let x = undefined;
    $('#update_lan').click((e) => {

        try {

            e.preventDefault()
            $('.site-index').hide();
            $('#dots5').show()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=sync-database/update-lan',
                data: {
                    data: ''
                },
                success: function(data) {
                    $('.site-index').show();
                    $('#dots5').hide()
                }
            })
        } catch (e) {
            console.log(e.message)
            swal({
                title: 'Error',
                text: e.message,
                type: 'error',
                button: false,

            })
        }
    })

    $('#update_cloud').click(function(e) {
        e.preventDefault();
        $('.site-index').hide();
        $('#dots5').show()

        $.post(window.location.pathname + '?r=site/token', {
            data: ''
        }, function(data) {
            localStorage.setItem('token', JSON.parse(data).token)
        })
        let baseUrl = window.location.pathname

        const res = []
        const chartOfAccountApi = new Promise((resolve, reject) => {
            // CHART OF ACCOUNTS API
            $.post(window.location.pathname + '?r=sync-database/chart-of-account', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=chart-of-accounts-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve('chartOfAccountApi ' + newdata)
                        }
                    })
                })
        })
        const subAccount1Api = new Promise((resolve, reject) => {
            // SUB ACCOUNTS 1 API
            $.post(window.location.pathname + '?r=sync-database/sub-account1', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=sub-accounts1-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve('subAccount1Api' + newdata)
                        }
                    })
                })
        })
        const subAccount2Api = new Promise((resolve, reject) => {
            // SUB ACCOUNTS 2 API
            $.post(window.location.pathname + '?r=sync-database/sub-account2', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=sub-accounts2-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve('subAccount2Api' + newdata)
                        }
                    })
                })
        })
        const payeeApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/payee', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)


                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=payee-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {

                            res[0] = {
                                payee: newdata
                            }
                            resolve(newdata)
                        }
                    })
                })
        })
        const transactionApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/transaction', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=transaction-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                            console.log('newdata')

                        }
                    })
                })
        });
        transactionApi.then(() => {
            $.post(window.location.pathname + '?r=sync-database/process-ors', // url
                {
                    myData: '',
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    console.log(d)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=process-ors-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            console.log(newdata)
                        }
                    })
                })
        })
        const recordAllotmentApi = new Promise((resolve, reject) => {
            // RECORD ALLOTMENT API
            $.post(window.location.pathname + '?r=sync-database/record-allotment', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    $.ajax({
                        type: "post",
                        url: 'https://fisdticaraga.com/index.php?r=record-allotment-api/create',
                        contentType: "application/json",
                        data: JSON.stringify(d),
                        dataType: 'json',
                        headers: {
                            "Authorization": `Bearer ${localStorage.getItem('token')}`
                        },
                        success: function(newdata) {
                            resolve(newdata)
                        }
                    })
                })
        })
        const trackingSheetApi = new Promise((resolve, reject) => {

            transactionApi.then(() => {


                $.post(window.location.pathname + '?r=sync-database/tracking-sheet', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=tracking-sheet-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })


        const dvAucsApi = new Promise((resolve, reject) => {
            $.post(window.location.pathname + '?r=sync-database/dv-aucs', // url
                {
                    myData: ''
                }, // data to be submit
                function(data) { // success callback
                    var d = JSON.parse(data)
                    try {


                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=dv-aucs-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                                // DV AUCS ENTRIES
                                $.post(window.location.pathname + '?r=sync-database/dv-aucs-entries', // url
                                    {
                                        myData: '',
                                        '<?= $csrfName ?>': "<?= $csrfToken ?>"
                                    }, // data to be submit
                                    function(data) { // success callback
                                        var d = JSON.parse(data)
                                        $.ajax({
                                            type: "post",
                                            url: 'https://fisdticaraga.com/index.php?r=dv-aucs-entries-api/create',
                                            contentType: "application/json",
                                            data: JSON.stringify(d),
                                            dataType: 'json',
                                            headers: {
                                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                                            },
                                            success: function(newdata) {
                                                resolve(newdata)
                                            }
                                        })
                                    })
                                // DV ACCOUNTING ENTRIES
                                $.post(window.location.pathname + '?r=sync-database/dv-accounting-entries', // url
                                    {
                                        myData: ''
                                    }, // data to be submit
                                    function(data) { // success callback
                                        var d = JSON.parse(data)
                                        $.ajax({
                                            type: "post",
                                            url: 'https://fisdticaraga.com/index.php?r=dv-accounting-entries-api/create',
                                            contentType: "application/json",
                                            data: JSON.stringify(d),
                                            dataType: 'json',
                                            headers: {
                                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                                            },
                                            success: function(newdata) {
                                                resolve(newdata)
                                            }
                                        })
                                    })
                            }
                        })
                    } catch (e) {
                        console.log(e.message)
                    }
                })
        })
        const cashDisbursementApi = new Promise((resolve, reject) => {

            dvAucsApi.then(() => {
                // RECORD ALLOTMENT API
                $.post(window.location.pathname + '?r=sync-database/cash-disbursement', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=cash-disbursement-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })
        const advancesApi = new Promise((resolve, reject) => {
            cashDisbursementApi.then(() => {

                $.post(window.location.pathname + '?r=sync-database/advances', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)
                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=advances-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })
        const advancesEntries = new Promise((resolve, reject) => {
            advancesApi.then(() => {
                $.post(window.location.pathname + '?r=sync-database/advances-entries', // url
                    {
                        myData: ''
                    }, // data to be submit
                    function(data) { // success callback
                        var d = JSON.parse(data)

                        $.ajax({
                            type: "post",
                            url: 'https://fisdticaraga.com/index.php?r=advances-entries-api/create',
                            contentType: "application/json",
                            data: JSON.stringify(d),
                            dataType: 'json',
                            headers: {
                                "Authorization": `Bearer ${localStorage.getItem('token')}`
                            },
                            success: function(newdata) {
                                resolve(newdata)
                            }
                        })
                    })
            })
        })


        // const processOrsApi = new Promise((resolve, reject) => {
        //     // PROCESS ORS  API
        //     $.post(window.location.pathname + '?r=sync-database/process-ors', // url
        //         {
        //             myData: ''
        //         }, // data to be submit
        //         function(data) { // success callback
        //             var d = JSON.parse(data)
        //             $.ajax({
        //                 type: "post",
        //                 url: 'https://fisdticaraga.com/index.php?r=process-ors-api/create',
        //                 contentType: "application/json",
        //                 data: JSON.stringify(d),
        //                 dataType: 'json',
        //                 headers: {
        //                     "Authorization": `Bearer ${localStorage.getItem('token')}`
        //                 },
        //                 success: function(newdata) {
        //                     resolve(newdata)
        //                 }
        //             })
        //         })
        // })
        // const processOrsApi = new Promise((resolve, reject) => {F
        // PROCESS ORS  API

        // })
        // processOrsApi.then((data) => {
        //     console.log(data)
        // })
        // At this point, "promiseA" is already settled.

        Promise.all([
            transactionApi,
            payeeApi,
            chartOfAccountApi,
            subAccount1Api,
            subAccount2Api,
            recordAllotmentApi,
            dvAucsApi,
            cashDisbursementApi,
            trackingSheetApi,
            advancesApi,
            advancesEntries

        ]).then(values => {
            $('.site-index').show();
            $('#dots5').hide()

            // console.log(values)
            // console.log("We waited until ajax ended: " + values);
            // console.log("My few ajax ended, lets do some things!!")
        }, reason => {
            console.log("Promises failed: " + reason);
        });






        // PAYEE
        // $.post(window.location.pathname + '?r=sync-database/payee', // url
        //     {
        //         myData: ''
        //     }, // data to be submit
        //     function(data) { // success callback
        //         var d = JSON.parse(data)


        //         $.ajax({
        //             type: "post",
        //             url: 'https://fisdticaraga.com/index.php?r=payee-api/create',
        //             contentType: "application/json",
        //             data: JSON.stringify(d),
        //             dataType: 'json',
        //             headers: {
        //                 "Authorization": `Bearer ${localStorage.getItem('token')}`
        //             },
        //             success: function(newdata) {
        //                 console.log(newdata)
        //             }
        //         })
        //     })

        // TRANSACTION API






    })
    $(document).ready(function() {
        $.getJSON(window.location.pathname + '?r=site/q').then(function(data) {
            cal(data)
        })
    })

    function cal(data) {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            themeSystem: 'bootstrap',
            height: 400,

            headerToolbar: {
                left: 'prev,next,today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: data,
            eventClick: function(info) {

                $('#genericModal').modal('show').find('#modalContent').load(window.location.pathname + '?r=event/update&id=' + info.event.id)
            }
        });
        calendar.render();
    }

    function allowDrop(ev) {
        ev.preventDefault();
        console.log('qwe')
    }

    function drag(ev) {
        console.log('qwe')
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        console.log('qwe')
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));
    }
</script>
<?php


$script = <<<JS
      function thousands_separators(num) {

            var number = Number(Math.round(num + 'e2') + 'e-2')
            var num_parts = number.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");
        }

    $(document).ready(function(){

        // CASH RECIEVED AND DISBURSEMENT
        $.ajax({
            type:'POST',
            url:window.location.pathname + '?r=report/get-cash',
            success:function(data){
                var res = JSON.parse(data)
                $('#total_cash_disbursed').text(thousands_separators(res['cash_balance']))
                $('#total_amount_pending').text(thousands_separators(res['total_amount_pending']))
                $('#cash_balance_per_accounting').text(thousands_separators(res['cash_balance_per_accounting']))
            }
        })
    })
        $(document).on('click','.fc-daygrid-day-number',function(){
            var date = $(this).closest('td').attr('data-date');
            //   console.log( $(this).closest('.fc-day'))
              var url = window.location.pathname + '?r=event/create&date='+date
            $('#genericModal').modal('show').find('#modalContent').load(url);
        })

JS;
$this->registerJs($script);

?>