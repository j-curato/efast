<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AdvancesReportType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Advances Report Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="advances-report-type-view">

    <div class="container card">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
            ],
        ]) ?>
    </div>
</div>
<style>
    .card {
        padding: 1rem;
    }
</style>