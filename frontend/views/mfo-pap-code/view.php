<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MfoPapCode */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mfo Pap Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mfo-pap-code-view">


    <p>
        <?= Yii::$app->user->can('update_mfo_pap_code') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'name',
            'description',
            'division'
        ],
    ]) ?>

</div>
<?php

