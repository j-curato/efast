<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="payee-view" id="payee-view">
    <div class="container card" style="padding: 1rem;">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary lrgModal']) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'account_name',
                'account_num',
                'registered_name',
                'contact_person',
                'registered_address',
                'contact',
                'remark',
                'tin_number',
            ],
        ]) ?>

    </div>
</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>