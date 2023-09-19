<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundClusterCode */

$this->title = 'Create Fund Cluster Code';
$this->params['breadcrumbs'][] = ['label' => 'Fund Cluster Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-cluster-code-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
