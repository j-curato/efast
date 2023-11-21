<?php


/* @var $this yii\web\View */
/* @var $model app\models\NotificationToPay */

$this->title = 'Create Notification To Pay';
$this->params['breadcrumbs'][] = ['label' => 'Notification To Pays', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-to-pay-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>