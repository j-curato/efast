<?php

use yii\db\Query;
use yii\helpers\Html;
use common\models\User;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;


/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="check-range-form">

    <!-- <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'from')->textInput() ?>

    <?= $form->field($model, 'to')->textInput() ?>
    <?= $form->field($model, 'begin_balance')->textInput() ?>
    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
        'name' => 'reporting_period',
        'pluginOptions' => [
            'autoclose' => true,
            'startView' => 'months',
            'minViewMode' => 'months',
            'format' => 'yyyy-mm'
        ],
        'options' => [
            'readOnly' => true,
            'style' => 'background-color:white;'
        ]
    ]) ?>



    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>
 -->
    <?php
    $from = '';
    $to = '';
    $begin_balance = '';
    $reporting_period = '';
    $model_id = '';
    $bank_account_id = '';
    $model_province = '';
    if (!empty($model)) {
        $from = $model->from;
        $to = $model->to;
        $begin_balance = $model->begin_balance;
        $reporting_period = $model->reporting_period;
        $model_id = $model->id;
        $bank_account_id = $model->bank_account_id;
        $model_province = $model->province;
    }
    ?>
    <form id="check_form">
        <input type="hidden" name='model_id' value='<?php echo $model_id ?>'>
        <div class="form-group">
            <label for="from">From</label>
            <input type="number" class="form-control" name="from" placeholder="From" value='<?php echo $from ?>' required>
        </div>
        <div class="form-group">
            <label for="to">To</label>
            <input type="number" class="form-control" name="to" placeholder="To" value='<?php echo $to ?>' required>
        </div>
        <div class="form-group">
            <label for="begin_balance">Beginning Balance</label>
            <input type="number" class="form-control" name="begin_balance" value='<?php echo $begin_balance ?>' placeholder="Beginning Balance">
        </div>
        <div class="form-group">
            <label for="bank_account"> Bank Account</label>
            <?php
            $bank_accounts_query = (new \yii\db\Query())
                ->select(['bank_account.id', "CONCAT(bank_account.account_number,'-',UPPER(bank_account.province),'-',bank_account.account_name) as account_name"])
                ->from('bank_account');

            $user_data = User::getUserDetails();
            $province = strtolower($user_data->employee->office->office_name);
            if (!Yii::$app->user->can('ro_accounting_admin')) {
                $bank_accounts_query->andWhere('bank_account.province = :province', ['province' => $province]);
            }
            $bank_accounts = $bank_accounts_query->all();
            echo Select2::widget([
                'data' => ArrayHelper::map($bank_accounts, 'id', 'account_name'),
                'name' => 'bank_account_id',
                'value' => $bank_account_id,
                'pluginOptions' => [
                    'placeholder' => 'Select Bank Account'
                ]

            ]);
            ?>
        </div>
        <div class="form-group">

            <?php
            if (YIi::$app->user->can('ro_accounting_admin')) {
                echo " <label for='province'>Province</label>";
                echo Select2::widget([
                    'data' => [
                        'adn' => 'ADN',
                        'sdn' => 'SDN',
                        'pdi' => 'PDI',
                        'sdn' => 'SDN',
                        'sds' => 'SDS',
                    ],
                    'value' => $model_province,
                    'name' => 'province',
                    'pluginOptions' => [
                        'placeholder' => 'Select Province'
                    ]

                ]);
            }
            ?>
        </div>
        <div class="form-group">
            <label for="reporting_period">Reporting Period</label>
            <?php
            echo DatePicker::widget([
                'name' => 'reporting_period',
                'value' => $reporting_period,
                'pluginOptions' => [
                    'autoclose' => true,
                    'startView' => 'months',
                    'minViewMode' => 'months',
                    'format' => 'yyyy-mm'
                ],
                'options' => [
                    'readOnly' => true,
                    'style' => 'background-color:white;'
                ]
            ]);
            ?>
        </div>
        <div class="row justify-content-center">

            <div class="form-group ">
                <button class="btn btn-success" type="submit">Save</button>
            </div>


        </div>
    </form>
    <!-- <?php ActiveForm::end(); ?> -->

</div>
<?php
SweetAlertAsset::register($this);
?>
<script>
    $('#check_form').submit((e) => {
        e.preventDefault()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=check-range/create',
            data: $('#check_form').serialize(),
            success: function(data) {
                console.log(data)
                var res = JSON.parse(data)
                if (res.success) {
                    swal({
                        title: 'success',
                        type: 'success',
                        button: false,
                        timer: 3000,
                    }, function() {
                        window.location.href = window.location.pathname + '?r=check-range/view&id=' + res.id
                    })
                } else {
                    swal({
                        title: 'Failed',
                        text: res.error,
                        type: 'error',
                        button: false,
                        timer: 3000
                    })
                }
            }
        })
    })
</script>