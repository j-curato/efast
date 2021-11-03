<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */

$this->title = 'Create Ptr';
$this->params['breadcrumbs'][] = ['label' => 'Ptrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ptr-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
