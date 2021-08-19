<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cdrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdr-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>


        <?php

        // if (Yii::$app->user->can('create_cdr')) {
            echo Html::a('Create Cdr', ['create'], ['class' => 'btn btn-success']);
        // }
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of CDR',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'serial_number',
            'reporting_period',
            'province',
            'book_name',
            //'report_type',
            //'is_final',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>