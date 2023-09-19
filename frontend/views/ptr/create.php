<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */

$this->title = 'Create Ptr';
$this->params['breadcrumbs'][] = ['label' => 'Ptrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ptr-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
