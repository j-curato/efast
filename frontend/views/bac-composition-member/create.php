<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BacCompositionMember */

$this->title = 'Create Bac Composition Member';
$this->params['breadcrumbs'][] = ['label' => 'Bac Composition Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bac-composition-member-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
