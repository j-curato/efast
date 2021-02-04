<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubMajorAccounts */

$this->title = 'Create Sub Major Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Sub Major Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-major-accounts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
