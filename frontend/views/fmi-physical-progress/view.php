<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiPhysicalProgress */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => ' Physical Progresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-physical-progress-view">

    <div class="container">

        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) ?>
            </span>
        </div>
        <div class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'serial_number',
                    'fk_fmi_subproject_id',
                    'date',
                    'physical_target',
                    'physical_accomplished',
                ],
            ]) ?>
        </div>
    </div>
</div>