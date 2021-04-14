<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashRecieved */

$this->title = 'Create Cash Recieved';
$this->params['breadcrumbs'][] = ['label' => 'Cash Recieveds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-recieved-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
