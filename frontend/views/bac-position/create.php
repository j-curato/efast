<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BacPosition */

$this->title = 'Create Bac Position';
$this->params['breadcrumbs'][] = ['label' => 'Bac Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-position-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
