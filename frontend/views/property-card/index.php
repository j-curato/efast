<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertyCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Cards';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-card-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Property Card', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'Property Cards'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'pc_number',
            'balance',
            'par_number',

            ['class' => 'yii\grid\ActionColumn'],
        ],
        'export' => [
            'fontAwesome' => true
        ],

    ]); ?>


</div>