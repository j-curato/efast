<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\UnitOfMeasure */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Unit Of Measures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="unit-of-measure-view">


    <div class="container card" style="padding: 1rem;">
        <p>
            <?= Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']); ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'unit_of_measure',
            ],
        ]) ?>

    </div>
</div>