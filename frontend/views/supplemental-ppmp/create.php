<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmp */

$this->title = 'Create Supplemental Ppmp';
$this->params['breadcrumbs'][] = ['label' => 'Supplemental Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplemental-ppmp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action,
    ]) ?>

</div>