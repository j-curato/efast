<?php

use app\components\helpers\MyHelper;
use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDisbursementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Disbursements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-disbursement-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



    <?php
    $gridColumn = [
        // ['class' => 'yii\grid\SerialColumn'],

        // 'book_id',
        [
            "label" => "Book",
            "attribute" => "book_id",
            "value" => "book.name"
        ],
        'reporting_period',
        'mode_of_payment',
        'check_or_ada_no',
        'ada_number',
        'issuance_date',
        [
            'label' => "DV Number",
            "attribute" => "dv_aucs_id",
            'value' => 'dvAucs.dv_number'
        ],
        [
            'label' => "Payee",
            "attribute" => "dvAucs.payee.account_name"
        ],
        [
            'label' => "Particular",
            "attribute" => "dvAucs.particular"
        ],
        [
            'label' => "Amount Disbursed",
            'format' => ['decimal', 2],
            'value' => function ($model) {
                $query = (new \yii\db\Query())
                    ->select(["SUM(dv_aucs_entries.amount_disbursed) as total_disbursed"])
                    ->from('dv_aucs')
                    ->join("LEFT JOIN", "dv_aucs_entries", "dv_aucs.id = dv_aucs_entries.dv_aucs_id")
                    ->where("dv_aucs.id =:id", ['id' => $model->dv_aucs_id])
                    ->andWhere("dv_aucs_entries.is_deleted !=1")
                    ->one();

                return $query['total_disbursed'];
            }
        ],
        [
            'label' => 'Good/Cancelled',
            'attribute' => 'is_cancelled',
            'value' => function ($model) {
                $model->is_cancelled ? $q = 'cancelled' : $q = 'Good';
                return $q;
            }
        ],

        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                return MyHelper::gridDefaultAction($model->id, '');
            }
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Cash Disbursements'
        ],
        'pjax' => true,
        'toolbar' =>  [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $gridColumn,
                    'filename' => 'Cash Disbursements',
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