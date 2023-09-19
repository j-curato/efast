<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrContractType */

$this->title = 'Create Pr Contract Type';
$this->params['breadcrumbs'][] = ['label' => 'Pr Contract Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-contract-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
