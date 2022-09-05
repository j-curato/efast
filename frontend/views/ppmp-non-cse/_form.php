<?php

use aryelds\sweetalert\SweetAlertAsset;

use kartik\select2\Select2Asset;
use kartik\date\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PpmpNonCse */
/* @var $form yii\widgets\ActiveForm */

$ppmp_item_counter = 1;

?>

<div class="ppmp-non-cse-form bg-white">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => false,
    ]); ?>



    <ul id="ppmp_items">
        <li class="panel panel-default">
            <div class="row">
                <div class="col-sm-3">
                    <?= $form->field($model, 'date')->widget(DatePicker::class, [
                        'options' => ['placeholder' => 'Enter Date ...'],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true
                        ]
                    ]) ?>
                </div>
            </div>
        </li>

        <?php
        if (!empty($items)) {
            $fund_source = Yii::$app->db->createCommand('SELECT * FROM fund_source ')->queryAll();
            $mfo = Yii::$app->db->createCommand('SELECT * FROM mfo_pap_code ')->queryAll();

            //     $fund_source_select = "<select name='fund_source[0]' class='fund_source form-control'>
            //     <option>Select Fund Source</option>
            //     ".
            //     foreach ($fund_source as $val) {
            //         echo '<option value='{$val['id']}'>{$val['name']}</option>';
            //     }."
            // </select>";
            foreach ($items as $i => $val) {
                $min_key =  min(array_keys($val));
                $project_name = $val[$min_key]['project_name'];
                $description = $val[$min_key]['description'];
                $target_month = $val[$min_key]['target_month'];
                $responsibility_center_id = $val[$min_key]['responsibility_center_id'];
                $responsibility_center_name = $val[$min_key]['responsibility_center_name'];
                $mfo_id = $val[$min_key]['fk_pap_code_id'];
                $mfo_pap_name = $val[$min_key]['mfo_pap_name'];
                $category_id = $val[$min_key]['category_id'];
                $item_id = $val[$min_key]['item_id'];

                echo "<li class='panel panel-default'>
                <input type='hidden' class='form-control update_item_id' name='itemId[$ppmp_item_counter]'  value='$item_id' required>
                <div class='row'>
                    <div class='col-sm-3'>
                        <h5>Add/Remove Project</h5>
                        <button type='button' class='add btn-xs btn-success '><i class='glyphicon glyphicon-plus'></i></button>
                        <button type='button' class='remove_item btn-xs btn-danger '><i class='fa fa-times fa-fw'></i></button>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-sm-12 form-group'>
                        <label for='project_name'> Project Name</label>
                        <input type='text' class='form-control project_name' name='project_name[$ppmp_item_counter]'  value='$project_name' required>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-sm-12 form-group'>
                        <label for='description'> Description</label>
                        <textarea class='form-control description' name='description[$ppmp_item_counter]' cols='30' rows='1' style='max-width: 100%;' required>$description</textarea>
                    <div>
                </div>
                <div class='row'>
            
             
                    <div class='col-sm-12 form-group'>
                        <label for='target_month'> Target Month</label>
                        <input type='input' class='form-control target_month' name='target_month[$ppmp_item_counter]'  value='$target_month' required>
                    </div>
                </div>
            <div class='row'>
          
                <div class='col-sm-4' class='form-group'>
                    <label for='pap_code'> PAP Code</label>
                    <select name='pap_code[$ppmp_item_counter]' class='pap_code mfo_pap_code form-control'>
                        <option value='$mfo_id'>$mfo_pap_name</option>
                        </select>
                </div>
                <div class='col-sm-4' class='form-group'>
                    <label for='end_user'> End User</label>
                    <select name='end_user[$ppmp_item_counter]' class='employee_select end_user form-control'>
                        <option value='$responsibility_center_id'>$responsibility_center_name</option>
                    </select>
                </div>

            </div>
                <table class=''>
                    <thead>
                        <th colspan='3'>Add Category <button type='button' class='btn-xs btn-primary add_category'  row='$ppmp_item_counter'><i class='glyphicon glyphicon-plus'></i></button></th>
                    </thead>
                    <tbody class='body'>
                    ";

                foreach ($val as $category_counter => $category) {
                    $amount = $category['budget'];
                    $stock_id = $category['stock_id'];
                    $stock_type = $category['type'];
                    $category_id = $category['category_id'];
                    echo "<tr>
                        <td class='center'>
                        <input type='hidden' class='form-control categoryId' name='categoryId[$ppmp_item_counter][$category_counter]' value='$category_id'>
                            <div class='form-group'>
                                <label for='stock-type '>Stock Type</label>
                                 <br>
                                <select name='stock_type[$ppmp_item_counter][$category_counter]' class='stock-type form-control'>
                                    <option value='$stock_id' >$stock_type</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class='form-group'>
                                <label for='categoriesAmount'>Amount</label>
                                <input type='text' class='mask-amount form-control' value='$amount'>
                                <input type='hidden' class='form-control categoriesAmount main-amount' name='categoriesAmount[$ppmp_item_counter][$category_counter]' value='$amount'>
                            </div>
                        </td>
                        <td>
                            <div class='pull-right' style='float:left'>
                                <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' ></i> </a>
                            </div>
                        </td>
                    </tr>";
                }
                echo "

                    </tbody>

                </table>
<hr>
        </li>";
                $ppmp_item_counter++;
            }
        } else {

        ?>
            <li class="panel panel-default">
                <div class="row">
                    <div class="col-sm-3 ">
                        <h5>Add/Remove Project</h5>
                        <button type="button" class='add btn-xs btn-success '><i class="glyphicon glyphicon-plus"></i></button>
                        <button type="button" class='remove_item btn-xs btn-danger '><i class='fa fa-times fa-fw'></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label for="project_name"> Project Name</label>
                        <input type="text" class="form-control project_name" name="project_name[0]" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label for="description"> Description</label>
                        <textarea class="form-control description" name="description[0]" cols="30" rows="1" style="max-width: 100%;" required></textarea>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label for="target_month"> Target Month</label>
                        <input type="text" class="form-control target_month" name="target_month[0]" required>
                    </div>
                </div>
                <div class="row">


                    <div class="col-sm-4" class="form-group">
                        <label for="pap_code"> PAP Code</label>
                        <select name="pap_code[0]" class="pap_code  mfo_pap_code form-control">
                            <option>Select PAP Code</option>

                        </select>
                    </div>
                    <div class="col-sm-4" class="form-group">
                        <label for="end_user"> End User</label>
                        <select name="end_user[0]" class="end_user form-control">
                            <option>Select End User</option>
                        </select>
                    </div>

                </div>
                <table class="">
                    <thead>
                        <th colspan="3">Add Category <button type='button' class='btn-xs btn-primary add_category' row='0'><i class="glyphicon glyphicon-plus"></i></button></th>
                    </thead>
                    <tbody class="body">

                    </tbody>

                </table>
                <hr>
            </li>
        <?php } ?>
    </ul>

    <div class="row panel panel-default" style="margin: 12px 0 12px 4rem;   padding: 3rem;">
        <div class=" col-sm-2 col-sm-offset-5">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>
