<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GeneralLedger */

$this->title = 'Create General Ledger';
$this->params['breadcrumbs'][] = ['label' => 'General Ledgers', 'url' => ['index']];
?>
<div class="general-ledger-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>