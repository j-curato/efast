<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NetAssetEquity */

$this->title = 'Create Net Asset Equity';
$this->params['breadcrumbs'][] = ['label' => 'Net Asset Equities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="net-asset-equity-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
