<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NoticeOfPostponementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notice Of Postponements';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-of-postponement-index">


    <p>
        <?= Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Notice of Postponements'
        ],
        'columns' => [

            'serial_number',
            'created_at',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = '';
                    if ($model->is_final != 1 ) {
                        $updateBtn = Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']);
                    }
                    // return MyHelper::gridDefaultAction($model->id, '');
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>
