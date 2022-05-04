// AJAX chart/subaccounts Select2

const base_url = window.location.pathname;
function accountingCodesSelect() {
  $(".chart-of-accounts").select2({
    ajax: {
      url: base_url + "?r=chart-of-accounts/search-accounting-code",
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
function liquidationAccountingCodesSelect() {
  $(".liquidation-chart-of-accounts").select2({
    ajax: {
      url: base_url + "?r=chart-of-accounts/search-liquidation-accounting-code",
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
      url: base_url + "?r=payee/search-payee",
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
    // placeholder: "Search for a Payee",
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
// GET ALL NATURE OF TRANSCTION
function natureOfTransactionSelect() {
  $.getJSON(
    base_url + "?r=nature-of-transaction/get-nature-of-transaction"
  ).then(function (data) {
    var array = [];
    $.each(data, function (key, val) {
      array.push({
        id: val.id,
        text: val.name,
      });
    });
    nature_of_transaction = array;
    $(".nature-of-transaction").select2({
      data: nature_of_transaction,
      placeholder: "Select Nature of Transaction",
    });
  });
}
// GET FUND SOURCE TYPE
function getFundSourceType() {
  return $.getJSON(base_url + "?r=fund-source-type/all-fund-source-type");
}
