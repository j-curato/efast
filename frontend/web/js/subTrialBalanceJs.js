function displayResultData(data) {
  $("#data_table tbody").html("");
  let total_debit = 0;
  let total_credit = 0;
  $.each(data, function (index, val) {
    let debit = "";
    let credit = "";
    let beginning_balance =
      val.begin_balance != null ? parseFloat(val.begin_balance) : 0;
    let total =
      val.total_debit_credit != null ? parseFloat(val.total_debit_credit) : 0;
    // let total = beginning_balance + total_debit_credit;

    if (val.normal_balance == null) {
      debit = "No Normal Balance";
      credit = "No Normal Balance";
    } else if (val.normal_balance.toLowerCase() == "debit") {
      if (total < 0) {
        var total_value = Math.abs(total);
        credit = thousands_separators(total_value.toFixed(2));
        total_credit += total_value;
      } else {
        debit = thousands_separators(total.toFixed(2));
        total_debit += total;
      }
    } else if (val.normal_balance.toLowerCase() == "credit") {
      if (total < 0) {
        var total_value = Math.abs(total);
        debit = thousands_separators(total_value.toFixed(2));
        total_debit += total_value;
      } else {
        credit = thousands_separators(total.toFixed(2));
        total_credit += total;
      }
    }

    const  row = `<tr>
            <td>${val.account_title}</td>
            <td>${val.object_code}</td>
            <td class='amount'>${debit}</td>
            <td class='amount'>${credit}</td>
        </tr>`;
    $("#data_table tbody").append(row);
  });
  const  total_row = `<tr>
            <td colspan='2' style='text-align:center;font-weight:bold;'>Total</td>
            <td class='amount'>${thousands_separators(
              total_debit.toFixed(2)
            )}</td>
            <td class='amount'>${thousands_separators(
              total_credit.toFixed(2)
            )}</td>
        </tr>`;
  $("#data_table tbody").append(total_row);
}
function query(csrfToken, reporting_period, book_id) {
  $.ajax({
    type: "POST",
    url:
      window.location.pathname +
      "?r=sub-trial-balance/generate-sub-trial-balance",
    data: {
      "_csrf-frontend": csrfToken,
      reporting_period: reporting_period,
      book_id: book_id,
    },
    success: function (data) {
      var res = JSON.parse(data);
      displayResultData(res);
      $("#month").text(res.month);
      $("#book_name").text(res.book_name);
      $(".container").show();
      $("#dots5").hide();
    },
  });
}
