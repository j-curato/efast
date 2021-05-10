<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdvancesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advances';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advances-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Advances', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    // ADVANCE ACCOUNTING ENTRIES AND MODEL NAA SA CONTROLLER GE CHANGE
    $gridColumn = [

        'id',
        'advances.nft_number',
        [
            "label" => "DV Number",
            "attribute" => "att3",
            "value" => "cashDisbursement.dvAucs.dv_number"
        ],
        [
            "label" => "Mode of Payment",
            "attribute" => "cashDisbursement.mode_of_payment"
        ],
        [
            "label" => "Check Number",
            "attribute" => "cashDisbursement.check_or_ada_no"
        ],
        [
            "label" => "ADA Number",
            "attribute" => "cashDisbursement.ada_number"
        ],
        [
            "label" => "Check Date",
            "attribute" => "cashDisbursement.issuance_date"
        ],
        [
            "label" => "Payee",
            "attribute" => "cashDisbursement.dvAucs.payee.account_name"
        ],
        [
            "label" => "Particular",
            "attribute" => "cashDisbursement.dvAucs.particular"
        ],
        [
            "label" => "Amount",
            "attribute" => "amount",
            'hAlign'=>'right'
        ],
        [
            "label" => "Book",
            "attribute" => "cashDisbursement.book.name"
        ],
        [
            "label" => "Report",
            "attribute" => "advances.report_type"
        ],

        [
            "label" => "Province",
            "attribute" => "advances.province"
        ],
        [
            "label" => "Fund Source",
            "attribute" => "advances.particular"
        ],
        [
            "label" => "UACS Code",
            "attribute" => "subAccount1.object_code"
        ],
        [
            "label" => "UACS Code",
            "attribute" => "subAccount1.name"
        ],

        ['class' => 'yii\grid\ActionColumn'],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
        ],
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Advances',
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
        'columns' => $gridColumn
    ]); ?>

    <style>
        .grid-view td {
            white-space: normal;
            width: 10rem;
            padding: 0;
        }
    </style>

            
</div>