<?php

use app\models\FundClusterCode;
use app\models\recordAllotmentEntriesSearch;
use app\models\RecordAllotmentsSearch;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RecordAllotmentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MAF';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="maf-index">

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-plus"></i> Create MAF', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
    $col = [
        'budget_year',
        'reporting_period',
        'date_issued',
        'valid_until',
        'allotmentNumber',
        'office_name',
        'division',
        [
            'attribute' => 'mfo_name',
            'value' => function ($model) {
                return $model->mfo_code . '-' . $model->mfo_name;
            }
        ],
        'fund_source_name',
        'uacs',
        'account_title',
        'particulars',
        'document_recieve',
        'fund_cluster_code',
        'financing_source_code',
        'fund_classification',
        'authorization_code',
        'responsibility_center',
        'allotment_class',
        'nca_nta',
        'carp_101',
        'book',
        'allotment_type',
        'book_name',
        [
            'attribute' => 'amount',
            'label' => 'Allotment Amount',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlOrsAmt',
            'label' => 'Total Ors',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlPrAmt',
            'label' => 'Total In Purchase Request',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'ttlTrAmt',
            'label' => 'Total in Transaction',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'balance',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'balAfterObligation',
            'label' => 'Balance After Obligation',
            'format' => ['decimal', 2]
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {

                $t = yii::$app->request->baseUrl . "/index.php?r=maf/update&id=$model->id";
                $view = yii::$app->request->baseUrl . "/index.php?r=maf/view&id=$model->id";
                return   ' ' . Html::a('', $view, ['class' => 'btn-xs btn-primary fa fa-eye'])
                    . ' ' . Html::a('', $t, ['class' => 'btn-xs btn-primary fa fa-pencil-square-o']);
            },
            'hiddenFromExport' => true,
        ],

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'panel' => [
            'heading' => '<h3 class="panel-title"> MAFs</h3>',
            'type' => 'primary',

        ],
        'pjax' => true,
        'toolbar' => [
            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $col,
                    'filename' => 'RecordAllotments',
                    'exportConfig' => [
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_EXCEL => false,
                        ExportMenu::FORMAT_HTML => false,
                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'columns' => $col,
    ]); ?>


</div>