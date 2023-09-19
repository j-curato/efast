<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundCategoryAndClassificationCode */

$this->title = 'Create Fund Category And Classification Code';
$this->params['breadcrumbs'][] = ['label' => 'Fund Category And Classification Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fund-category-and-classification-code-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
