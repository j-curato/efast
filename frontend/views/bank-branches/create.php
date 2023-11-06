<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BankBranches */

$this->title = 'Create Bank Branches';
$this->params['breadcrumbs'][] = ['label' => 'Bank Branches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-branches-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
