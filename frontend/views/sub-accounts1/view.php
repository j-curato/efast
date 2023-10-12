<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */

$this->title = $model->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sub-accounts1-view">
    <div class="container card" style="padding: 2rem;">
        <p>
            <?= Yii::$app->user->can('update_sub_account_1') ?  Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : ''; ?>
            <?= Yii::$app->user->can('create_sub_account_2') ?  Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate']) : ''; ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'chart_of_account_id',
                'object_code',
                'name',
            ],
        ]) ?>
    </div>

</div>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]) ?>