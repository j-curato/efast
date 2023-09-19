<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundSourceType */

$this->title = 'Create Fund Source Type';
$this->params['breadcrumbs'][] = ['label' => 'Fund Source Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-source-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
