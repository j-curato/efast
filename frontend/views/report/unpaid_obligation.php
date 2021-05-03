<?php

use app\models\ChartOfAccounts;
use app\models\DvAucsEntries;
use app\models\FundClusterCode;
use app\models\ProcessOrs;
use app\models\ResponsibilityCenter;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of UnPaid Obligations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <!-- <input type="text" name="" id="sample"> -->
    <div class="container panel panel-default">


    </div>
    <?php
    // SELECT * from dv_aucs_entries where dv_aucs_entries.process_ors_id IN(
    //     SELECT DISTINCT process_ors.id FROM process_ors 
    //     WHERE process_ors.id IN(SELECT DISTINCT dv_aucs_entries.process_ors_id from dv_aucs_entries WHERE dv_aucs_entries.process_ors_id IS NOT NULL 
    //     AND dv_aucs_entries.dv_aucs_id IN 
    //     (SELECT DISTINCT dv_aucs.id from dv_aucs where dv_aucs.id  NOT IN (SELECT DISTINCT cash_disbursement.dv_aucs_id FROM cash_disbursement WHERE cash_disbursement.dv_aucs_id IS NOT NULL))
    //     )
    //     )

    $query = DvAucsEntries::find()
        ->where("dv_aucs_entries.dv_aucs_id  IN (SELECT dv_aucs.id from dv_aucs
        WHERE dv_aucs.id IN
        (SELECT cash_disbursement.dv_aucs_id from cash_disbursement where cash_disbursement.dv_aucs_id IS NOT NULL))")
        ->andWhere("dv_aucs_entries.process_ors_id IS NOT NULL");
    // add conditions that should always apply here

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
    ]);

    // ob_clean();
    // echo "<pre>";
    // var_dump($dataProvider);
    // echo "</pre>";
    // return ob_get_clean();
    // die();
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Unpaid Obligations',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'id',
            [
                'label' => 'Obligation Number',
                'value' => 'processOrs.serial_number'
            ],
            [
                'label' => 'Transaction Tracking Number',
                'value' => 'processOrs.transaction.tracking_number'
            ],
            [
                'label' => 'Payee',
                'value' => 'dvAucs.payee.account_name'
            ],
            [
                'label' => 'Particulars',
                'value' => 'dvAucs.particular'
            ],

            [
                'label' => 'DV Number',
                'value' => 'dvAucs.dv_number'
            ],
            [
                'label' => 'Check/ADA Number',
                'value' => 'dvAucs.cashDisbursement.check_or_ada_no'
            ],
            [
                'label' => 'Amount Obligate',
                'value' => function ($model) {
                    $query = (new \yii\db\Query())
                        ->select('SUM(raoud_entries.amount) as total_obligated')
                        ->from('raouds')
                        ->join('LEFT JOIN', 'raoud_entries', 'raouds.id = raoud_entries.raoud_id')
                        ->where("raouds.process_ors_id = :process_ors_id", ['process_ors_id' => $model->process_ors_id])
                        ->one();
                    return $query['total_obligated'];
                },
                'format' => ['decimal', 2],
                'hAlign' => 'right'
            ],
            [
                'label' => 'Amount Disbursed',
                'value' => 'amount_disbursed',
                'format' => ['decimal', 2]
            ],
            [
                'label' => 'Tax Withheld',
                'value' => function ($model) {

                    return $model->vat_nonvat + $model->ewt_goods_services + $model->compensation;
                },
                'format' => ['decimal', 2]
            ],
            [
                'label' => 'Unpaid Obligation',
                'value' => function ($model) {
                    $total_obligated = (new \yii\db\Query())
                        ->select('SUM(raoud_entries.amount) as total_obligated')
                        ->from('raouds')
                        ->join('LEFT JOIN', 'raoud_entries', 'raouds.id = raoud_entries.raoud_id')
                        ->where("raouds.process_ors_id = :process_ors_id", ['process_ors_id' => $model->process_ors_id])
                        ->one();
                        $dv = (new \yii\db\Query())
                        ->select('SUM(dv_aucs_entries.amount_disbursed) as total_disbursed,
                        SUM(dv_aucs_entries.vat_nonvat) as total_vat,
                        SUM(dv_aucs_entries.ewt_goods_services) as total_ewt,
                        SUM(dv_aucs_entries.compensation) as total_compensation
                        ') 
                        ->from('dv_aucs_entries')
                        ->where('dv_aucs_entries.dv_aucs_id  IN (SELECT dv_aucs.id from dv_aucs
                        WHERE dv_aucs.id IN
                        (SELECT cash_disbursement.dv_aucs_id from cash_disbursement where cash_disbursement.dv_aucs_id IS NOT NULL))')
                        ->andWhere('dv_aucs_entries.process_ors_id =:process_ors_id',['process_ors_id'=> $model->process_ors_id])
                        ->one();
                    return $total_obligated ['total_obligated'] -  intval($dv['total_disbursed']) - intval($dv['total_vat'])-intval($dv['total_ewt']) - intval($dv['total_compensation']);
                   
                },
                'format' => ['decimal', 2]
            ],


        ],
    ]); ?>


    <script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
    <link href="/dti-afms-2/frontend/web/js/maskMoney.js" />
    <link href="/dti-afms-2/frontend/web/js/select2.min.js" />
    <link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
    <link href="/dti-afms-2/frontend/web/js/jquery.dataTables.js" />
    <link href="/dti-afms-2/frontend/web/css/jquery.dataTables.css" rel="stylesheet" />
    <!-- 
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({

                processing: true,
                // serverSide: true,
                ajax: {
                    url: window.location.pathname + "?r=report/pending-ors",
                    data: function(data) {
                        data.id = data.id
                    },
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'transaction_id'
                    },
                    {
                        data: 'reporting_period'
                    },
                    {
                        data: 'serial_number'
                    },
                    {
                        data: 'obligation_number'
                    },
                    {
                        data: 'funding_code'
                    },
                    {
                        data: 'document_recieve_id'
                    },
                    {
                        data: 'mfo_pap_code_id'
                    },
                    {
                        data: 'fund_source_id'
                    },
                    {
                        data: 'book_id'
                    },
                    {
                        data: 'date'
                    },
                ]
            });
        });
    </script> -->
</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 100px;
    }

    @media print {}
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

        $('#sample').maskMoney();
JS;
$this->registerJs($script);
?>