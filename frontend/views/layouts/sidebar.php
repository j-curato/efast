<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\User;
use app\models\Employee;
use hail812\adminlte\widgets\Menu;

$user_data = User::getUserDetails();
$employee = Employee::getEmployeeById($user_data->fk_employee_id);
$imagePath = "https://adminlte.io/themes/v3/dist/img/user4-128x128.jpg";
$filePath = '@webroot/profile_pics';
$usr_id = Yii::$app->user->identity->id;
$realFilePathPng = Yii::getAlias($filePath . "/$usr_id.png");
$realFilePathJpg = Yii::getAlias($filePath . "/$usr_id.jpg");
if (file_exists($realFilePathPng) && is_file($realFilePathPng)) {
    // File exists in the folder
    $imagePath =  Yii::$app->request->baseUrl . '/profile_pics' . "/$usr_id.png";
} else if (file_exists($realFilePathJpg) && is_file($realFilePathJpg)) {
    $imagePath =  Yii::$app->request->baseUrl . '/profile_pics' . "/$usr_id.jpg";
}
// Display the image using Html::img

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">eFAST</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <!-- <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
                <?= Html::img($imagePath, ['alt' => 'User Image', 'class' => 'img-circle elevation-2']); ?>
            </div>
            <div class="info">
                <p>
                    <?= Html::a(!empty($employee['employee_name']) ? $employee['employee_name'] : '', ['/site/profile'], ['style' => 'max-width:100%']) ?>
                </p>
            </div>
        </div>
        <nav class="mt-2">
            <?php
            function removeNull($items)
            {
                $newArr = [];
                foreach ($items as $item) {
                    if ($item !== NULL) {
                        $newArr[] = $item;
                    }
                }
                return $newArr;
            }
            $options = [
                'class' => 'nav nav-pills nav-sidebar flex-column nav-compact',
                'data-widget' => 'treeview',
                'role' => 'menu',
                'data-accordion' => 'false',
            ];
            $accountingMasterRecords =  [
                Yii::$app->user->can('payee') ? ['label' => 'Payor/Payee', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['payee/index'],] : null,
                Yii::$app->user->can('chart_of_account') ? ['label' => 'Chart of Accounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['chart-of-accounts/index'],] : null,
                Yii::$app->user->can('major_account') ? ['label' => 'Major Accounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/major-accounts/index'],] : null,
                Yii::$app->user->can('sub_major_account') ? ['label' => 'Sub Major Accounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sub-major-accounts/index'],] : null,
                Yii::$app->user->can('sub_account_1') ? ['label' => 'Sub Account 1', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sub-accounts1/index'],] : null,
                Yii::$app->user->can('sub_account_2') ? ['label' => 'Sub Account 2', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sub-accounts2/index'],] : null,
                Yii::$app->user->can('book') ? ['label' => 'Books', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/books/index'],] : null,
                Yii::$app->user->can('cash_flow') ? ['label' => 'CashFlow', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-flow/index'],] : null,
                Yii::$app->user->can('nature_of_transaction') ? ['label' => 'Nature of Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/nature-of-transaction/index'],] : null,
                Yii::$app->user->can('mrd_classification') ? ['label' => 'MRD Classification', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/mrd-classification/index'],] : null,
                Yii::$app->user->can('fund_source_type') ? ['label' => 'Fund Source Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/fund-source-type/index'],] : null,
                Yii::$app->user->can('report_type') ? ['label' => 'Report Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report-type/index'],] : null,
                Yii::$app->user->can('division') ? ['label' => 'Divisions', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/divisions/index'],] : null,
                Yii::$app->user->can('office') ? ['label' => 'Offices', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/office/index'],] : null,
                Yii::$app->user->can('division_program_unit') ? ['label' => 'Division/Program/Unit', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/division-program-unit/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Asignatory', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/assignatory/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Ors Reporting Period', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ors-reporting-period/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Jev Reporting Period', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-reporting-period/index'],] : null,
            ];
            $accountingTransactions = [
                Yii::$app->user->can('ro_transaction') ? ['label' => 'Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/transaction/index'],] : null,
                Yii::$app->user->can('ro_routing_slip') ? ['label' => 'Routing Slip', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/tracking-index'],] : null,
                Yii::$app->user->can('ro_process_dv') ? ['label' => 'Process Dv', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/index'],] : null,
                Yii::$app->user->can('ro_jev') ?  ['label' => 'Jev', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-preparation/index'],] : null,
                Yii::$app->user->can('ro_turn_arround_time') ? ['label' => 'Turn Arround Time', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/turnarround-time'],] : null,
                Yii::$app->user->can('ro_transmittal') ? ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/transmittal/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Document Tracking', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/document-tracker/index'],] : null,
                Yii::$app->user->can('ro_jev_beginning_balance') ? ['label' => 'JEV Beginning Balance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-beginning-balance/index'],] : null,
                Yii::$app->user->can('ro_remittance_payee') ? ['label' => 'Remittance Payee', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/remittance-payee/index'],] : null,
                Yii::$app->user->can('ro_payroll') ? ['label' => 'Payroll', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/payroll/index'],] : null,
                Yii::$app->user->can('ro_remitttance') ? ['label' => 'Remittance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/remittance/index'],] : null,
                Yii::$app->user->can('ro_alphalist') ? ['label' => 'Alphalist', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ro-alphalist/index'],] : null,
                Yii::$app->user->can('ro_liquidation_report') ? ['label' => 'Liquidation Report', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ro-liquidation-report/index'],] : null,

            ];
            $accountingReports = [
                // Yii::$app->user->can('super-user') ?   ['label' => 'DV w/o File Link', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/no-file-link-dvs'],] : null,
                Yii::$app->user->can('ro_general_ledger') ?   ['label' => 'General Ledger', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/general-ledger/index'],] : null,
                Yii::$app->user->can('ro_general_journal') ?   ['label' => 'General Journal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/general-journal/index'],] : null,
                Yii::$app->user->can('ro_adadj') ?   ['label' => 'ADADJ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-preparation/adadj'],] : null,
                Yii::$app->user->can('ro_ckdj') ?   ['label' => 'CKDJ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-preparation/ckdj'],] : null,
                Yii::$app->user->can('ro_trial_balance') ?   ['label' => 'Trial Balance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/trial-balance/index'],] : null,
                Yii::$app->user->can('ro_conso_trial_balance') ?   ['iconStyle' => 'far', 'label' => 'ConsoTrial Balance', 'icon' => 'dot-circle', 'url' => ['/conso-trial-balance/index'],] : null,
                Yii::$app->user->can('ro_sub_trial_balance') ?   ['iconStyle' => 'far', 'label' => 'Sub Trial Balance', 'icon' => 'dot-circle', 'url' => ['/sub-trial-balance/index'],] : null,
                Yii::$app->user->can('ro_conso_sub_trial_balance') ?   ['iconStyle' => 'far', 'label' => 'Conso Sub Trial Balance', 'icon' => 'dot-circle', 'url' => ['/conso-sub-trial-balance/index'],] : null,
                Yii::$app->user->can('ro_subsidiary_ledger') ?   ['iconStyle' => 'far', 'label' => 'Subsidiary Ledger', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/get-subsidiary-ledger'],] : null,


                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Detailed Financial Position', 'icon' => 'dot-circle', 'url' => ['/report/detailed-financial-position'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Conso Financial Position', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/consolidated-financial-position'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Detailed F Performance', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/detailed-financial-performance'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Conso F Performance', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/consolidated-financial-performance'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Detailed Cashflow', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/detailed-cashflow'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Conso Cashflow', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/consolidated-cashflow'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Net Asset Changes', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/changes-netasset-equity'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Transaction Archive', 'icon' => 'dot-circle', 'url' => ['/report/transaction-archive'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Remittance Summary', 'icon' => 'dot-circle', 'url' => ['/report/withholding-and-remittance-summary'],] : null,
                Yii::$app->user->can('super-user') ?   ['iconStyle' => 'far', 'label' => 'Annex 3 CA to Employees', 'icon' => 'dot-circle', 'url' => ['/report/liquidation-report-annex'],] : null,
            ];
            $budgetMasterRecords = [
                Yii::$app->user->can('ro_responsibility_center') ? ['label' => 'Responsibility Center', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/responsibility-center/index'],] : null,
                Yii::$app->user->can('document_receive') ? ['label' => 'Document Receive', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/document-recieve/index'],] : null,
                Yii::$app->user->can('fund_cluster_code') ? ['label' => 'Fund Cluster Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/fund-cluster-code/index'],] : null,
                Yii::$app->user->can('financing_source_code') ? ['label' => 'Financing Source Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/financing-source-code/index'],] : null,
                Yii::$app->user->can('authorization_code') ? ['label' => 'Authorization Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/authorization-code/index'],] : null,
                Yii::$app->user->can('fund_classification_code') ? ['label' => 'Fund Classification Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/fund-category-and-classification-code/index']] : null,
                Yii::$app->user->can('mfo_pap_code') ? ['label' => 'MFO/PAP Codes', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/mfo-pap-code/index'],] : null,
                Yii::$app->user->can('fund_source') ? ['label' => 'Fund Source', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/fund-source/index'],] : null,
                Yii::$app->user->can('allotment_type') ? ['label' => 'Allotment Type', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/allotment-type/index'],] : null,
            ];
            $budgetTransactions = [
                Yii::$app->user->can('record_allotment') ? ['iconStyle' => 'far', 'label' => 'Record Allotments', 'icon' => 'dot-circle', 'url' => ['/record-allotments/index'],] : null,
                Yii::$app->user->can('process_ors') ? ['iconStyle' => 'far', 'label' => 'Process Ors', 'icon' => 'dot-circle', 'url' => ['/process-ors/index'],] : null,
                Yii::$app->user->can('process_ors') ? ['iconStyle' => 'far', 'label' => 'Process BURS', 'icon' => 'dot-circle', 'url' => ['/process-ors/burs-index'],] : null,
                Yii::$app->user->can('maf') ? ['iconStyle' => 'far', 'label' => 'MAF', 'icon' => 'dot-circle', 'url' => ['/maf/index'],] : null,
            ];

            $budgetReports = [
                Yii::$app->user->can('saob') ?     ['iconStyle' => 'far', 'label' => 'SAOB', 'icon' => 'dot-circle', 'url' => ['/saob/index'],] : null,
                Yii::$app->user->can('ro_fur') ?     ['iconStyle' => 'far', 'label' => 'FUR', 'icon' => 'dot-circle', 'url' => ['/ro-fur/index'],] : null,
                Yii::$app->user->can('rao') ?     ['iconStyle' => 'far', 'label' => 'RAO', 'icon' => 'dot-circle', 'url' => ['/budget-reports/export-rao'],] : null,

            ];

            $budgetStatusOfFunds = [
                Yii::$app->user->can('sof_per_mfo') ?     ['iconStyle' => 'far', 'label' => 'per MFO/PAP', 'icon' => 'dot-circle', 'url' => ['/budget-reports/sof-per-mfo'],] : null,
                Yii::$app->user->can('sof_per_office') ?     ['iconStyle' => 'far', 'label' => 'per Office/Division', 'icon' => 'dot-circle', 'url' => ['/budget-reports/sof-per-office'],] : null,
                Yii::$app->user->can('sof_per_mfo_office') ?     ['iconStyle' => 'far', 'label' => 'per MFO & Office', 'icon' => 'dot-circle', 'url' => ['/budget-reports/sof-per-mfo-office'],] : null,
            ];

            $cashMasterRecords = [
                Yii::$app->user->can('ro_check_range') ?     ['label' => 'Check Ranges', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ro-check-ranges/index'],] : null,
                Yii::$app->user->can('ro_mode_of_payment') ?     ['label' => 'Mode of Payments', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/mode-of-payments/index'],] : null,
                Yii::$app->user->can('banks') ?     ['label' => 'Banks', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/banks/index'],] : null,
            ];

            $cashTransactions = [
                Yii::$app->user->can('cash_disbursement') ? ['label' => 'Cash Disbursement', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-disbursement/index'],] : null,
                Yii::$app->user->can('sliie') ? ['label' => 'SLIIE`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sliies/index'],] : null,
                Yii::$app->user->can('lddap_ada') ? ['label' => 'LDDAP-ADA`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/lddap-adas/index'],] : null,
                Yii::$app->user->can('acic') ? ['label' => 'ACIC`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/acics/index'],] : null,
                Yii::$app->user->can('acic_in_bank') ? ['label' => 'ACIC in Bank', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/acic-in-bank/index'],] : null,
                Yii::$app->user->can('rci') ? ['label' => 'RCI', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rci/index'],] : null,
                Yii::$app->user->can('radai') ? ['label' => 'RADAI', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/radai/index'],] : null,
                Yii::$app->user->can('cash_receive') ? ['label' => 'Cash Received', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-received/index'],] : null,
                Yii::$app->user->can('laps_amounts') ? ['label' => 'Laps Amounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-adjustment/index'],] : null,
                Yii::$app->user->can('cash_disbursement') ? ['label' => 'Cancel Disbursement', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-disbursement/cancel-disbursement-index'],] : null,
            ];
            $cashReports = [
                Yii::$app->user->can('cadadr') ?     ['label' => 'CADADR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-reports/cadadr'],] : null,
                Yii::$app->user->can('cadadr_per_dv') ?     ['label' => 'CADADR per DV', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-report/dv-cadadr'],] : null,
            ];

            $propertyMasterRecords  = [
                Yii::$app->user->can('ssf_sp_num') ?     ['label' => 'SSF SP No.', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ssf-sp-num/index'],] : null,
                Yii::$app->user->can('ssf_sp_status') ?     ['label' => 'SSF SP Status', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ssf-sp-status/index'],] : null,
                Yii::$app->user->can('locations') ?     ['label' => 'Locations', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/location/index'],] : null,
                Yii::$app->user->can('unit_of_measure') ?     ['label' => 'Unit of Measure', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/unit-of-measure/index'],] : null,
                Yii::$app->user->can('agency') ?     ['label' => 'Agency', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/agency/index'],] : null,
                Yii::$app->user->can('transfer_type') ?     ['label' => 'Transfer Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/transfer-type/index'],] : null,
                Yii::$app->user->can('property_articles') ?     ['label' => 'Property Articles', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-articles/index'],] : null,
                Yii::$app->user->can('citymun') ? ['label' => 'City/Municipality', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/citymun/index'],] : null,
            ];

            $propertyTransactions = [
                Yii::$app->user->can('property') ?     ['label' => 'Property', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/index'],] : null,
                Yii::$app->user->can('ptr') ?     ['label' => 'PTR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ptr/index'],] : null,
                Yii::$app->user->can('par') ?     ['label' => 'PAR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/par/index'],] : null,
                Yii::$app->user->can('property_card') ?     ['label' => 'Property Card', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-card/index'],] : null,
                Yii::$app->user->can('other_property_details') ?     ['label' => 'Other Property Details', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/other-property-details/index'],] : null,
                Yii::$app->user->can('depreciation_schedule') ?     ['label' => 'Depreciation Schedule', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/depreciation-schedule/index'],] : null,
                Yii::$app->user->can('rlsddp') ?     ['label' => 'RLSDDP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rlsddp/index'],] : null,
                Yii::$app->user->can('iirup') ?     ['label' => 'IIRUP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iirup/index'],] : null,
                Yii::$app->user->can('derecognition') ?     ['label' => 'Derecognition', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/derecognition/index'],] : null,
            ];

            $propertReports = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Print PC Stickers', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-card/print-pc'],] : null,
                Yii::$app->user->can('property_database') ?  ['label' => 'Property Database', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/property-database'],] : null,
                Yii::$app->user->can('rpcppe') ?     ['label' => 'RPCPPE', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rpcppe/index'],] : null,
                Yii::$app->user->can('ppelc') ?     ['label' => 'PPELC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-reports/ppelc'],] : null,
                Yii::$app->user->can('property_accountabilities') ?     ['label' => 'Accountabilities', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-reports/user-properties'],] : null,
            ];
            $procurementMasterRecords = [
                Yii::$app->user->can('contract_type') ?     ['label' => 'Contract Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-contract-type/index'],] : null,
                Yii::$app->user->can('mode_of_procurement') ?     ['label' => 'Mode of Procurement', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-mode-of-procurement/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Office/Section', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-office/index'],] : null,
                Yii::$app->user->can('stock') ?     ['label' => 'Stock/Property', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-stock/index'],] : null,
                Yii::$app->user->can('bac') ?     ['label' => 'BAC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bac-composition/index'],] : null,
                Yii::$app->user->can('bac_position') ?     ['label' => 'BAC position', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bac-position/index'],] : null,

            ];

            $procurementTransactions = [

                Yii::$app->user->can('ppmp') ?     ['label' => 'Supplemental PPMP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/supplemental-ppmp/index'],] : null,
                Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-request/index'],] : null,
                Yii::$app->user->can('rfq') ?     ['label' => 'RFQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-rfq/index'],] : null,
                Yii::$app->user->can('aoq') ?     ['label' => 'AOQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-aoq/index'],] : null,
                Yii::$app->user->can('purchase_order') ?     ['label' => 'Purchase Order', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-order/index'],] : null,
                Yii::$app->user->can('purchase_order_transmittal') ?     ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/purchase-order-transmittal/index'],] : null,
            ];

            $procurementReports = [

                Yii::$app->user->can('pr_to_iar_tracking') ?     ['label' => 'PR to IAR Tracking', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/procurement-reports/procurement-to-inspection-tracking'],] : null,
                // Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/procurement-summary'],] : null,
                // Yii::$app->user->can('super-user') ?     ['label' => 'PO Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/pr-summary'],] : null,
                // Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Search', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/proc-summary'],] : null,
            ];

            $generalServiceMasterRecords = [
                Yii::$app->user->can('car') ?     ['label' => 'Cars', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cars/index'],] : null,
            ];
            $generalServiceTransactions = [

                Yii::$app->user->can('maintenance_job_request') ?     ['label' => 'Job Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/maintenance-job-request/index'],] : null,
                Yii::$app->user->can('pre_repair_inspection') ?     ['label' => 'Pre-Repair Inspection', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pre-repair-inspection/index'],] : null,
                Yii::$app->user->can('trip_ticket') ?     ['label' => 'Trip Ticket', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/trip-ticket/index'],] : null,

            ];

            $generalServiceReports = [];

            $inspectionMasterRecords = [];
            $inspectionTransactions = [
                Yii::$app->user->can('request_for_inspection') ?     ['label' => 'Request for Inspection', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/request-for-inspection/index'],] : null,
                Yii::$app->user->can('inspection_report') ?     ['label' => 'Inspection Report', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/inspection-report/index'],] : null,
                Yii::$app->user->can('iar') ?     ['label' => 'IAR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iar/index'],] : null,
                Yii::$app->user->can('iar_transmittal') ?     ['label' => 'IAR Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iar-transmittal/index'],] : null,
            ];
            $inspectionReports = [];

            $provinceMasterRecords = [
                Yii::$app->user->can('advances_report_type') ?  ['label' => 'Advances Report Types', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/advances-report-types/index'],] : null,
                Yii::$app->user->can('bank_account') ?  ['label' => 'Bank Account', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bank-account/index'],] : null,
                Yii::$app->user->can('po_check_range') ?  ['label' => 'Check Range', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/check-range/index'],] : null,
                Yii::$app->user->can('po_asignatory') ?     ['label' => 'PO Asignatory', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-assignatory/index'],] : null,
                Yii::$app->user->can('po_responsibility_center') ?     ['label' => 'PO Responsibility Center', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-responsibility-center/index'],] : null,
            ];
            // SKIPED

            $provinceTransactions = [
                Yii::$app->user->can('advances') ?     ['label' => 'Advances', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/advances/index'],] : null,
                Yii::$app->user->can('po_transaction') ?     ['label' => 'PO Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transaction/index'],] : null,
                Yii::$app->user->can('liquidation') ?     ['label' => 'Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ["/liquidation/index"],] : null,
                Yii::$app->user->can('liquidation') ?     ['label' => 'Cancelled Check', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/liquidation/cancelled-check-index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Pending Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/pending-at-ro'],] : null,
                Yii::$app->user->can('po_transmittal') ? ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/index'],] : null,
                Yii::$app->user->can('po_transmittal') ? ['label' => 'Returned DV`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/returned-liquidation'],] : null,
                Yii::$app->user->can('po_transmittal_to_coa') ?     ['label' => 'PO Transmittal to COA', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal-to-coa/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Positions', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/employee-position/index'],] : null,
                Yii::$app->user->can('po_alphalist') ?     ['label' => 'Alphalist', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/alphalist/index'],] : null,
            ];
            $provinceReports = [
                Yii::$app->user->can('po_cibr') ?     ['label' => 'CIBR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cibr'],] :  null,
                Yii::$app->user->can('po_cdr') ?     ['label' => 'CDR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cdr/index'],] : null,
                Yii::$app->user->can('po_fur') ?     ['label' => 'FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/fur/index'],] : null,
                Yii::$app->user->can('po_rod') ?     ['label' => 'ROD', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rod/index'],] : null,
                Yii::$app->user->can('po_fund_source_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/fund-source-fur'],] : null,
                Yii::$app->user->can('po_summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/summary-fund-source-fur'],] : null,
                Yii::$app->user->can('po_budget_year_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/budget-year-fur'],] : null,
                Yii::$app->user->can('po_mlp') ?     ['label' => 'MLP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/monthly-liquidation-program/index'],] : null,
                Yii::$app->user->can('po_adequacy_of_resource') ?     ['label' => 'Adequacy of Resource', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/province-adequacy'],] : null,
                Yii::$app->user->can('advances_liquidation') ?     ['label' => 'Advances/Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/advances-liquidation'],] : null,
                Yii::$app->user->can('po_transmittal_summary') ?     ['label' => 'PO Transmittal Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/po-transmittal-summary'],] : null,
            ];
            $itMasterRecords = [];
            $itTransactions = [
                Yii::$app->user->can('super-user') ?     ['label' => 'IT Maintenance Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/it-maintenance-request/index'],] : null,
            ];
            $itReports = [];
            $reports = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Pending ORS', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/pending-ors'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => "Pending DV's", 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/pending-dv'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'UnObligated Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/unobligated-transaction'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'UnPaid Obligation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/unpaid-obligation'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Detailed Dv', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/detailed-dv-aucs'],] : null,
                Yii::$app->user->can('super-user') ?    ['label' => 'Conso Dv', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/conso-detailed-dv'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Tax Remittance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/tax-remittance'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Annex 3', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/annex3'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Annex A', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/annex-a'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'RAAF', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/raaf'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'CDJ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/cdj'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Transaction Tracking', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/transaction-tracking'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'DV Time Monitoring', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/dv-time-monitoring'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'DV Time Monitoring Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/dv-time-monitoring-summary'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Holidays', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/holidays'],] : null,
            ];
            $hrMasterRecords = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Employees', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/employee/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'permission', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/permission/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'role', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/role/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'user', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/user/index'],] : null,
            ];
            $menuItems =  [
                [
                    'label' => 'Accounting',
                    'icon' => 'calculator',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($accountingMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far  text-info',
                            'items' => removeNull($accountingTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far  text-info',
                            'items' =>  removeNull($accountingReports)
                        ],
                    ]
                ],
                [
                    'label' => 'Budget',
                    'icon' => 'fa-solid fa fa-piggy-bank',

                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far  text-info',
                            'items' => removeNull($budgetMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far  text-info',
                            'items' => removeNull($budgetTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far  text-info',
                            'items' => removeNull($budgetReports)
                        ],
                        [
                            'label' => 'Status of Funds',
                            'iconStyle' => 'far  text-info',
                            'items' => removeNull($budgetStatusOfFunds)
                        ],
                    ],


                ],
                [
                    'label' => 'Cash',
                    'icon' => 'fa fa-coins',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($cashMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($cashTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($cashReports)
                        ],

                    ],


                ],
                [
                    'label' => 'Reports',
                    'icon' => 'fa fa-chart-bar',
                    'items' => removeNull($reports)

                ],
                [
                    'label' => 'Property',
                    'icon' => 'fa fa-building',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($propertyMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($propertyTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($propertReports)
                        ],
                    ],


                ],
                [
                    'label' => 'Procurement',
                    'icon' => 'fa fa-shopping-cart',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($procurementMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($procurementTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($procurementReports)
                        ],
                    ],


                ],
                [
                    'label' => 'General Services',
                    'icon' => 'fa fa-broom',

                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($generalServiceMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($generalServiceTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($generalServiceReports)
                        ],
                    ],


                ],
                [
                    'label' => 'Inspection',
                    'icon' => 'fa fa-search',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($inspectionMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($inspectionTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($inspectionReports)
                        ],
                    ],


                ],
                [
                    'label' => 'Province',
                    'icon' => 'fa fa-map',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($provinceMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($provinceTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($provinceReports)
                        ],
                    ],


                ],

                [
                    'label' => 'IT Helpdesk',
                    'icon' => 'fa fa-wifi',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($itMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($itTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($itReports)
                        ],
                    ],


                ],
                [
                    'label' => 'Human Resource',
                    'icon' => ' fa-id-card',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull($hrMasterRecords)
                        ],

                    ],


                ],


                [
                    'label' => 'Profile',
                    'icon' => 'fa fa-user',
                    'url' => ['/site/profile']


                ],

            ];
            $poUserMenuItems = [

                [
                    'label' => 'Master Records',
                    'icon' => 'fa fa-book ',
                    'items' => removeNull([
                        Yii::$app->user->can('po_check_range') ?  ['label' => 'Check Range', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/check-range/index'],] : null,
                        Yii::$app->user->can('po_asignatory') ?     ['label' => 'PO Asignatory', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-assignatory/index'],] : null,
                        Yii::$app->user->can('po_responsibility_center') ?     ['label' => 'PO Responsibility Center', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-responsibility-center/index'],] : null,
                        Yii::$app->user->can('payee') ? ['label' => 'Payee', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/payee/index'],] : null,
                    ]),
                ],
                [
                    'label' => 'Accounting',
                    'icon' => 'calculator',
                    'items' => [
                        [
                            'label' => 'Transaction',
                            'iconStyle' => 'far text-info',
                            'url' => '#',
                            'items' => removeNull([
                                Yii::$app->user->can('advances') ?     ['label' => 'Advances', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/advances/index'],] : null,
                                Yii::$app->user->can('po_transaction') ?     ['label' => 'PO Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transaction/index'],] : null,
                                Yii::$app->user->can('liquidation') ?     ['label' => 'Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/liquidation/index'],] : null,
                                Yii::$app->user->can('liquidation') ?     ['label' => 'Cancelled Check', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/liquidation/cancelled-check-index'],] : null,
                                Yii::$app->user->can('po_transmittal') ? ['label' => 'DV Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/index'],] : null,

                            ]),
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([
                                Yii::$app->user->can('po_alphalist') ?     ['label' => 'Alphalist', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/alphalist/index'],] : null,
                                Yii::$app->user->can('po_cibr') ?     ['label' => 'CIBR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cibr/index'],] : null,
                                Yii::$app->user->can('po_cdr') ?     ['label' => 'CDR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cdr/index'],] : null,
                                Yii::$app->user->can('po_fur') ?     ['label' => 'FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/fur/index'],] : null,
                                Yii::$app->user->can('po_rod') ?     ['label' => 'ROD', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rod/index'],] : null,
                                // Yii::$app->user->can('po_transmittal') ? ['label' => 'Returned DV`s', 'icon' => 'dot-circle','iconStyle'=>'far', 'url' => ['/po-transmittal/returned-liquidation'],] : null,
                                Yii::$app->user->can('advances_liquidation') ?     ['label' => 'Advances/Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/advances-liquidation'],] : null,

                            ]),
                        ],
                        [
                            'label' => 'Querys',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([
                                Yii::$app->user->can('po_fund_source_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/fund-source-fur'],] : null,
                                Yii::$app->user->can('po_summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/summary-fund-source-fur'],] : null,
                                Yii::$app->user->can('po_budget_year_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/budget-year-fur'],] : null,
                                Yii::$app->user->can('po_adequacy_of_resource') ?     ['label' => 'Adequacy of Resource', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/province-adequacy'],] : null,
                                Yii::$app->user->can('po_mlp') ?     ['label' => 'MLP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/monthly-liquidation-program/index'],] : null,

                            ]),
                        ]

                    ],
                ],
                [
                    'label' => 'Property',
                    'icon' => 'fa fa-building',
                    'items' => [

                        [
                            'label' => 'Transaction',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([
                                Yii::$app->user->can('property') ?     ['label' => 'Property', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/index'],] : null,
                                Yii::$app->user->can('ptr') ?     ['label' => 'PTR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ptr/index'],] : null,
                                Yii::$app->user->can('par') ?     ['label' => 'PAR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/par/index'],] : null,
                                Yii::$app->user->can('property_card') ?     ['label' => 'Property Card', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-card/index'],] : null,
                                Yii::$app->user->can('rlsddp') ?     ['label' => 'RLSDDP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rlsddp/index'],] : null,
                                Yii::$app->user->can('iirup') ?     ['label' => 'IIRUP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iirup/index'],] : null,
                            ]),
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([
                                Yii::$app->user->can('property') ?  ['label' => 'Property Database', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/property-database'],] : null,
                                Yii::$app->user->can('rpcppe') ?     ['label' => 'RPCPPE', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rpcppe/index'],] : null,
                                Yii::$app->user->can('ppelc') ?     ['label' => 'PPELC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/ppelc'],] : null,
                                Yii::$app->user->can('property_accountabilities') ?     ['label' => 'Accountabilities', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/user-properties'],] : null,
                            ]),
                        ],
                    ],
                ],
                // [
                //     'label' => 'Query',
                //     'icon' => 'fa fa-database',
                //     'items' => removeNull([
                //         Yii::$app->user->can('po_fund_source_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/fund-source-fur'],] : null,
                //         Yii::$app->user->can('po_summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/summary-fund-source-fur'],] : null,
                //         Yii::$app->user->can('po_summary_fund_source_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/budget-year-fur'],] : null,
                //         Yii::$app->user->can('po_rod') ?     ['label' => 'ROD', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rod/index'],] : null,
                //         Yii::$app->user->can('po_adequacy_of_resource') ?     ['label' => 'Adequacy of Resource', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/province-adequacy'],] : null,

                //     ]),
                // ],
                [
                    'label' => 'Procurement',
                    'icon' => 'fa fa-shopping-cart',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([
                                Yii::$app->user->can('bac') ?     ['label' => 'RBAC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bac-composition/index'],] : null,

                            ]),
                        ],
                        [
                            'label' => 'Transaction',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([
                                Yii::$app->user->can('ppmp') ?     ['label' => 'Supplemental PPMP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/supplemental-ppmp/index'],] : null,
                                Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-request/index'],] : null,
                                Yii::$app->user->can('rfq') ?     ['label' => 'RFQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-rfq/index'],] : null,
                                Yii::$app->user->can('aoq') ?     ['label' => 'AOQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-aoq/index'],] : null,
                                Yii::$app->user->can('purchase_order') ?     ['label' => 'Purchase Order', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-order/index'],] : null,
                            ]),
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far text-info',
                            'items' => removeNull([]),
                        ],


                    ],
                ],
                [
                    'label' => 'Inspection',
                    'icon' => 'fa fa-search',
                    'items' => removeNull([
                        Yii::$app->user->can('request_for_inspection') ?     ['label' => 'Request for Inspection', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/request-for-inspection/index'],] : null,
                        Yii::$app->user->can('inspection_report') ?     ['label' => 'Inspection Report', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/inspection-report/index'],] : null,
                        Yii::$app->user->can('iar') ?     ['label' => 'IAR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iar/index'],] : null,
                    ]),
                ],
                [
                    'label' => 'Profile',
                    'icon' => 'fa fa-user',
                    'url' => ['/site/profile']
                ],


            ];

            echo Menu::widget([
                'items' => strtolower($user_data->employee->office->office_name) !== 'ro' ? $poUserMenuItems : $menuItems,
                'options' => [
                    'class' => 'nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent',
                    'data-widget' => 'treeview',
                    'role' => 'menu',
                    'data-accordion' => 'false'
                ],
            ]);

            Yii::$app->authManager
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>