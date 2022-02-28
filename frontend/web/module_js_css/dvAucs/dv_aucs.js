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

  if (
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
