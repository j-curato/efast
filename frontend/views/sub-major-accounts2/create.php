<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubMajorAccounts2 */

$this->title = 'Create Sub Major Accounts2';
$this->params['breadcrumbs'][] = ['label' => 'Sub Major Accounts2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-major-accounts2-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
