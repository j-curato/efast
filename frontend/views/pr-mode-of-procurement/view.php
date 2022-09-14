<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrModeOfProcurement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Mode Of Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-mode-of-procurement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::button('<i class="glyphicon glyphicon-plus"></i> Create', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=pr-mode-of-procurement/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mode_name',
            'description',
        ],
    ]) ?>

</div>