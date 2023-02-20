<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupervisorValidationNotesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supervisor Validation Notes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supervisor-validation-notes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Supervisor Validation Notes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => 'primary',
        ],
        'columns' => [

            'employee_name:ntext',
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ]
        ],
    ]); ?>


</div>