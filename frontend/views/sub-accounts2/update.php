<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts2 */

$this->title = 'Update Sub Accounts2: ' . $model->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-accounts2-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
