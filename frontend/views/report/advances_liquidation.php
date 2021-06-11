<?php

use app\models\AdvancesEntries;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "List of Pending DV's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">
    <?php




    // add conditions that should always apply here

    $gridColumn = [
        'check_date',
        'check_number',
        'is_cancelled',
        'dv_number',
        'reporting_period',
        'fund_source',
        'payee',
        'particular',
        'gl_object_code',
        'gl_account_title',
        [
            'label' => 'Cash Advances Received',
            'attribute' => 'amount'
        ],
        [
            'attribute' => 'withdrawals',
            'hAlign' => 'right'
        ],
        [
            'attribute' => 'vat_nonvat',
            'hAlign' => 'right'
        ],
        [
            'attribute' => 'expanded_tax',
            'hAlign' => 'right'
        ],
        [
            'label' => "Total Tax",
            'value' => function ($model) {
                $q= $model->vat_nonvat + $model->expanded_tax;
                return $q;
            }   
        ],
        [
            'label' => "Gross Payment",
            'value' => function ($model) {
                $w=$model->withdrawals - ($model->vat_nonvat + $model->expanded_tax);
                return  $w  ;
            }
        ],

        'report_type',
        'sl_object_code',
        'sl_account_title'
    ];


    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Unobligated Transactions',
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
                        // ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]

                ]),
                'options' => ['class' => 'btn-group mr-2', 'style' => 'margin-right:20px']
            ],

        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => $gridColumn,
    ]); ?>

</div>
<script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
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


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

        $('#generate').click(function(e){
            e.preventDefault();
            
            $.pjax({
                container:'#cibr',
                url:window.location.pathname +"?r=report/cibr",
                data:$("#filter").serialize()
            })
        })
JS;
$this->registerJs($script);
?>