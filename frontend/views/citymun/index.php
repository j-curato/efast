<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CitymunSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'City/Municipality';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citymun-index">


    <p>
        <?= Yii::$app->user->can('create_citymun') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'Heading' => 'City/Municipality'
        ],
        'columns' => [
            'city_mun',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' =>
                function ($model) {
                    $updateBtn = Yii::$app->user->can('update_citymun') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
<?php
?>