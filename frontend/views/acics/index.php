<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccicsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ACCIC`s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accics-index">


    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'ACCIC`s'
        ],
        'columns' => [

            'serial_number',
            'fk_book_id',
            'date_issued',
            'created_at',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id, 'none');
                }
            ],
        ],
    ]); ?>


</div>