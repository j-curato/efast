<?php

use kartik\export\ExportMenu;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashDisbursementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cancel Disbursement';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-disbursement-index">


    <p>
        <?= Html::a('Create', ['cancel-disbursement'], ['class' => 'btn btn-success']) ?>
    </p>


    <?php
    $gridColumn = [
        'id',
        'book_name',
        'dv_number',
        'reporting_period',
        'mode_of_payment',
        'check_or_ada_no',
        'ada_number',
        'issuance_date',
        'parent_disbursement',
        'dv_amount',

        [
            'label' => 'View',
            'format' => 'raw',
            'value' => function ($model) {
                $t = yii::$app->request->baseUrl . "/index.php?r=cash-disbursement/view&id=$model->id";
                $delete = yii::$app->request->baseUrl . "/index.php?r=cash-disbursement/delete&id=$model->id";
                return ' ' . Html::a('', $t, ['class' => 'btn btn-primary fa fa-eye',])
                    . ' ' . Html::a('', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger fa fa-trash-alt',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]);

                // return $query['total'];
            },
            'hiddenFromExport' => true,
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Cancelled Checks'
        ],
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