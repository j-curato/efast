<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashReceived */

$this->title = 'Create Cash Receive';
$this->params['breadcrumbs'][] = ['label' => 'Cash Recieveds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-recieved-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
