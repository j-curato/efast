$("#transaction").change(function () {
  var transaction_type = $("#transaction").val();
  $("#transaction_type").val(transaction_type);
  // var result=[]

  var result = [
    {
      serial_number: "",
      transaction_particular: "",
      transaction_payee: "",
      total: "",
    },
  ];
  var count = $("#transaction_table tbody tr").length;
  if (transaction_type == "Payroll") {
    $("#payroll_display").show();
  } else if (
    transaction_type != "Single" &&
    transaction_type != "Multiple" &&
    count - 1 < 0
  ) {
    addDvToTable(result, 1);
  }
  if (transaction_type == "Single") {
    $("#particular").attr("readonly", true);
  } else {
    $("#particular").attr("readonly", false);
  }

  if (transaction_type === "Single" || transaction_type === "Multiple") {
    $("#bok").hide();
    $("#book").prop("required", false);
  } else {
    $("#bok").show();
    $("#book").prop("required", true);
  }
});
// ADD ORS
$("#submit").click(function (e) {
  e.preventDefault();

  var transaction_type = $("#transaction").val();
  var count = $("#transaction_table tbody tr").length;
  if (transaction_type == "Single" && count >= 1) {
    swal({
      title: "Error",
      text: "Transaction Type is Single",
      type: "error",
      timer: 6000,
      button: false,
      // confirmButtonText: "Yes, delete it!",
    });
    return;
  }
  $.ajax({
    url: window.location.pathname + "?r=dv-aucs/get-dv",
    method: "POST",
    data: $("#add_data").serialize(),
    success: function (data) {
      var res = JSON.parse(data);
      console.log(res);
      if (res.isSuccess) {
        addDvToTable(res.results, row);
        row++;
      } else {
        swal({
          title: "Error",
          text: res.error,
          type: "error",
          timer: 6000,
          button: false,
          // confirmButtonText: "Yes, delete it!",
        });
      }
      if (transaction_type == "Single") {
        $("#particular").val(res.results[0]["transaction_particular"]);
        var payeeSelect = $("#payee");
        var option = new Option(
          res.results[0]["transaction_payee"],
          [res.results[0]["transaction_payee_id"]],
          true,
          true
        );
        payeeSelect.append(option).trigger("change");
      }
    },
  });
  $(".checkbox").prop("checked", false); // Checks it
  $(".amounts").prop("disabled", true);
  $(".amounts").val(null);
});

// PAYROLL
$("#payroll_number").change(function () {
  $.ajax({
    type: "POST",
    url: window.location.pathname + "?r=payroll/payroll-data",
    data: { id: $(this).val() },
    success: function (data) {
      console.log(data);
      const res = JSON.parse(data);
      console.log(res);
      const amount_disbursed = res.amount_disbursed;
      let ewt_goods_services = 0;
      let compensation = 0;
      const other_trust_liabilities = res.total_trust_liab;

      if (res.type == "2307") {
        ewt_goods_services = res.total_due_to_bir;
      } else if (res.type == "1601c") {
        compensation = res.total_due_to_bir;
      }
      $("#reporting_period").val(res.reporting_period);
      $("#particular").val(res.particular);
      $("#book_id").val(res.book_id).trigger("change");

      var payeeSelect = $("#payee");
      var option = new Option([res.payee], [res.payee_id], true, true);
      payeeSelect.append(option).trigger("change");

      var row = `<tr>
      <td style='display:none'> <input style='display:none' value='${res.ors_id}' type='text' name='process_ors_id[1]'/></td>
      <td>${res.ors_number}</td>
      <td> 
      ${res.particular}
      </td>
      <td>${res.payee}</td>
      <td></td>
      <td>
       <input value='${amount_disbursed}' name='amount_disbursed[1]' type='text'  class='amount_disbursed'/>
      </td>
      <td> <input value='0' type='text' name='vat_nonvat[1]' class='vat'/></td>
      <td> <input value='${ewt_goods_services}' type='text' name='ewt_goods_services[1]' class='ewt'/></td>
      <td> <input value='${compensation}' type='text' name='compensation[1]' class='compensation'/></td>
      <td> <input value='${other_trust_liabilities}' type='text' name='other_trust_liabilities[1]' class='liabilities'/></td>
      <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="glyphicon glyphicon-minus"></i></button></td></tr>
  `;
      $("#transaction_table tbody").append(row);
      // total += amount_disbursed
      select_id++;
      dv_count++;
    },
  });
});
