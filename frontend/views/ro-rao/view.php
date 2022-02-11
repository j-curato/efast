<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RoRao */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'RAO', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ro-rao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=ro-rao/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>
    </p>

    <?php

    $gridColumn =  [
        'reporting_period',
        'allotment_number',
        'ors_number',
        'payee',
        'particular',
        'document_name',
        'fund_cluster_code_name',
        'financing_source_code_name',
        'fund_category_and_classification_code_name',
        'authorization_code_name',
        'mfo_pap_code_name',
        'fund_source_name',
        'uacs',
        'general_ledger',
        'book_name',
        [
            'attribute' => 'ors_amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]
        ],
        [
            'attribute' => 'allotment_amount',
            'hAlign' => 'right',
            'format' => ['decimal', 2]
        ],
        'division',
        'is_cancelled'
    ];

    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Rao',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
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
        'columns' => $gridColumn
    ]); ?>

</div>