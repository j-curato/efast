<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvTransactionType */

$this->title = 'Create Dv Transaction Type';
$this->params['breadcrumbs'][] = ['label' => 'Dv Transaction Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-transaction-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
