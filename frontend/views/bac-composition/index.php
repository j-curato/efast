<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BacCompositionnSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RBAC Compositions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-composition-index">


    <p>
        <?= Yii::$app->user->can('create_bac') ? Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success modalButtonCreate']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'BAC Composition'
        ],
        'columns' => [

            'effectivity_date',
            'expiration_date',
            'rso_number',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    // $updateBtn = Yii::$app->user->can('update_books') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>