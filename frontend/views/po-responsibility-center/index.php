<?php

use app\components\helpers\MyHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoResponsibilityCenterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Po Responsibility Centers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-responsibility-center-index">


    <p>
        <?php
        $display = 'display:none';
        $province = '';
        if (Yii::$app->user->can('ro_accounting_admin')) {
            echo Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'], ['class' => 'modalButtonCreate btn btn-success']);
            $display = '';
            $province = 'province';
        }
        ?>
    </p>


    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Responsibility Center',
        ],
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'columns' => [

            'province',

            'name',
            'description:ntext',
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