function query(csrfToken, book_id, reporting_period) {
  $.ajax({
    type: "POST",
    url: window.location.pathname + "?r=general-journal/generate",
    data: {
      "_csrf-frontend": csrfToken,
      book_id: book_id,
      reporting_period: reporting_period,
    },
    success: function (data) {
      console.log(JSON.parse(data));
      displayData(JSON.parse(data));
    },
  });
}

function displayData(data) {
  $("#data-table tbody").html("");
  console.log(data);
  $.each(data, function (key, val) {
    const jev_number = key;
    const particular = val[0]["explaination"];
    const date = val[0]["date"];
    console.log(val[0]["explaination"]);
    const row = `<tr>
                        <td>${date}</td>
                        <td>${jev_number}</td>
                        <td>${particular}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      
            </tr>`;

    $("#data-table tbody").append(row);

    $.each(val, function (key2, val2) {
      const account_title = val2.account_title;
      const object_code = val2.object_code;
      const debit = val2.debit;
      const credit = val2.credit;
      const item_row = `<tr>
                            <td></td>
                            <td></td>
                            <td>${account_title}</td>
                            <td>${object_code}</td>
                            <td class='amount'>${thousands_separators(
                              debit
                            )}</td>
                            <td class='amount'>${thousands_separators(
                              credit
                            )}</td>
                        </tr>`;
      $("#data-table tbody").append(item_row);
    });
  });

  const footer = `<tr class="footer1" style="border:0;">
                  <td colspan="3" class="br"></td>
                  <td colspan='2' class="br" style="padding-top:2em">
                      CERTIFIED CORRECT:
                  </td>
                  <td></td>
                </tr>
                <tr class="footer2">
                  <td colspan="3" class="br"></td>

                  <td colspan='3' style="text-align: center;font-weight:bold;padding-top:4rem">
                          <span style='font-weight: bold;text-decoration:underline'>CHARLIE C. DECHOS, CPA</span>
                          <br>
                           <span>OIC Accountant III </span>
               
                  </td>
                </tr>`;
                $("#data-table tbody").append(footer);
}
