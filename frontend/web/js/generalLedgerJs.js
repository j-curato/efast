function displayData(beginning_balance, general_ledger) {
  let beginning_balance_debit =
    beginning_balance.debit != null ? parseFloat(beginning_balance.debit) : 0;
  let beginning_balance_credit =
    beginning_balance.debit != null ? parseFloat(beginning_balance.credit) : 0;
  let total_beginning_balance =
    beginning_balance.debit != null
      ? parseFloat(beginning_balance.beginning_balance_total)
      : 0;
  var row = `<tr>
            <td></td>
            <td></td>
            <td>Beginning Balance</td>
            <td></td>
            <td class='amount'>${thousands_separators(
              beginning_balance_debit
            )}</td>
            <td class='amount'>${thousands_separators(
              beginning_balance_credit
            )}</td>
            <td class='amount'>${thousands_separators(
              total_beginning_balance
            )}</td>
            </tr>`;
  $("#data_table tbody").append(row);

  for (var i = 0; i < general_ledger.length; i++) {
    var reporting_period = general_ledger[i]["reporting_period"];
    var date = general_ledger[i]["date"];
    var particular = general_ledger[i]["particular"];
    var jev_number = general_ledger[i]["jev_number"];
    var debit = general_ledger[i]["debit"];
    var credit = general_ledger[i]["credit"];
    var accounting_entries_total = parseFloat(general_ledger[i]["total"]);
    total_beginning_balance += accounting_entries_total;
    row = `<tr>
            <td>${reporting_period}</td>
            <td>${date}</td>
            <td>${particular}</td>
            <td>${jev_number}</td>
            <td class='amount'>${thousands_separators(debit)}</td>
            <td class='amount'>${thousands_separators(credit)}</td>
            <td class='amount'>${thousands_separators(
              total_beginning_balance
            )}</td>
            </tr>`;

    $("#data_table tbody").append(row);
  }
}
function query(csrfParam, csrfToken, object_code, reporting_period, book_id) {
  $.ajax({
    type: "POST",
    url: window.location.pathname + "?r=general-ledger/generate-general-ledger",
    data: {
      object_code: object_code,
      book_id: book_id,
      reporting_period: reporting_period,
      "_csrf-frontend": csrfToken,
    },
    success: function (data) {
      const res = JSON.parse(data);
      displayData(res.beginning_balance, res.query);
    },
  });
}
