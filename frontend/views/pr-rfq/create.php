<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrRfq */

$this->title = 'Create Pr Rfq';
$this->params['breadcrumbs'][] = ['label' => 'Pr Rfqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-rfq-create">

    <?php
    $err = '';
    $items = [];
    if (!empty($error)){
        $err = $error;
    }
    if (!empty($pr_items))
        $items = $pr_items;
    ?>
    <?= $this->render('_form', [
        'model' => $model,
        'error'=>$err,
        'pr_items'=>$items
    ]) ?>

</div>
