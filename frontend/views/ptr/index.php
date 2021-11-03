<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PtrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ptrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ptr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ptr', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'PTR'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ptr_number',
            'par_number',
            'transfer_type',
            'date',
            'reason:ntext',
            //'from',
            //'to',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>