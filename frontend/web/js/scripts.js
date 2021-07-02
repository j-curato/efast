function thousands_separators(num) {

    var number = Number(Math.round(num + 'e2') + 'e-2')
    var num_parts = number.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
}
// GET ALL BOOKS
const url = window.location.pathname
$.getJSON(url + '?r=books/get-books')
    .then(function (data) {
        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.name
            })
        })
        book = array
        $('#book').select2({
            data: book,
            placeholder: "Select Book",

        })

    });
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
var accounts=
$.getJSON(url + '?r=chart-of-accounts/accounting-codes')
    .then(function (data) {
        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.object_code,
                text: val.object_code + ' ' + val.account_title
            })
        })
        accounts = array
        $('#chart-0').select2({

            data: accounts,
            placeholder: "Select Chart of Account",

        })
    })

// RESPONSIBILITY CENTERS
$.getJSON(url + '?r=responsibility-center/get-responsibility-center')
    .then(function (data) {
        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.name
            })
        })
        r_center = array
        $('#r_center_id').select2({
            data: r_center,

            placeholder: 'Select Responsibility Center'
        })
    })
// GET ALL PAYEE
$.getJSON(url + '?r=payee/get-payee')

    .then(function (data) {

        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.account_name
            })
        })
        payee = array
        $('#payee').select2({
            data: payee,
            placeholder: "Select Payee",

        })

    })
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

// GET ALL NETASSETS
$.getJSON(url + '?r=net-asset-equity/get-all-netasset')
    .then(function (data) {

        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.specific_change
            })
        })
        net_asset = array
        $('#isEquity-0').select2({
            data: net_asset,
            placeholder: 'Select Net Asset'

        }).next().hide();


    })
// MRD CLASSIFICATIOn
var mrd_classification = []
$.getJSON(url + '?r=mrd-classification/get-mrd-classification')
    .then(function (data) {
        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.name
            })
        })
        mrd_classification = array
        $('#mrd_classification').select2({
            data: mrd_classification,
            placeholder: "Select MRD Classification"
        })

    })

// GET ALL NATURE OF TRANSCTION
$.getJSON(url + '?r=nature-of-transaction/get-nature-of-transaction')
    .then(function (data) {
        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.name
            })
        })
        nature_of_transaction = array
        $('#nature_of_transaction').select2({
            data: nature_of_transaction,
            placeholder: "Select Nature of Transaction"
        })

    })

// GET FINANCING SOURCE CODES
$.getJSON(url + '?r=transaction/get-transaction')
    .then(function (data) {

        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.tracking_number
            })
        })
        transaction = array
        $('#transaction_id').select2({
            data: transaction,
            placeholder: "Select Transaction",

        })

    });



// GET DEBIT AND CREDIT TOTAL
function getTotal() {
    var total_credit = 0.00;
    var total_debit = 0.00;
    $(".credit").each(function () {
        total_credit += Number($(this).val());
    })
    $(".debit").each(function () {
        total_debit += Number($(this).val());
    })

    document.getElementById("d_total").innerHTML = "<h4>" + thousands_separators(total_debit) + "</h4>";
    document.getElementById("c_total").innerHTML = "<h4>" + thousands_separators(total_credit) + "</h4>";

}
