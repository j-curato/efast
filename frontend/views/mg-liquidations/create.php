<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MgLiquidations */

$this->title = 'Create MG Liquidations';
$this->params['breadcrumbs'][] = ['label' => 'Mg Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mg-liquidations-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