</div>
<style>
    #ppmp_items {
        list-style-type: none;
    }

    #ppmp_items li {
        padding: 3rem;
        margin: 12px 0 12px 0;
    }

    /* #ppmp_items li {
        background: green;
    }

    #ppmp_items li:nth-child(odd) {
        background: red;
    } */

    table {
        width: 60%;
    }

    td {
        text-align: center;
    }

    .stock-type {
        max-width: 400px;
        min-width: 400px;
    }
    .add_category{
        margin: 12px 0 5px 0
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
Select2Asset::register($this);

?>
<script>
    let responsibility_centers = []
    let pap_codes = []
    let fund_sources = []
    async function r_centers() {
        const res = await getAllResponsibilityCenter()
        responsibility_centers = res.responsibility_center
        endUserSelect()


    }
    async function papCodes() {
        const res = await getAllMfo()
        pap_codes = res.mfo
        console.log(pap_codes)
        mfoPapSelect()


    }


    function mfoPapSelect() {
        $('.mfo_pap_code').select2({
            data: pap_codes,
            placeholder: 'Select MFO/PAP Code'
        })
    }

    function endUserSelect() {
        $('.end_user').select2({
            data: responsibility_centers,
            placeholder: 'Select Responsibility Center'
        })
    }


    $(document).ready(function() {

        r_centers()
        papCodes()
        stockTypeSelect()
        let ppmp_item_counter = <?php echo $ppmp_item_counter ?>;

        maskAmount()
        $('#ppmp_items').on('click', '.add', function(event) {
            $('.end_user').select2('destroy')
            $('.mfo_pap_code').select2('destroy')
            const source = $(this).closest('li');
            const clone = source.clone(true);
            clone.find('.project_name').attr('name', 'project_name[' + ppmp_item_counter + ']')
            clone.find('.description').attr('name', 'description[' + ppmp_item_counter + ']')
            clone.find('.target_month').attr('name', 'target_month[' + ppmp_item_counter + ']')
            clone.find('.fund_source').attr('name', 'fund_source[' + ppmp_item_counter + ']')
            clone.find('.pap_code').attr('name', 'pap_code[' + ppmp_item_counter + ']')
            clone.find('.end_user').attr('name', 'end_user[' + ppmp_item_counter + ']')
            clone.find('.add_category').attr('row', ppmp_item_counter)
            clone.find('.update_item_id').remove()
            clone.find('table tbody').html('')
            $('#ppmp_items').append(clone)
            endUserSelect()
            mfoPapSelect()
            ppmp_item_counter++
        });
        $('.add_category').on('click', function() {
            const closest_tr = $(this).closest('tr')
            const row_number = $(this).attr('row')
            const row = `<tr>
                            <td>
                        
                                <div class='form-group'>
                                <label for='stock-type '>Stock Type</label>
                                 <br>
                                <select name='stock_type[${row_number}][]' class='stock-type form-control'>
                                    <option >Select Stock Type</option>
                                </select>
                            </div>
                            </td>
                            <td>
                            <div class='form-group'>
                                <label for='categoriesAmount'>Amount</label>
                                <input type='text' class='mask-amount form-control'>
                                <input type='hidden' class='form-control categoriesAmount main-amount' name='categoriesAmount[${row_number}][]'>
                            </div>
                                </td>
                                <td>
                                    <div class='pull-right' style='float:left'>
                                    <a class='remove_this_row btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw' ></i> </a>
                                    </div>
                                </td>
                           </tr>`

            closest_tr.closest('table').find('.body').append(row);
            stockTypeSelect()

            maskAmount()

        })

        $('#ppmp_items').on('click', '.remove_this_row', function() {
            $(this).closest('tr').remove()

        })
        $('.remove_item').on('click', function() {
            $(this).closest('li').remove()

        })


        $('#ppmp_items').on('change keyup', '.mask-amount', function() {
            $(this).closest('tr').find('.main-amount').val($(this).maskMoney('unmasked')[0])
        })

    })
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

    $('#PpmpNonCse').on('beforeSubmit',function(e){
        e.preventDefault()
        var \$form = $(this);
        if (\$form.find('.has-error').length) 
            {
                return false;
            }
        $.ajax({
            type:'POST',
            url:\$form.attr("action"),
            data: \$form.serialize(),
            success:function(data){
                const res  =JSON.parse(data)
                 if (!res.isSuccess){
                    swal( {
                        icon: 'error',
                        title: res.error_message,
                        type: "error",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                }
            },

        })
        return false;
    })       

JS;
$this->registerJs($script);
?>