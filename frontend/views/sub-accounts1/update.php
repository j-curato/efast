<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */

$this->title = 'Update Sub Account 1: ' . $model->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-accounts1-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
