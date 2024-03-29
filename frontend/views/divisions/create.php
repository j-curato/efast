<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Divisions */

$this->title = 'Create Divisions';
$this->params['breadcrumbs'][] = ['label' => 'Divisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="divisions-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
