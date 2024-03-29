<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NetAssetEquity */

$this->title = 'Update Net Asset Equity: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Net Asset Equities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="net-asset-equity-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
