<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubMajorAccounts2 */

$this->title = 'Update Sub Major Accounts2: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Sub Major Accounts2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sub-major-accounts2-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
