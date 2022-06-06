let header_rows_index = [];

// CONSO HEADER
function displayConsoHead(consoHeadData) {
  let head_row = "<tr><th>Books</th>";
  let counter = 1;
  $.each(consoHeadData, function (key, val) {
    head_row += `<th>${val}</th>`;
    header_rows_index[counter] = val;
    counter++;
  });
  head_row +=
    "<th>1600 (VAT/NonVAT)</th><th>160E  (Expanded Tax)</th><th>1601C (Compensation)</th></tr>";
  // console.log(header_rows_index)
  $("#conso_table tbody").append(head_row);
}

// CONSO DATA
function displayConso(data, head) {
  let row_number = 1;
  let total_conso_total_tax = 0;
  let grand_total_compensation = 0;
  let grand_total_vat_nonvat = 0;
  let grand_total_ewt_tax = 0;
  $.each(data, function (key, val) {
    let row = `<tr><td>${key}</td>`;
    // DISPLAY IYANG ROW
    $.each(head, function (key4, val4) {
      row += `<td class='amount'></td>`;
    });
    row += "<td></td><td></td><td></td></tr>";
    $("#conso_table tbody").append(row);

    let book_total_compensation = 0;
    let book_total_vat_nonvat = 0;
    let book_total_ewt_tax = 0;
    // ASSIGN ang DATA KUNG UNSA SIYA NA ROW AND COL NUMBER
    $.each(val, function (key2, val2) {
      // row += `<td>${val2.withdrawals}</td>`;

      const conso_total_tax = parseFloat(val2.total_tax);
      const compensation = parseFloat(val2.total_compensation);
      const vat_nonvat = parseFloat(val2.total_vat_nonvat);
      const ewt_tax = parseFloat(val2.total_ewt_goods_services);
      console.log();
      book_total_compensation += compensation;
      book_total_vat_nonvat += vat_nonvat;
      book_total_ewt_tax += ewt_tax;
      const data_index = header_rows_index.indexOf(val2.reporting_period) + 1;
      $(`#conso_table tbody tr > :nth-child(${data_index})`)
        .eq(row_number)
        .text(thousands_separators(conso_total_tax));

      total_conso_total_tax += conso_total_tax;
    });

    grand_total_compensation += book_total_compensation;
    grand_total_vat_nonvat += book_total_vat_nonvat;
    grand_total_ewt_tax += book_total_ewt_tax;
    $(`#conso_table tbody tr > :nth-child(${header_rows_index.length + 1})`)
      .eq(row_number)
      .text(thousands_separators(book_total_vat_nonvat));
    $(`#conso_table tbody tr > :nth-child(${header_rows_index.length + 2})`)
      .eq(row_number)
      .text(thousands_separators(book_total_ewt_tax));
    $(`#conso_table tbody tr > :nth-child(${header_rows_index.length + 3})`)
      .eq(row_number)
      .text(thousands_separators(book_total_compensation));
    row_number++;
  });

  // DISPLAY IYANG ROW FOR TOTALS
  let total_row = `<tr><th class='total'>TOTAL</th>`;
  $.each(head, function (key4, val4) {
    total_row += `<th class='amount total'></th>`;
  });
  total_row += "<td></td><td></td><td></td></tr>";
  // ASSIGN ang DATA KUNG UNSA SIYA NA ROW AND COL NUMBER
  $("#conso_table tbody").append(total_row);
  $.each(
    header_rows_index,
    function (header_rows_index_key, header_rows_index_val) {
      let col_total = 0;
      if (header_rows_index_key > 0) {
        const i = header_rows_index_key + 1;
        $(`#conso_table tbody tr > :nth-child(${i})`).each(function (key, val) {
          if (key > 0) {
            const amount =
              $(this).text() != ""
                ? parseFloat($(this).text().replace(/,/g, ""))
                : 0;
            col_total += amount;
          }
        });
        $(`#conso_table tbody tr > :nth-child(${i})`)
          .eq(row_number)
          .text(thousands_separators(col_total));
      }
    }
  );

  $(`#conso_table tbody tr > :nth-child(${header_rows_index.length + 1})`)
    .eq(row_number)
    .text(thousands_separators(grand_total_vat_nonvat));
  $(`#conso_table tbody tr > :nth-child(${header_rows_index.length + 2})`)
    .eq(row_number)
    .text(thousands_separators(grand_total_ewt_tax));
  $(`#conso_table tbody tr > :nth-child(${header_rows_index.length + 3})`)
    .eq(row_number)
    .text(thousands_separators(grand_total_compensation));
  const conso_total_row = `<tr>
            <th class='total' >Tax Grand Total</th>
            <th class='amount total' >${thousands_separators(
              total_conso_total_tax.toFixed(2)
            )}</th></tr>`;
  $("#conso_table tbody").append(conso_total_row);
}

