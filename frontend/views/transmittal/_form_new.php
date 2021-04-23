<?php

use app\models\CashDisbursement;
use app\models\CashDisbursementSearch;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transmittal-form">



    <?php
    $searchModel = new CashDisbursementSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $gridColumns = [
        [
            'label' => 'ID',
            'attribute' => 'id'
        ],
        [
            'label' => 'DV Number',
            'attribute' => 'dvAucs.dv_number'
        ],
        [
            'label' => 'Check/ada Number',
            'value' => function ($model) {
                $q = $model->mode_of_payment  . '-' . $model->check_or_ada_no;
                return $q;
            }
        ],
        [
            'label' => 'Payee',
            'attribute' => 'dvAucs.payee.account_name'

        ],
        [
            'label' => 'Particular',
            'attribute' => 'dvAucs.particular'

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
                    ->one();
                return $query['total_disbursed'];
            }
        ],
    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            // 'heading' => 'List of Areas',
        ],
        'export' => false,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',

        ],

        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => $gridColumns,
    ]); ?>

</div>