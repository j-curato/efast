<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NatureOfTransaction */

$this->title = 'Create Nature Of Transaction';
$this->params['breadcrumbs'][] = ['label' => 'Nature Of Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nature-of-transaction-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
