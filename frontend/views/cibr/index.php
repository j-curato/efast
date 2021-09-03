<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CibrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cibrs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cibr-index">


    <p>
        <?php

        // if (Yii::$app->user->can('create_cibr')) {

        echo   Html::a('Create Cibr', ['create'], ['class' => 'btn btn-success']);
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
            'heading' => 'List of CIBR',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'export' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'reporting_period',
            'province',
            [
                'label' => 'Draft/Final',
                'attribute' => 'is_final',
                'value' => function ($model) {
                    $final = $model->is_final === 1 ? $final = 'Final' : 'Draft';
                    return $final;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>