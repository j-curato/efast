<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevBeginningBalance */

$this->title = 'Create Jev Beginning Balance';
$this->params['breadcrumbs'][] = ['label' => 'Jev Beginning Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-beginning-balance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
