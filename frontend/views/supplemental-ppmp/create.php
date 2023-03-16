<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmp */

$this->title = 'Create Supplemental Ppmp';
$this->params['breadcrumbs'][] = ['label' => 'Supplemental Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplemental-ppmp-create">


    <?= $this->render('_form', [
        'model' => $model,
        'action' => $action,
    ]) ?>

</div>