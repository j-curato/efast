<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiTranches */

$this->title = 'Create Fmi Tranches';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Tranches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-tranches-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
