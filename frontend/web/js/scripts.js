function thousands_separators(num) {

    var number = Number(Math.round(num + 'e2') + 'e-2')
    var num_parts = number.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
}
// GET ALL BOOKS
const url = window.location.pathname
// $.getJSON(url + '?r=books/get-books')
//     .then(function (data) {
//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.name
//             })
//         })
//         book = array
//         $('#book').select2({
//             data: book,
//             placeholder: "Select Book",

//         })

//     });
function getBooks() {
    return $.getJSON(url + '?r=books/get-books')
}
// GET TRANSACTIONs

// $.getJSON('/dti-afms-2/frontend/web/index.php?r=transaction/get-all-transaction')
//     .then(function (data) {

//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.tracking_number
//             })
//         })
//         transaction = array
//         $('#transaction_id').select2({
//             data: transaction,
//             placeholder: "Select Transaction",

//         })

//     });


// GET CHART OF ACCOUNTS
// var accounts = []
// $.getJSON(url + '?r=chart-of-accounts/accounting-codes')
//     .then(function (data) {
//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.object_code,
//                 text: val.object_code + ' ' + val.account_title
//             })
//         })
//         accounts = array
//         $('#chart-0').select2({

//             data: accounts,
//             placeholder: "Select Chart of Account",

//         })
//     })

// RESPONSIBILITY CENTERS
// $.getJSON(url + '?r=responsibility-center/get-responsibility-center')
//     .then(function (data) {
//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.name
//             })
//         })
//         r_center = array
//         $('#r_center_id').select2({
//             data: r_center,

//             placeholder: 'Select Responsibility Center'
//         })
//     })
function getResponsibilityCenters() {
    return $.getJSON(url + '?r=responsibility-center/get-responsibility-center')
}
// GET ALL PAYEE
// $.getJSON(url + '?r=payee/get-payee')

//     .then(function (data) {

//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.account_name
//             })
//         })
//         payee = array
//         $('#payee').select2({
//             data: payee,
//             placeholder: "Select Payee",

//         })


//     })
function getPayee() {
    return $.getJSON(url + '?r=payee/get-payee')
}

// GET ALL CASHFLOW
$.getJSON(url + '?r=cash-flow/get-all-cashflow')
    .then(function (data) {

        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.specific_cashflow
            })
        })
        cashflow = array
        $('#cashflow-0').select2({
            data: cashflow,
            placeholder: 'Select Cash Flow'
        }).next().hide()


    })
// function getCashFlow() {
//     return $.getJSON(url + '?r=cash-flow/get-all-cashflow')
// }

// GET ALL NETASSETS
// $.getJSON(url + '?r=net-asset-equity/get-all-netasset')
//     .then(function (data) {

//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.specific_change
//             })
//         })
//         net_asset = array
//         $('#isEquity-0').select2({
//             data: net_asset,
//             placeholder: 'Select Net Asset'

//         }).next().hide();


//     })
// MRD CLASSIFICATIOn
// var mrd_classification = []
// $.getJSON(url + '?r=mrd-classification/get-mrd-classification')
//     .then(function (data) {
//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.name
//             })
//         })
//         mrd_classification = array
//         $('#mrd_classification').select2({
//             data: mrd_classification,
//             placeholder: "Select MRD Classification"
//         })

//     })

// GET ALL NATURE OF TRANSCTION
// $.getJSON(url + '?r=nature-of-transaction/get-nature-of-transaction')
//     .then(function (data) {
//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.name
//             })
//         })
//         nature_of_transaction = array
//         $('#nature_of_transaction').select2({
//             data: nature_of_transaction,
//             placeholder: "Select Nature of Transaction"
//         })

//     })

// GET TRANSCTIONS
// $.getJSON(url + '?r=transaction/get-transaction')
//     .then(function (data) {

//         var array = []
//         $.each(data, function (key, val) {
//             array.push({
//                 id: val.id,
//                 text: val.tracking_number
//             })
//         })
//         transaction = array
//         $('#transaction_id').select2({
//             data: transaction,
//             placeholder: "Select Transaction",

//         })

//     });
function getNetAssets() {
    return $.getJSON(url + '?r=net-asset-equity/get-all-netasset')
}
function getMrdClassification() {
    return $.getJSON(url + '?r=mrd-classification/get-mrd-classification')
}

function getNatureOfTransactions() {
    return $.getJSON(url + '?r=nature-of-transaction/get-nature-of-transaction')
}

// GET DEBIT AND CREDIT TOTAL
function getRoTransactions() {
    return $.getJSON(url + '?r=transaction/get-transaction')
}
function getChartOfAccounts(id) {
    console.log(id)
    return $.getJSON(url + '?r=chart-of-accounts/accounting-codes&id=' + id)
}
function getChartOfAccountsDv(id) {
    var csrfToken = $('meta[name="csrf-token"]').attr("content");

    return $.getJSON(url + '?r=chart-of-accounts/accounting-codes-dv&id=' + id)
}
function getFundSourceType() {
    return $.getJSON(url + '?r=fund-source-type/all-fund-source-type')

}
// function getFundSourceType() {
//     return $.getJSON(url + '?r=fund-source-type/all-fund-source-type')

// }
function getOrs(id) {
    return $.getJSON(url + '?r=tracking-sheet/get-all-ors&id=' + id)

}
function getAllTrackingSheet() {
    return $.getJSON(url + '?r=tracking-sheet/get-all-tracking-sheet')
}
function getAllPayee() {
    return $.getJSON(url + '?r=payee/get-payee')
}
function getAllTransaction() {
    return $.getJSON(url + '?r=transaction/get-all-transaction')
}

function getAllGeneralLedger() {
    return $.getJSON(url + '?r=chart-of-accounts/get-general-ledger')
}
