<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBatches */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Batches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-batches-view">


    <div class="container">
        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) ?>
            </span>

        </div>
        <div class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'batch_name:ntext',
                ],
            ]) ?>
        </div>
    </div>



</div>