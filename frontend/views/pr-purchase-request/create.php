<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseRequest */

$this->title = 'Create Pr Purchase Request';
$this->params['breadcrumbs'][] = ['label' => 'Pr Purchase Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-purchase-request-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
