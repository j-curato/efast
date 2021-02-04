<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MajorAccounts */

$this->title = 'Create Major Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Major Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="major-accounts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
