let header_rows_index = [];
function displayConsoHead(consoHeadData) {
  let head_row = "<tr><td>Books</td>";
  $.each(consoHeadData, function (key, val) {
    head_row += `<th>${val}</th>`;
    header_rows_index[key + 1] = val;
  });
  head_row += "</tr>";
  // console.log(header_rows_index)
  $("#conso_table tbody").append(head_row);
}

function displayConso(data, head) {
  let row_number = 1;
  let total_conso_gross_amount = 0;

  $.each(data, function (key, val) {
    let row = `<tr><td>${key}</td>`;
    $.each(head, function (key4, val4) {
      row += `<td class='amount'></td>`;
    });
    row += "</tr>";
    $("#conso_table tbody").append(row);
    $.each(val, function (key2, val2) {
      // row += `<td>${val2.withdrawals}</td>`;
      const conso_gross_amount = parseFloat(val2.gross_amount);
      const data_index = header_rows_index.indexOf(val2.reporting_period) + 1;
      $(`#conso_table tbody tr > :nth-child(${data_index})`)
        .eq(row_number)
        .text(thousands_separators(conso_gross_amount));
      total_conso_gross_amount += conso_gross_amount;
    });
    row_number++;
  });
  const conso_total_row = `<tr>
                <td style='font-weight:bold'>Grand Total</td>
                <td class='amount' style='font-weight:bold'>${thousands_separators(total_conso_gross_amount.toFixed(2))}</td></tr>`;
  $("#conso_table tbody").append(conso_total_row);
}

function displayDetailed(data) {
  let total_withdrawals = 0;
  let total_gross_amount = 0;
  let total_vat_nonvat = 0;
  let total_expanded_tax = 0;
  let total_liquidation_damage = 0;
  $.each(data, function (key, val) {
    const dv_number = val.dv_number;
    const withdrawals = parseFloat(val.withdrawals);
    const payee = val.payee;
    const check_number = val.check_number;
    const gross_amount = parseFloat(val.gross_amount);
    const vat_nonvat = parseFloat(val.vat_nonvat);
    const expanded_tax = parseFloat(val.expanded_tax);
    const liquidation_damage = parseFloat(val.liquidation_damage);

    const row = `<tr>
                <td>${dv_number}</td>
                <td>${check_number}</td>
                <td>${payee}</td>
                <td class='amount'>${thousands_separators(gross_amount)}</td>
                <td class='amount'>${thousands_separators(withdrawals)}</td>
                <td class='amount'>${thousands_separators(vat_nonvat)}</td>
                <td class='amount'>${thousands_separators(expanded_tax)}</td>
                <td class='amount'>${thousands_separators(
                  liquidation_damage
                )}</td>
        </tr>`;
    $("#detailed_table tbody").append(row);

    total_withdrawals += withdrawals;
    total_gross_amount += gross_amount;
    total_vat_nonvat += vat_nonvat;
    total_expanded_tax += expanded_tax;
    total_liquidation_damage += liquidation_damage;
  });
  const total_row = `<tr>
                <td  colspan='3'>Total</td>
                <td class='amount'>${thousands_separators(
                  total_gross_amount.toFixed(2)
                )}</td>
                <td class='amount'>${thousands_separators(
                  total_withdrawals.toFixed(2)
                )}</td>
                <td class='amount'>${thousands_separators(
                  total_vat_nonvat.toFixed(2)
                )}</td>
                <td class='amount'>${thousands_separators(
                  total_expanded_tax.toFixed(2)
                )}</td>
                <td class='amount'>${thousands_separators(
                  total_liquidation_damage.toFixed(2)
                )}</td>
        </tr>`;
  $("#detailed_table tbody").append(total_row);
}
