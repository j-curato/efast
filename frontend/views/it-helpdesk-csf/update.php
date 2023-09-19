<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsf */

$this->title = 'Update It Helpdesk Csf: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'It Helpdesk Csfs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="it-helpdesk-csf-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
