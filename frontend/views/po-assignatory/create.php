<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoAsignatory */

$this->title = 'Create Po Asignatory';
$this->params['breadcrumbs'][] = ['label' => 'Po Asignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="po-asignatory-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
