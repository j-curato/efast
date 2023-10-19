<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashReceived */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Recieveds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cash-recieved-view">


    <div class="container card" style="padding: 1rem;">

        <p>
            <?= Yii::$app->user->can('update_cash_receive') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'document_recieved_id',
                'book_id',
                'mfo_pap_code_id',
                'date',
                'reporting_period',
                'nca_no',
                'nta_no',
                'nft_no',
                'purpose',
                'amount',
            ],
        ]) ?>
    </div>

</div>

<?php

$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>