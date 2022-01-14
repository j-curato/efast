<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BacCompositionMember */

$this->title = 'Update Bac Composition Member: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bac Composition Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bac-composition-member-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
