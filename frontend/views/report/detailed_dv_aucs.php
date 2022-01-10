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

$this->title = "Detailed Dv";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">
    <?php




    $gridColumn = [
        'dv_number',
        'reporting_period',
        'obligation_number',
        'transaction_tracking_number',
        'payee',
        'particular',
        'total_dv',
        'total_vat',
        'total_ewt',
        'total_compensation',
        'mfo_name',
        'mfo_code',
        'allotment_number',
        'allotment_object_code',
        'allotment_account_title',
        'allotment_class',
        'obligation_object_code',
        'obligation_account_title',
        'obligation_amount',
        'total_obligation',
        'dv_amount',
        'dv_vat',
        'dv_ewt',
        'dv_compensation',
        'mode_of_payment',
        'check_or_ada_no',
        'ada_number',
        'issuance_date',
        'nature_transaction_name',
        'mrd_name',
        [
            'label' => 'Good/Cancelled',
            'value' => function ($model) {
                if ($model->is_cancelled) {
                    return 'Cancelled';
                } else {
                    return 'Good';
                }
            }
        ],
        'jev_number',

    ];


    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Detailed DV',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'pjax' => true,
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Detailed_Dv',
                    'batchSize' => 1,
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
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }
</style>
<script src="/js/jquery.min.js" type="text/javascript"></script>
<link href="/js/select2.min.js" />
<link href="/css/select2.min.css" rel="stylesheet" />
<link href="/js/jquery.dataTables.js" />
<link href="/css/jquery.dataTables.css" rel="stylesheet" />

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