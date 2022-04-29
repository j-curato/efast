function addData(res, major) {
    $("#fur_table tbody").html('');



    $.each(res, function(major_name, val) {

        const str = major_name.toLowerCase().replace(/\s/g, '-');
        const major_row = `<tr class='data_row' id ='${str}'>
                    <td colspan='' style='text-align:left;font-weight:bold;background-color:#cccccc' class='major-header'>` + major_name + `</td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    <td class='major-header' style='text-align:left;font-weight:bold;background-color:#cccccc'></td>
                    </tr>`
        $('#fur_table tbody').append(major_row)
        $.each(val, function(sub_major_name, val2) {
            const sub_major_row = `<tr class='data_row'>
                    <td colspan='' style='text-align:left;font-weight:bold'>` + sub_major_name + `</td>
                    <td ></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>`
            if (sub_major_name != major_name) {
                $('#fur_table tbody').append(sub_major_row)
            }
            $.each(val2, function(key, val3) {
                const prev_allotment = parseFloat(val3.prev_allotment)
                const current_allotment = parseFloat(val3.current_allotment)
                const prev_total_ors = parseFloat(val3.prev_total_ors)
                const current_total_ors = parseFloat(val3.current_total_ors)
                const ors_to_date = parseFloat(val3.to_date)
                const chart_of_account = val3.account_title
                const mfo_name = val3.mfo_name
                const document_name = val3.document_name
                const balance = parseFloat(val3.balance)
                let utilazation = 0
                if (ors_to_date != 0 || (prev_allotment + current_allotment) != 0) {
                    utilazation = ors_to_date / (prev_allotment + current_allotment) * 100
                }
                const data_row = `<tr class='data_row'>
                    <td colspan='' style='text-align:right;'>` + chart_of_account + `</td>
                    <td  class='amount'>${thousands_separators(prev_allotment)}</td>
                    <td class='amount'>${thousands_separators(current_allotment)}</td>
                    <td class='amount'>${thousands_separators(prev_total_ors)}</td>
                    <td class='amount'>${thousands_separators(current_total_ors)}</td>
                    <td class='amount'>${thousands_separators(ors_to_date)}</td>
                    <td class='amount'>${thousands_separators(balance)}</td>
                    <td class='amount'>${thousands_separators(utilazation)}</td>
          
                    <td>${mfo_name}</td>
                    <td>${document_name}</td>
                    </tr>`
                $('#fur_table tbody').append(data_row)
            })
        })
    })



    


}

function addToSummaryTable(conso) {
    $('#summary_table tbody').html('')
    let total_beginning_balance = 0
    let total_prev = 0
    let total_current = 0
    let total_to_date = 0
    let total_utilization = 0
    let total_balance = 0
    let total_prev_allotment = 0
    let total_current_allotment = 0
    $.each(conso, function(key, val) {
        const beginning_balance = parseFloat(val.beginning_balance)
        const current_allotment = parseFloat(val.current_allotment)
        const prev_allotment = parseFloat(val.prev_allotment)
        const prev = parseFloat(val.prev_total_ors)
        const current = parseFloat(val.current_total_ors)
        const to_date = parseFloat(val.to_date)
        const utilization = to_date / (prev_allotment + current_allotment) * 100
        const balance = parseFloat(val.balance)
        const row = `<tr>
            <td>` + val.mfo_name + `</td>
            <td>` + val.document_name + `</td>
            <td class='amount'>` + thousands_separators(prev_allotment) + `</td>
            <td class='amount'>` + thousands_separators(current_allotment) + `</td>
            <td class='amount'>` + thousands_separators(prev) + `</td>
            <td class='amount'>` + thousands_separators(current) + `</td>
            <td class='amount'>` + thousands_separators(to_date) + `</td>
            <td class='amount'>` + thousands_separators(balance) + `</td>
            <td class='amount'>` + thousands_separators(utilization) + '%' + `</td>
        </tr>`
        $('#summary_table tbody').append(row)
        total_beginning_balance += beginning_balance
        total_prev += prev
        total_current += current
        total_to_date += to_date
        total_balance += balance
        total_prev_allotment += prev_allotment
        total_current_allotment += current_allotment

    })

    total_utilization = total_to_date / (total_prev_allotment + total_current_allotment) * 100
    row = `<tr>
            <td style='font-weight:bold' colspan='2'>Total</td>
            <td class='amount'>` + thousands_separators(total_prev_allotment.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_current_allotment.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_prev.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_current.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_to_date.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_balance.toFixed(2)) + `</td>
            <td class='amount'>` + thousands_separators(total_utilization.toFixed(2)) + '%' + `</td>
        </tr>`
    $('#summary_table tbody').append(row)
}