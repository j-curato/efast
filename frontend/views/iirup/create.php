<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Iirup */

$this->title = 'Create Iirup';
$this->params['breadcrumbs'][] = ['label' => 'Iirups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iirup-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>