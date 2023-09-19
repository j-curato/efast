<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RemittancePayee */

$this->title = 'Create Remittance Payee';
$this->params['breadcrumbs'][] = ['label' => 'Remittance Payees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remittance-payee-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
