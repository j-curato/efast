<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */

$this->title = 'Create Sub Accounts1';
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-accounts1-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
