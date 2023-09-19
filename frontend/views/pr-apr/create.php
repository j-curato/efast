<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrApr */

$this->title = 'Create Pr Apr';
$this->params['breadcrumbs'][] = ['label' => 'Pr Aprs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-apr-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
