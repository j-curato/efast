<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RadaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RADAIs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="radai-index">


    <p>
        <?= Html::a('Create RADAI', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'RADAIs'
        ],
        'pjax' => true,
        'columns' => [

            'serial_number',
            'reporting_period',
            'date',
            [

                'attribute' => 'fk_book_id',
                'value' => function ($model) {
                    return $model->book->name ?? '';
                }
            ],

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