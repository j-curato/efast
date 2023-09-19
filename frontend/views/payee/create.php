<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payee */

$this->title = 'Create Payee';
$this->params['breadcrumbs'][] = ['label' => 'Payees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payee-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
