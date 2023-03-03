<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Location */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>
<div class="location-view panel panel-default container">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonCreate']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'location',
            [
                'attribute' => 'is_nc',
                'value' => function ($model) {
                    $is_nc = [
                        '0' => 'Office',
                        '1' => 'NC'
                    ];
                    return $is_nc[$model->is_nc];
                }
            ],
            [
                'attribute' => 'fk_office_id',
                'value' => function ($model) {
                    return $model->office->office_name;
                }
            ],
            [
                'attribute' => 'fk_division_id',
                'value' => function ($model) {
                    return strtoupper($model->divisions->division);
                }
            ]
        ],
    ]) ?>

</div>
<style>
    .container {
        padding: 3rem;
    }
</style>