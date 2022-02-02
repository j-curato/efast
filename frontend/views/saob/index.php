<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SaobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Saobs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saob-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Saob', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'SAOB'
        ],
        'columns' => [

            'from_reporting_period',
            'to_reporting_period',

            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => 'book.name'
            ],
            [
                'label' => 'MFO/PAP Code',
                'attribute' => 'mfo_pap_code_id',
                'value' => 'mfo.code'
            ],
            [
                'label' => 'Document Recieve',
                'attribute' => 'document_recieve_id',
                'value' => 'documentRecieve.name'
            ],
            //'created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>