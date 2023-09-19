<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TransferType */

$this->title = 'Create Transfer Type';
$this->params['breadcrumbs'][] = ['label' => 'Transfer Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
