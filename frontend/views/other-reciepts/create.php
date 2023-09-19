<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OtherReciepts */

$this->title = 'Create Other Reciepts';
$this->params['breadcrumbs'][] = ['label' => 'Other Reciepts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="other-reciepts-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
