<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MajorAccounts */

$this->title = 'Update Major Accounts: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Major Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="major-accounts-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
