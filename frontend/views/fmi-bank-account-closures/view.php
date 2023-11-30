<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankAccountClosure */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Account Closures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-bank-account-closure-view">


    <p>
        <?= Yii::$app->user->can('update_fmi_bank_closure') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'serial_number',
            'fk_fmi_subproject_id',
            'reporting_period',
            'date',
            'bank_certification_link:ntext',
            'created_at',
        ],
    ]) ?>

</div>