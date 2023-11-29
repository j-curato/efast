<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiLguLiquidations */

$this->title = 'Create  LGU Liquidations';
$this->params['breadcrumbs'][] = ['label' => ' LGU Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-lgu-liquidations-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
