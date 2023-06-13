<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Accics */

$this->title = 'Create ACIC`s';
$this->params['breadcrumbs'][] = ['label' => 'Accics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="accics-create">


    <?= $this->render('_form', [
        'model' => $model,
        'cashItems' => [],
        'cashRcvItems' => [],
        'cancelledItems' => [],
    ]) ?>

</div>