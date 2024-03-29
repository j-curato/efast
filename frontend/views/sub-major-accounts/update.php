<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubMajorAccounts */

$this->title = 'Update Sub Major Accounts: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sub Major Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-major-accounts-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
