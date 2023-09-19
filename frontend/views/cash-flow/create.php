<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashFlow */

$this->title = 'Create Cash Flow';
$this->params['breadcrumbs'][] = ['label' => 'Cash Flows', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-flow-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
