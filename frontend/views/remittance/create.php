<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Remittance */

$this->title = 'Create Remittance';
$this->params['breadcrumbs'][] = ['label' => 'Remittances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="remittance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
