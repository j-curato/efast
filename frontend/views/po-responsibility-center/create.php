<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoResponsibilityCenter */

$this->title = 'Create Po Responsibility Center';
$this->params['breadcrumbs'][] = ['label' => 'Po Responsibility Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-responsibility-center-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
