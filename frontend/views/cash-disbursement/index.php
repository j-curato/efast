<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDisbursementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Disbursements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-disbursement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cash Disbursement', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    $gridColumn = [

        // 'id',
        // 'book_id',
        [
            "label" => "Book",
            "attribute" => "book_id",
            "value" => "book.name"
        ],
        'reporting_period',
        'mode_of_payment',
        'check_or_ada_no',
        'is_cancelled',
        'issuance_date',
        [
            'label' => "DV Number",
            "attribute" => "dvAucsEntries.dvAucs.dv_number"
        ],
        [
            'label' => "Payee",
            "attribute" => "dvAucsEntries.dvAucs.payee.account_name"
        ],
        [
            'label' => "Paricular",
            "attribute" => "dvAucsEntries.dvAucs.particular"
        ],
        [
            'label' => "Amount Disbursed",
            "attribute" => "dvAucsEntries.amount_disbursed"
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
                    'filename' => 'Jev',
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


</div>