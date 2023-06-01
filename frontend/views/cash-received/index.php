<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CashReceivedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cash Receive';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-recieved-index">


    <p>
        <?= Html::a('Create Cash Received', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>

    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Cash Receives'
        ],
        'export' => false,
        'columns' => [

            'date',
            'reporting_period',
            [
                'label' => 'Document Recieved',
                'attribute' => 'documentRecieved.name'
            ],
            [
                'label' => "Book",
                'attribute' => 'book.name'
            ],


            'nca_no',
            'nta_no',
            'nft_no',
            'purpose',
            // 'mfo_pap_code_id',
            [
                'label' => 'Amount',
                'attribute' => 'amount',
                'format' => ['decimal', 2],
            ],

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>

    <style>
        .grid-view td {
            white-space: normal;
            width: 5rem;
        }
    </style>
</div>

<?php

$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>