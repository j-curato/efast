<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AssignatorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignatories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignatory-index">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Create Asignatory', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        
        'columns' => [

            'name',
            'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>