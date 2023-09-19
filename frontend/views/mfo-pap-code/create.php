<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MfoPapCode */

$this->title = 'Create Mfo Pap Code';
$this->params['breadcrumbs'][] = ['label' => 'Mfo Pap Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mfo-pap-code-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
