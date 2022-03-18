// AJAX chart/subaccounts Select2

function accountingCodesSelect() {
  $(".chart-of-accounts").select2({
    ajax: {
      url:
        window.location.pathname +
        "?r=chart-of-accounts/search-accounting-code",
      dataType: "json",
      data: function (params) {
        return {
          q: params.term,
        };
      },
      processResults: function (data) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: data.results,
        };
      },
    },
  });
}

// PAYEE SELECT2
function payeeSelect() {
  $(".payee").select2({
    ajax: {
      url: window.location.pathname + "?r=payee/search-payee",
      dataType: "json",
      data: function (params) {
        return {
          q: params.term,
        };
      },
      processResults: function (data) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: data.results,
        };
      },
    },
    placeholder: "Search for a Payee",
  });
}
// MASK AMOUNT
function maskAmount() {
  $(".mask-amount").maskMoney({
    allowNegative: true,
  });
}
// SEPARATE WITH COMMA AMOUNT
function thousands_separators(num) {
  var number = Number(Math.round(num + "e2") + "e-2");
  var num_parts = number.toString().split(".");
  num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return num_parts.join(".");
  console.log(num);
}
