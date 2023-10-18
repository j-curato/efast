<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GeneralJournalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'General Journals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="general-journal-index">


    <p>
        <?= Yii::$app->user->can('create_ro_general_journal') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success ']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'General Journals'
        ],
        'columns' => [
            [
                'label' => 'Book',
                'attribute' => 'book_id',
                'value' => 'book.name'
            ],
            'reporting_period',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('update_ro_general_journal') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => '']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ],
        ],
    ]); ?>


</div>