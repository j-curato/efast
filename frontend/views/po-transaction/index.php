<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use kartik\grid\GridView;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\export\ExportMenu;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transaction-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'], ['class' => 'btn btn-success lrgModal']); ?>
    </p>
    <?php
    $gridColumn = [

        'tracking_number',
        'po_responsibility_center_id',
        'payee:ntext',
        'particular:ntext',
        [
            'attribute' => 'amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]
        ],
        'payroll_number',
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                // $updateBtn = Yii::$app->user->can('update_books') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                $updateBtn = Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'lrgModal']);
                return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
            }
        ],
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Transactions'
        ],
        'pjax' => true,
        'toolbar' => [
            [
                'content' =>
                ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns'  => $gridColumn,
                    'filename' => 'Liquidations',
                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_HTML => false,

                    ]
                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'columns' => $gridColumn
    ]); ?>


</div>


<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    [
        'depends' => [JqueryAsset::class]
    ]
);

?>