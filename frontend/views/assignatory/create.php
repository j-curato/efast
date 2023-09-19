<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assignatory */

$this->title = 'Create Asignatory';
$this->params['breadcrumbs'][] = ['label' => 'Asignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignatory-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
