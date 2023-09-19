<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundCategoryAndClassificationCode */

$this->title = 'Update Fund Category And Classification Code: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Category And Classification Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fund-category-and-classification-code-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
