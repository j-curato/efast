function addData(res) {
    $("#fur_table tbody").html('');

    var division_keys = Object.keys(res)
    for (var i = 0; i < division_keys.length; i++) {
        var division_name = division_keys[i]

        var mfo_keys = Object.keys(res[division_name])
        for (var mfo_loop = 0; mfo_loop < mfo_keys.length; mfo_loop++) {
            var mfo_name = mfo_keys[mfo_loop];
            var total_allotment = 0
            var total_ors = 0
            var total_begin_balance = 0
            var total_to_date = 0
            var qqq = 1

            var str = mfo_name.toLowerCase().replace(/\(.*?\)/g, "-");
            str = mfo_loop + '_' + str.replace(/[\. ,:-]+/g, "-")
            row = `<tr class='data_row'  id='${str}'>
            <td colspan='' style='font-weight:bold;background-color:#cccccc' class='major-header'>` + division_name.toUpperCase() + `</td>
                  <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc' >` + mfo[mfo_name][0]['code'] + ' - ' + mfo_name + `</td>
                <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc' class='major-header'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    </tr>`

            $('#fur_table tbody').append(row)
            var document_keys = Object.keys(res[division_name][mfo_name])
            for (var document_loop = 0; document_loop < document_keys.length; document_loop++) {
                var document_name = document_keys[document_loop];

                row = `<tr class='data_row' >
                <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc'>` + document_name + `</td>
                <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    </tr>`
                $('#fur_table tbody').append(row)
                var major = res[division_name][mfo_name][document_name]
                for (var major_loop = 0; major_loop < major.length; major_loop++) {
                    var major_name = res[division_name][mfo_name][document_name][major_loop]['major_name'];
                    var allotment = res[division_name][mfo_name][document_name][major_loop]['allotment'];
                    var current_total_ors = res[division_name][mfo_name][document_name][major_loop]['current_total_ors'];
                    var prev_total_ors = res[division_name][mfo_name][document_name][major_loop]['prev_total_ors'];
                    var balance = res[division_name][mfo_name][document_name][major_loop]['balance'];
                    var begin_balance = res[division_name][mfo_name][document_name][major_loop]['begin_balance'];
                    var ors_to_date = res[division_name][mfo_name][document_name][major_loop]['ors_to_date'];
                    var utilization = res[division_name][mfo_name][document_name][major_loop]['utilization'];
                    // var utilization = ors_to_date / allotment
                    if (utilization == null) {
                        utilization = 0
                    }

                    row = `<tr class='data_row'>
                  
                    <td colspan=''  >` + major_name + `</td>
                    <td class='amount'>` + thousands_separators(begin_balance) + `</td>
                    <td class='amount'>` + thousands_separators(allotment) + `</td>
                    <td class='amount'>` + thousands_separators(current_total_ors) + `</td>
                    <td class='amount'>` + thousands_separators(balance) + `</td>
                    <td class='amount'>` + thousands_separators(utilization) + '%' + `</td>
                    </tr>`

                    $('#fur_table tbody').append(row)
                    total_allotment += parseFloat(allotment)
                    total_ors += parseFloat(current_total_ors)
                    total_begin_balance += parseFloat(begin_balance)
                    total_to_date += parseFloat(ors_to_date)
                    qqq++

                }
                qqq++
                var mfo_description = `<td rowspan='${major.length}'></td>`




            }
            row = `<tr class='data_row'>
                    <td rowspan='${qqq}'></td>
                    <td rowspan='${qqq}' style='padding:5px;text-align:left'>` + mfo[mfo_name][0]['description'] + `</td>
         
                    </tr>`

            $(`#${str}`).after(row)
            var ut = parseFloat(total_to_date) / parseFloat(total_allotment)
            row = `<tr class='data_row'>
                    <td ></td>
                    <td ></td>
                    <td style='font-weight:bold'>Total</td>
                    <td class='amount'>` + thousands_separators(total_begin_balance) + `</td>
                    <td class='amount'>` + thousands_separators(total_allotment) + `</td>
                    <td class='amount'>` + thousands_separators(total_ors) + `</td>
                    <td class='amount'>` + thousands_separators(total_begin_balance - total_ors) + `</td>
                    <td class='amount'>` + thousands_separators(ut) + '%' + `</td>
                    </tr>`

            $('#fur_table tbody').append(row)
        }

    }


}

function addToSummaryTable(conso) {
    $('#summary_table tbody').html('')
    var total_beginning_balance = 0
    var total_prev = 0
    var total_current = 0
    var total_to_date = 0
    var total_utilization = 0
    var total_balance = 0
    for (var i = 0; i < conso.length; i++) {
        var beginning_balance = parseFloat(conso[i]['beginning_balance'])
        var prev = parseFloat(conso[i]['prev'])
        var current = parseFloat(conso[i]['current'])
        var to_date = parseFloat(conso[i]['to_date'])
        var utilization = to_date / beginning_balance
        var balance = beginning_balance - to_date
        var row = `<tr>
            <td>` + conso[i]['mfo_name'] + `</td>
            <td>` + conso[i]['document'] + `</td>
            <td class='amount'>` + thousands_separators(beginning_balance) + `</td>
            <td class='amount'>` + thousands_separators(prev) + `</td>
            <td class='amount'>` + thousands_separators(current) + `</td>
            <td class='amount'>` + thousands_separators(to_date) + `</td>
            <td class='amount'>` + thousands_separators(balance) + `</td>
            <td class='amount'>` + thousands_separators(utilization) + `</td>
        </tr>`
        $('#summary_table tbody').append(row)
        total_beginning_balance += beginning_balance
        total_prev += prev
        total_current += current
        total_to_date += to_date

        total_balance += balance
    }
    total_utilization = total_to_date / total_beginning_balance
    row = `<tr>
            <td style='font-weight:bold' colspan='2'>Total</td>
            <td class='amount'>` + thousands_separators(total_beginning_balance.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_prev.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_current.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_to_date.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_balance.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_utilization.toFixed(2)) + `</td>
        </tr>`
    $('#summary_table tbody').append(row)
}