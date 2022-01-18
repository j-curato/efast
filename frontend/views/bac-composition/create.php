<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BacComposition */

$this->title = 'Create Bac Composition';
$this->params['breadcrumbs'][] = ['label' => 'Bac Compositions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-composition-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
