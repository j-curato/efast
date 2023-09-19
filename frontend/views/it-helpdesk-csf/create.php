<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ItHelpdeskCsf */

$this->title = 'Create It Helpdesk Csf';
$this->params['breadcrumbs'][] = ['label' => 'It Helpdesk Csfs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="it-helpdesk-csf-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
