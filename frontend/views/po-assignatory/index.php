<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoAsignatorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Asignatories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-asignatory-index">


    <p>
        <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'], ['class' => 'btn btn-success modalButtonCreate']); ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => Gridview::TYPE_PRIMARY,
            'heading' => 'List of Asignatory'
        ],
        'columns' => [

            'name',
            'position',

            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return MyHelper::gridDefaultAction($model->id);
                }
            ],
        ],
    ]); ?>


</div>