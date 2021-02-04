<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundSource */

$this->title = 'Create Fund Source';
$this->params['breadcrumbs'][] = ['label' => 'Fund Sources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-source-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
