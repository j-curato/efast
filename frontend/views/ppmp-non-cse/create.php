<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PpmpNonCse */

$this->title = 'Create Ppmp Non Cse';
$this->params['breadcrumbs'][] = ['label' => 'Ppmp Non Cses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppmp-non-cse-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
