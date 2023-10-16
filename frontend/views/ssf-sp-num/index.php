<?php

use app\components\helpers\MyHelper;
use kartik\export\ExportMenu;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SsfSpNumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'SSF SP No.';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssf-sp-num-index">


    <p>
        <?= Yii::$app->user->can('create_ssf_sp_num') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>
    </p>

    <?php
    $cols = [

        'budget_year',
        'fk_office_id',
        'fk_citymun_id',
        'project_name:ntext',
        'cooperator',
        'equipment:ntext',
        'amount',
        'date',
        [
            'label' => 'Actions',
            'format' => 'raw',
            'value' => function ($model) {
                $updateBtn = Yii::$app->user->can('update_ssf_sp_num') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
            }
        ]
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'SSF SP No. List'

        ],
        'toolbar' => [


            [
                'content' => ExportMenu::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => $cols,
                    'filename' => "DV",
                    'batchSize' => 10,
                    'stream' => false,
                    'target' => '_popup',

                    'exportConfig' => [
                        ExportMenu::FORMAT_CSV => false,
                        ExportMenu::FORMAT_TEXT => false,
                        ExportMenu::FORMAT_PDF => false,
                        ExportMenu::FORMAT_HTML => false,
                        ExportMenu::FORMAT_EXCEL => false,

                    ]

                ]),
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'pjax' => true,
        'export' => false,
        'columns' => $cols

    ]); ?>


</div>
<?php

$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depeneds' => JqueryAsset::class]);
?>