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
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of UnPaid Obligations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
    <!-- <input type="text" name="" id="sample"> -->


    <form id="filter">

        <div class="row" style="padding: 2rem;">
            <div class="col-sm-3">
                <label for="" style="color:red"> *Please Re-Generate To Update Data</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'year',
                    'pluginOptions' => [
                        'format' => 'yyyy',
                        'autoclose' => true,
                        'minViewMode' => 'years'
                    ],
                    'options' => []
                ])
                ?>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-success" style="margin-top: 2.3rem;">
                    Generate
                </button>
            </div>
        </div>
    </form>
    <?php
    $gridColumn = [
        'reporting_period',
        'serial_number',

        [
            'label' => 'Total ORS  Amount',
            'attribute' => 'total_amount',
            'format' => ['decimal', 2],
            'hAlign' => 'right'
        ],

        [
            'attribute' => 'total_amount_disbursed',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'unpaid_obligation',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        'dv_number',
        'check_number',
        [
            'attribute' => 'amount_disbursed',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'vat_nonvat',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'vat_nonvat',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'ewt_goods_services',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'compensation',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'other_trust_liabilities',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],
        [
            'attribute' => 'total_withheld',
            'format' => ['decimal', 2],
            'hAlign' => 'right'

        ],




    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Unpaid Obligations',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'pjax' => true,
        'pjaxSettings' => [
            'options' => [
                'id' => 'detailed_dv_pjax'
            ]
        ],
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
        'columns' => $gridColumn
    ]); ?>



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
<script>
    $(document).ready(function() {

    })
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

        $(document).ready(function(){
                $('#filter').submit(function(e) {
                e.preventDefault()
                $.pjax({
                    container: "#detailed_dv_pjax",
                    url: window.location.href,
                    type: 'POST',
                    data:$("#filter").serialize()
                });

            })
          
        })
JS;
$this->registerJs($script);
?>