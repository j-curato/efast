<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NatureOfTransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Nature Of Transactions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nature-of-transaction-index">


    <p>
        <?= Yii::$app->user->can('create_nature_of_transaction') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success mdModal']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' =>
        [
            'type' => 'primary',
            'heading' => 'Nature of Transactions'
        ],
        'columns' => [

            'name',
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_nature_of_transaction') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>