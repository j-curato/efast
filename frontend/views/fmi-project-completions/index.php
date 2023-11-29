<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FmiProjectCompletionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fmi Project Completions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-project-completions-index">


    <p>
        <?= Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-plus"></i> Create', ['create'], ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
            'heading' => 'List of Project Completions'
        ],
        'columns' => [

            'fk_office_id',
            'fk_fmi_subproject_id',
            'serial_number',
            'completion_date',
            //'turnover_date',
            //'spcr_link:ntext',
            //'certificate_of_project_link:ntext',
            //'certificate_of_turnover_link:ntext',
            //'reporting_period',
            //'created_at',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    $updateBtn = Yii::$app->user->can('super-user') ? Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'mdModal']) : '';
                    return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $model->id]) . ' ' . $updateBtn;
                }
            ]
        ],
    ]); ?>


</div>