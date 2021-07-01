function thousands_separators(num) {

    var number = Number(Math.round(num + 'e2') + 'e-2')
    var num_parts = number.toString().split(".");
    num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return num_parts.join(".");
}
// GET ALL BOOKS

$.getJSON('/dti-afms-2/frontend/web/index.php?r=books/get-books')
    .then(function (data) {

        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.name
            })
        })
        book = array
        $('#book_id').select2({
            data: book,
            placeholder: "Select Book",

        })

    });
// GET TRANSACTIONs

$.getJSON('/dti-afms-2/frontend/web/index.php?r=transaction/get-all-transaction')
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

// GET CHART OF ACCOUNTS
$.getJSON('/dti-afms-2/frontend/web/index.php?r=chart-of-accounts/get-general-ledger')
    .then(function (data) {
        var array = []
        $.each(data, function (key, val) {
            array.push({
                id: val.id,
                text: val.object_code + ' ' + val.title
            })
        })
        accounts = array
        // var y=JSON.parse(accounts)
        chart_of_accounts = data

    })