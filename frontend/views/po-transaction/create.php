<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoTransaction */

$this->title = 'Create  Transaction';
$this->params['breadcrumbs'][] = ['label' => ' Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-transaction-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'requestedBy' => [],
    ]) ?>

</div>