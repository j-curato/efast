<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProccessOrsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Process Ors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Process Ors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>
    <!-- ANG MODEL ANI KAY SA PROCESS ORS ENTRIES -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'label' => 'Serial Number',
                'attribute' => 'processOrs.reporting_period',
                'value' => 'processOrs.reporting_period'
            ],
            'amount',

            [
                'label' => 'Adjust',
                'format' => 'raw',
                'value' => function ($model) {
                    $t = yii::$app->request->baseUrl . "/index.php?r=process-ors-entries/create";
                    return ' ' . Html::a('', $t, ['class' => 'btn btn-success fa fa-pencil-square-o']);
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>