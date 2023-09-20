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
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <nav class="mt-2">
            <?php
            $user_data = Yii::$app->memem->getUserData();
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
                Yii::$app->user->can('super-user') ? ['label' => 'Payor/Payee', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['payee/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Chart of Accounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['chart-of-accounts/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Major Accounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/major-accounts/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Sub Major Accounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sub-major-accounts/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Sub Account 1', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sub-accounts1/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Sub Account 2', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sub-accounts2/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Books', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/books/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'CashFlow', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-flow/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Nature of Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/nature-of-transaction/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'MRD Classification', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/mrd-classification/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Asignatory', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/assignatory/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Ors Reporting Period', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ors-reporting-period/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Jev Reporting Period', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-reporting-period/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Fund Source Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/fund-source-type/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Report Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report-type/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Divisions', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/divisions/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Offices', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/office/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Division/Program/Unit', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/division-program-unit/index'],] : null,
            ];
            $accountingTransactions = [
                Yii::$app->user->can('ro_transaction') ? ['label' => 'Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/transaction/index'],] : null,
                Yii::$app->user->can('ro_routing_slip') ? ['label' => 'Routing Slip', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/tracking-index'],] : null,
                Yii::$app->user->can('ro_process_dv') ? ['label' => 'Process Dv', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/index'],] : null,
                Yii::$app->user->can('ro_jev') ?  ['label' => 'Jev', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-preparation/index'],] : null,
                Yii::$app->user->can('ro_turn_arround_time') ? ['label' => 'Turn Arround Time', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/turnarround-time'],] : null,
                Yii::$app->user->can('ro_transmittal') ? ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/transmittal/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Document Tracking', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/document-tracker/index'],] : null,
                Yii::$app->user->can('ro_jev_bgn_bal') ? ['label' => 'JEV Beginning Balance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-beginning-balance/index'],] : null,
                Yii::$app->user->can('ro_remit_pye') ? ['label' => 'Remittance Payee', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/remittance-payee/index'],] : null,
                Yii::$app->user->can('ro_payroll') ? ['label' => 'Payroll', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/payroll/index'],] : null,
                Yii::$app->user->can('ro_remitttance') ? ['label' => 'Remittance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/remittance/index'],] : null,
                Yii::$app->user->can('ro_alphalist') ? ['label' => 'Alphalist', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ro-alphalist/index'],] : null,
                Yii::$app->user->can('ro_liq_rpt') ? ['label' => 'Liquidation Report', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ro-liquidation-report/index'],] : null,
            ];
            $accountingReports = [
                // Yii::$app->user->can('super-user') ?   ['label' => 'DV w/o File Link', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/dv-aucs/no-file-link-dvs'],] : null,
                Yii::$app->user->can('ro_gen_led') ?   ['label' => 'General Ledger', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/general-ledger/index'],] : null,
                Yii::$app->user->can('ro_gen_jour') ?   ['label' => 'General Journal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/general-journal/index'],] : null,
                Yii::$app->user->can('ro_adadj') ?   ['label' => 'ADADJ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-preparation/adadj'],] : null,
                Yii::$app->user->can('ro_ckdj') ?   ['label' => 'CKDJ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/jev-preparation/ckdj'],] : null,
                Yii::$app->user->can('ro_trial_bal') ?   ['label' => 'Trial Balance', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/trial-balance/index'],] : null,
                Yii::$app->user->can('ro_cns_trl_bal') ?   ['iconStyle' => 'far', 'label' => 'ConsoTrial Balance', 'icon' => 'dot-circle', 'url' => ['/conso-trial-balance/index'],] : null,
                Yii::$app->user->can('ro_sub_trl_bal') ?   ['iconStyle' => 'far', 'label' => 'Sub Trial Balance', 'icon' => 'dot-circle', 'url' => ['/sub-trial-balance/index'],] : null,
                Yii::$app->user->can('ro_cns_sub_trl_bal') ?   ['iconStyle' => 'far', 'label' => 'Conso Sub Trial Balance', 'icon' => 'dot-circle', 'url' => ['/conso-sub-trial-balance/index'],] : null,
                Yii::$app->user->can('ro_sub_led') ?   ['iconStyle' => 'far', 'label' => 'Subsidiary Ledger', 'icon' => 'dot-circle', 'url' => ['/jev-preparation/get-subsidiary-ledger'],] : null,
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
                Yii::$app->user->can('super-user') ? ['label' => 'Responsibility Center', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/responsibility-center/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Documet Receive', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/document-recieve/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Fund Cluster Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/fund-cluster-code/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Financing Source Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/financing-source-code/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Authorization Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/authorization-code/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Fund Classification Code', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/fund-category-and-classification-code/index']] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'MFO/PAP Codes', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/mfo-pap-code/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Fund Source', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/fund-source/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Allotment Type', 'iconStyle' => 'far', 'icon' => 'dot-circle', 'url' => ['/allotment-type/index'],] : null,
            ];
            $budgetTransactions = [
                Yii::$app->user->can('super-user') ? ['iconStyle' => 'far', 'label' => 'Record Allotments', 'icon' => 'dot-circle', 'url' => ['/record-allotments/index'],] : null,
                Yii::$app->user->can('super-user') ? ['iconStyle' => 'far', 'label' => 'Process Ors', 'icon' => 'dot-circle', 'url' => ['/process-ors/index'],] : null,
                Yii::$app->user->can('super-user') ? ['iconStyle' => 'far', 'label' => 'Process BURS', 'icon' => 'dot-circle', 'url' => ['/process-ors/burs-index'],] : null,
                Yii::$app->user->can('super-user') ? ['iconStyle' => 'far', 'label' => 'MAF', 'icon' => 'dot-circle', 'url' => ['/maf/index'],] : null,
            ];
            $budgetReports = [
                Yii::$app->user->can('super-user') ?     ['iconStyle' => 'far', 'label' => 'SAOB', 'icon' => 'dot-circle', 'url' => ['/saob/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['iconStyle' => 'far', 'label' => 'FUR', 'icon' => 'dot-circle', 'url' => ['/ro-fur/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['iconStyle' => 'far', 'label' => 'RAO', 'icon' => 'dot-circle', 'url' => ['/report/rao'],] : null,

            ];
            $budgetStatusOfFunds = [
                Yii::$app->user->can('super-user') ?     ['iconStyle' => 'far', 'label' => 'per MFO/PAP', 'icon' => 'dot-circle', 'url' => ['/budget-reports/sof-per-mfo'],] : null,
                Yii::$app->user->can('sof_per_office') ?     ['iconStyle' => 'far', 'label' => 'per Office/Division', 'icon' => 'dot-circle', 'url' => ['/budget-reports/sof-per-office'],] : null,
                Yii::$app->user->can('sof_per_mfo_office') ?     ['iconStyle' => 'far', 'label' => 'per MFO & Office', 'icon' => 'dot-circle', 'url' => ['/budget-reports/sof-per-mfo-office'],] : null,
            ];
            $cashMasterRecords = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Check Ranges', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ro-check-ranges/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Mode of Payments', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/mode-of-payments/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Banks', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/banks/index'],] : null,
            ];
            $cashTransactions = [
                Yii::$app->user->can('super-user') ? ['label' => 'Cash Disbursement', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-disbursement/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'SLIIE`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/sliies/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'LDDAP-ADA`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/lddap-adas/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'ACIC`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/acics/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'ACIC in Bank', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/acic-in-bank/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'RCI', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rci/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'RADAI', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/radai/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Cash Received', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-received/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Laps Amounts', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-adjustment/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'Cancel Disbursement', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cash-disbursement/cancel-disbursement-index'],] : null,
            ];
            $cashReports = [
                Yii::$app->user->can('super-user') ?     ['label' => 'CADADR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/cadadr'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'CADADR per DV', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/dv-cadadr'],] : null,
            ];
            $propertyMasterRecords  = [
                Yii::$app->user->can('super-user') ?     ['label' => 'SSF SP No.', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ssf-sp-num/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'SSF SP Status', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ssf-sp-status/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Locations', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/location/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Unit of Measure', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/unit-of-measure/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Agency', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/agency/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Employees', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/employee/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Transfer Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/transfer-type/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Property Articles', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-articles/index'],] : null,
                Yii::$app->user->can('super-user') ? ['label' => 'City/Municipality', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/citymun/index'],] : null,
            ];
            $propertyTransactions = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Property', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'PTR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/ptr/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'PAR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/par/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Property Card', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-card/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Other Property Details', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/other-property-details/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Depreciation Schedule', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/depreciation-schedule/index'],] : null,
                Yii::$app->user->can('rlsddp') ?     ['label' => 'RLSDDP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rlsddp/index'],] : null,
                Yii::$app->user->can('iirup') ?     ['label' => 'IIRUP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iirup/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Derecognition', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/derecognition/index'],] : null,
            ];
            $propertReports = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Print PC Stickers', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property-card/print-pc'],] : null,
                Yii::$app->user->can('super-user') ?  ['label' => 'Property Database', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/property-database'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'RPCPPE', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rpcppe/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'PPELC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/ppelc'],] : null,
                Yii::$app->user->can('property_accountabilities') ?     ['label' => 'Accountabilities', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/user-properties'],] : null,
            ];
            $procurementMasterRecords = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Contract Type', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-contract-type/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Mode of Procurment', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-mode-of-procurement/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Office/Section', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-office/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Stock/Property', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-stock/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'BAC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bac-composition/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'BAC position', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bac-position/index'],] : null,

            ];
            $procurementTransactions = [

                Yii::$app->user->can('ppmp') ?     ['label' => 'Supplemental PPMP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/supplemental-ppmp/index'],] : null,
                Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-request/index'],] : null,
                Yii::$app->user->can('rfq') ?     ['label' => 'RFQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-rfq/index'],] : null,
                Yii::$app->user->can('aoq') ?     ['label' => 'AOQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-aoq/index'],] : null,
                Yii::$app->user->can('purchase_order') ?     ['label' => 'Purchase Order', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-order/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/purchase-order-transmittal/index'],] : null,
            ];
            $procurementReports = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/procurement-summary'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'PO Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/pr-summary'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Search', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/proc-summary'],] : null,
            ];
            $generalServiceMasterRecords = [
                Yii::$app->user->can('super-user') ?     ['label' => 'Cars', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cars/index'],] : null,
            ];

            $generalServiceTransactions = [

                Yii::$app->user->can('super-user') ?     ['label' => 'Job Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/maintenance-job-request/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Pre-Repair Inspection', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pre-repair-inspection/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Trip Ticket', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/trip-ticket/index'],] : null,

            ];
            $generalServiceReports = [];

            $inspectionMasterRecords = [];
            $inspectionTransactions = [
                Yii::$app->user->can('request_for_inspection') ?     ['label' => 'Request for Inspection', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/request-for-inspection/index'],] : null,
                Yii::$app->user->can('inspection_report') ?     ['label' => 'Inspection Report', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/inspection-report/index'],] : null,
                Yii::$app->user->can('iar') ?     ['label' => 'IAR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iar/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'IAR Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/iar-transmittal/index'],] : null,
            ];
            $inspectionReports = [];

            $provinceMasterRecords = [
                Yii::$app->user->can('province') ?  ['label' => 'Advances Report Types', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/advances-report-types/index'],] : null,
                Yii::$app->user->can('province') ?  ['label' => 'Bank Account', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bank-account/index'],] : null,
                Yii::$app->user->can('po_check_range') ?  ['label' => 'Check Range', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/check-range/index'],] : null,
                Yii::$app->user->can('po_asignatory') ?     ['label' => 'PO Asignatory', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-assignatory/index'],] : null,
                Yii::$app->user->can('po_responsibility_center') ?     ['label' => 'PO Responsibility Center', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-responsibility-center/index'],] : null,
            ];
            $provinceTransactions = [
                Yii::$app->user->can('advances') ?     ['label' => 'Advances', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/advances/index'],] : null,
                Yii::$app->user->can('po_transaction') ?     ['label' => 'PO Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transaction/index'],] : null,
                Yii::$app->user->can('liquidation') ?     ['label' => 'Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ["/liquidation/index"],] : null,
                Yii::$app->user->can('liquidation') ?     ['label' => 'Cancelled Check', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/liquidation/cancelled-check-index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Pending Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/pending-at-ro'],] : null,
                Yii::$app->user->can('po_transmittal') ? ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/index'],] : null,
                Yii::$app->user->can('po_transmittal') ? ['label' => 'Returned DV`s', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/returned-liquidation'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'PO Transmittal to COA', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal-to-coa/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Positions', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/employee-position/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Alphalist', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/alphalist/index'],] : null,
            ];
            $provinceReports = [
                Yii::$app->user->can('po_cdr') ?     ['label' => 'CDR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/cdr/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/fur/index'],] : null,
                Yii::$app->user->can('province') ?     ['label' => 'ROD', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rod/index'],] : null,
                Yii::$app->user->can('fund_source_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/fund-source-fur'],] : null,
                Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/summary-fund-source-fur'],] : null,
                Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/budget-year-fur'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'MLP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/monthly-liquidation-program/index'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'Adequacy of Resource', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/province-adequacy'],] : null,
                Yii::$app->user->can('advances_liquidation') ?     ['label' => 'Advances/Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/advances-liquidation'],] : null,
                Yii::$app->user->can('super-user') ?     ['label' => 'PO Transmittal Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/po-transmittal-summary'],] : null,
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
            $menuItems =  [
                [
                    'label' => 'Accounting',
                    'icon' => 'calculator',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far',
                            'items' => removeNull($accountingMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($accountingTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($budgetMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($budgetTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
                            'items' => removeNull($budgetReports)
                        ],
                        [
                            'label' => 'Status of Funds',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($cashMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($cashTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($propertyMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($propertyTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($procurementMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($procurementTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($generalServiceMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($generalServiceTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($inspectionMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($inspectionTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($provinceMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($provinceTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull($itMasterRecords)
                        ],
                        [
                            'label' => 'Transactions',
                            'iconStyle' => 'far',
                            'items' => removeNull($itTransactions)
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
                            'items' => removeNull($itReports)
                        ],
                    ],


                ],


            ];
            $poUserMenuItems = [

                [
                    'label' => 'Master Records',
                    'icon' => 'fa fa-book',
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
                            'iconStyle' => 'far',
                            'url' => '#',
                            'items' => removeNull([
                                Yii::$app->user->can('po_advances') ?     ['label' => 'Advances', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/advances/index'],] : null,
                                Yii::$app->user->can('po_transaction') ?     ['label' => 'PO Transaction', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transaction/index'],] : null,
                                Yii::$app->user->can('liquidation') ?     ['label' => 'Liquidation', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/liquidation/index'],] : null,
                                Yii::$app->user->can('liquidation') ?     ['label' => 'Cancelled Check', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/liquidation/cancelled-check-index'],] : null,
                                Yii::$app->user->can('po_transmittal') ? ['label' => 'DV Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/po-transmittal/index'],] : null,

                            ]),
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull([
                                Yii::$app->user->can('po_fnd_src_fur') ?     ['label' => 'Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/fund-source-fur'],] : null,
                                Yii::$app->user->can('po_smry_fnd_src_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/summary-fund-source-fur'],] : null,
                                Yii::$app->user->can('po_smry_bgt_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/budget-year-fur'],] : null,
                                Yii::$app->user->can('po_adqcy_rsrc') ?     ['label' => 'Adequacy of Resource', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/province-adequacy'],] : null,
                                // Yii::$app->user->can('po_accounting_admin') ?     ['label' => 'MLP', 'icon' => 'dot-circle','iconStyle'=>'far', 'url' => ['/monthly-liquidation-program'],] : null,

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
                            'iconStyle' => 'far',
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
                            'iconStyle' => 'far',
                            'items' => removeNull([
                                Yii::$app->user->can('property') ?  ['label' => 'Property Database', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/property/property-database'],] : null,
                                Yii::$app->user->can('rpcppe') ?     ['label' => 'RPCPPE', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rpcppe/index'],] : null,
                                Yii::$app->user->can('property') ?     ['label' => 'PPELC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/ppelc'],] : null,
                                Yii::$app->user->can('property') ?     ['label' => 'Accountabilities', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/user-properties'],] : null,
                            ]),
                        ],
                    ],
                ],
                [
                    'label' => 'Query',
                    'icon' => 'fa fa-database',
                    'items' => removeNull([
                        Yii::$app->user->can('province') ?     ['label' => 'Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/fund-source-fur'],] : null,
                        Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Fund Source FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/summary-fund-source-fur'],] : null,
                        Yii::$app->user->can('summary_fund_source_fur') ?     ['label' => 'Summary Budget Year FUR', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/budget-year-fur'],] : null,
                        Yii::$app->user->can('province') ?     ['label' => 'ROD', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/rod/index'],] : null,
                        Yii::$app->user->can('province') ?     ['label' => 'Adequacy of Resource', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/province-adequacy'],] : null,

                    ]),
                ],
                [
                    'label' => 'Procurement',
                    'icon' => 'fa fa-shopping-cart',
                    'items' => [
                        [
                            'label' => 'Master Records',
                            'iconStyle' => 'far',
                            'items' => removeNull([
                                Yii::$app->user->can('po_procurement_admin') ?     ['label' => 'RBAC', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/bac-composition/index'],] : null,

                            ]),
                        ],
                        [
                            'label' => 'Transaction',
                            'iconStyle' => 'far',
                            'items' => removeNull([
                                // Yii::$app->user->can('super-user') ?     ['label' => 'Activity/Project Procurement', 'icon' => 'dot-circle','iconStyle'=>'far', 'url' => ['/pr-project-procurement'],] : null,
                                Yii::$app->user->can('ppmp') ?     ['label' => 'Supplemental PPMP', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/supplemental-ppmp/index'],] : null,
                                Yii::$app->user->can('purchase_request') ?     ['label' => 'Purchase Request', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-request/index'],] : null,
                                Yii::$app->user->can('rfq') ?     ['label' => 'RFQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-rfq/index'],] : null,
                                Yii::$app->user->can('aoq') ?     ['label' => 'AOQ', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-aoq/index'],] : null,
                                Yii::$app->user->can('purchase_order') ?     ['label' => 'Purchase Order', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/pr-purchase-order/index'],] : null,
                                Yii::$app->user->can('super-user') ?     ['label' => 'Transmittal', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/purchase-order-transmittal/index'],] : null,

                            ]),
                        ],
                        [
                            'label' => 'Reports',
                            'iconStyle' => 'far',
                            'items' => removeNull([
                                Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/procurement-summary'],] : null,
                                Yii::$app->user->can('super-user') ?     ['label' => 'PO Summary', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/pr-summary'],] : null,
                                Yii::$app->user->can('super-user') ?     ['label' => 'Procurement Search', 'icon' => 'dot-circle', 'iconStyle' => 'far', 'url' => ['/report/proc-summary'],] : null,
                            ]),
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






            ];


            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => strtolower($user_data->office->office_name) !== 'ro' ? $poUserMenuItems : $menuItems
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>