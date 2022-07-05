<?php

use app\models\ChartOfAccounts;
use app\models\DvAucs;
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
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "List of Pending DV's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">

    <div class="container panel panel-default">


    </div>
    <?php
    // SELECT * from dv_aucs where dv_aucs.id NOT IN
    //(SELECT DISTINCT cash_disbursement.dv_aucs_id from cash_disbursement WHERE cash_disbursement.dv_aucs_id IS NOT NULL)
    $query = DvAucs::find()
        ->join('LEFT JOIN', "(SELECT SUM(dv_aucs_entries.amount_disbursed) as total_disbursed
        ,dv_aucs_entries.dv_aucs_id from dv_aucs_entries GROUP BY dv_aucs_entries.dv_aucs_id) as qwe", 'dv_aucs.id = qwe.dv_aucs_id')
        ->where("dv_aucs.id NOT IN 
        (SELECT DISTINCT cash_disbursement.dv_aucs_id from cash_disbursement WHERE cash_disbursement.dv_aucs_id IS NOT NULL)")
        ->andWhere('qwe.total_disbursed >0')
        ->andWhere('dv_aucs.is_cancelled =0')
        ->andWhere('dv_aucs.id !=9026')
        ;

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
    $gridColumn = [

        'id',
        'reporting_period',
        'dv_number',
        'payee.account_name',
        [
            'label' => 'Amount Disbursed',
            'value' => function ($model) {

                $query = (new \yii\db\Query())
                    ->select("SUM(dv_aucs_entries.amount_disbursed) total_disbursed")
                    ->from("dv_aucs")
                    ->join("LEFT JOIN", 'dv_aucs_entries', 'dv_aucs.id = dv_aucs_entries.dv_aucs_id')
                    ->where("dv_aucs.id = :id", ['id' => $model->id])
                    ->one();

                return $query['total_disbursed'];
            },
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],
        'is_cancelled'

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => "List of Pending DV's",
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Advances/Liquidation',
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]

                ]),
                'options' => ['class' => 'btn-group mr-2', 'style' => 'margin-right:20px']
            ],

        ],
        'columns' => $gridColumn
    ]); ?>


    <script src="/js/jquery.min.js" type="text/javascript"></script>
    <link href="/js/select2.min.js" />
    <link href="/css/select2.min.css" rel="stylesheet" />
    <link href="/js/jquery.dataTables.js" />
    <link href="/css/jquery.dataTables.css" rel="stylesheet" />
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
    .select2-selection--single {
        /* border: 1px solid #d2d6de; */
        border-radius: 0;
        /* padding: 6px ; */
        height: 34px;

    }

    .select2-container--default .select2-selection--single,
    .select2-selection .select2-selection--single {
        /* border: 1px solid #d2d6de; */
        /* border-radius: 0; */
        padding: 6px;
        /* height: 34px; */
    }



    .square-icon {
        font-size: 20px;
    }

    .serial {
        margin-top: 8px;
    }

    .head {
        text-align: center;
        font-weight: bold;
    }

    td {
        border: 1px solid black;
        padding: .5rem;
    }

    table {
        margin: 12px;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }

    .ors_a {
        border-top: 1px solid white;
        border-right: 1px solid white;
        border-bottom: 1px solid white;
    }

    .ors_b {
        border-top: 1px solid white;
        border-right: 1px solid white;
        border-bottom: 1px solid white;
        border-left: 1px solid white;
    }

    @media print {
        .actions {
            display: none;
        }

        .krajee-datepicker {
            border: 1px solid white;
            font-size: 10px;
            padding-left: 9px;
        }

        /* .select2-selection__rendered{
            text-decoration: underline;
        } */
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid white;
            padding: 0;
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

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
            font-size: 10px;
        }

        @page {
            size: auto;
            margin: 0;
            margin-top: 0.5cm;
        }



        .container {
            margin: 0;
            top: 0;
        }

        .entity_name {
            font-size: 5pt;
        }



        .container {

            border: none;
        }


        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        /* thead {
                display: table-header-group
            } */

        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS


JS;
$this->registerJs($script);
?>