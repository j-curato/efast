<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RoFurSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ' FURS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-fur-index">


    <p>
        <?= Html::a('Create Ro Fur', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'header' => 'FUR'
        ],
        'columns' => [
            'from_reporting_period',
            'to_reporting_period',
            'division',
            [
                'label' => 'Document Receive',
                'attribute' => 'document_recieve_id',
                'value' => function ($model) {
                    return $model->documentReceive->name;
                }
            ],
            ['class' => 'kartik\grid\ActionColumn'],
        ],
    ]); ?>


</div>