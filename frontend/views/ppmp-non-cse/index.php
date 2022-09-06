<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PpmpNonCseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ppmp Non Cses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmp-non-cse-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Ppmp Non Cse', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' =>
        [
            'type' => 'primary',
            'heading' => 'PPMP NON-CSE'
        ],
        'columns' => [

            'ppmp_number',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>