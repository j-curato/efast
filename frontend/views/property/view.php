<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Property */

$this->title = $model->property_number;
$this->params['breadcrumbs'][] = ['label' => 'Properties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="property-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->property_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->property_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="container">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'property_number',

                [
                    'label' => 'Book',
                    'attribute' => 'book.name'
                ],
                [
                    'label' => 'Unit of Measure',
                    'attribute' => 'unitOfMeasure.unit_of_measure'
                ],

                'iar_number',
                'article',
                [
                    'label' => 'Description',
                    'value' => function ($model) {
                        return     preg_replace('#\[n\]#', "\n", $model->description);
                    }
                ],

                'model',
                'serial_number',
                'quantity',
                'acquisition_amount'
            ],
        ]) ?>
    </div>


</div>
<style>
    .container {
        background-color: white;
    }
</style>