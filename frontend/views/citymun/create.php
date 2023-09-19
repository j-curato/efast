<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Citymun */

$this->title = 'Create City/Municipality';
$this->params['breadcrumbs'][] = ['label' => 'Citymuns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="citymun-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