// DETAILED DATA
function displayDetailedTable(data) {
  console.log(data);
  let total_amount_disbursed = 0;
  let total_vat_nonvat = 0;
  let total_ewt_goods_services = 0;
  let total_compensation = 0;
  let total_other_trust_liabilities = 0;
  let total_tax = 0;
  $.each(data, function (book_name, per_book_data) {
    const book_row = `<tr><th>${book_name}</th><td colspan='10'></td></tr>`;
    $("#detailed_table ").append(book_row);

    let book_total_tax = 0;
    let book_total_amount_disbursed = 0;
    let book_total_vat_nonvat = 0;
    let book_total_ewt_goods_services = 0;
    let book_total_compensation = 0;
    let book_total_other_trust_liabilities = 0;
    $.each(per_book_data, (key, val) => {
      const dv_number = val.dv_number;
      const check_number = val.check_number;
      const check_date = val.check_date;
      const amount_disbursed = parseFloat(val.amount_disbursed);
      const vat_nonvat = parseFloat(val.vat_nonvat);
      const ewt_goods_services = parseFloat(val.ewt_goods_services);
      const compensation = parseFloat(val.compensation);
      const other_trust_liabilities = parseFloat(val.other_trust_liabilities);
      const tax = ewt_goods_services + vat_nonvat;
      total_tax += tax;
      total_amount_disbursed += amount_disbursed;
      total_vat_nonvat += vat_nonvat;
      total_ewt_goods_services += ewt_goods_services;
      total_compensation += compensation;
      total_other_trust_liabilities += other_trust_liabilities;

      book_total_tax += tax;
      book_total_amount_disbursed += amount_disbursed;
      book_total_vat_nonvat += vat_nonvat;
      book_total_ewt_goods_services += ewt_goods_services;
      book_total_compensation += compensation;
      book_total_other_trust_liabilities += other_trust_liabilities;

      const detailed_row = `<tr>
            <td></td>
            <td>${dv_number}</td>
            <td>${check_date}</td> 
            <td>${check_number}</td>
            <td class='amount'>${thousands_separators(amount_disbursed)}</td>
            <td class='amount'>${thousands_separators(compensation)}</td>
            <td class='amount'>${thousands_separators(
              other_trust_liabilities
            )}</td>
            <td class='amount'>${thousands_separators(vat_nonvat)}</td>
            <td class='amount'>${thousands_separators(ewt_goods_services)}</td>
            <td class='amount'>${thousands_separators(tax)}</td>
        </tr>`;
      $("#detailed_table").append(detailed_row);
    });

    const total_per_book_row = `<tr>
    <th colspan='4'>${book_name} TOTAL</th>
    <th class='amount'>${thousands_separators(book_total_amount_disbursed)}</th>
    <th class='amount'>${thousands_separators(book_total_compensation)}</th>
    <th class='amount'>${thousands_separators(
      book_total_other_trust_liabilities
    )}</th>
    <th class='amount'>${thousands_separators(book_total_vat_nonvat)}</th>
    <th class='amount'>${thousands_separators(
      book_total_ewt_goods_services
    )}</th>
    <th class='amount'>${thousands_separators(book_total_tax)}</th>
</tr>`;
    $("#detailed_table").append(total_per_book_row);
  });

  const detailed_total_row = `<tr>
            <th colspan='4' style='font-weight:bold'>GRAND TOTAL</th>
            <th class='amount total'>${thousands_separators(
              total_amount_disbursed.toFixed(2)
            )}</th>
            <th class='amount total'>${thousands_separators(
              total_compensation.toFixed(2)
            )}</th>
            <th class='amount total'>${thousands_separators(
              total_other_trust_liabilities.toFixed(2)
            )}</th>
            <th class='amount total'>${thousands_separators(
              total_vat_nonvat.toFixed(2)
            )}</th>
            <th class='amount total'>${thousands_separators(
              total_ewt_goods_services.toFixed(2)
            )}</th>
            <th class='amount total'>${thousands_separators(
              total_tax.toFixed(2)
            )}</th>
        </tr>`;
  $("#detailed_table").append(detailed_total_row);
}
