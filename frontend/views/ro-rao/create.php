<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoRao */

$this->title = 'Create Ro Rao';
$this->params['breadcrumbs'][] = ['label' => 'Ro Raos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-rao-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
