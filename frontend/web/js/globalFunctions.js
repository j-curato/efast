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

function employeeSelect() {
  $(".employee_select").select2({
    ajax: {
      url: window.location.pathname + "?r=employee/search-employee",
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
function rfiPurchaseOrderSelect() {
  $(".purchase-order").select2({
    ajax: {
      url: base_url + "?r=pr-purchase-order/search-purchase-order-for-rfi",
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
function stockTypeSelect() {
  $(".stock-type").select2({
    ajax: {
      url: base_url + "?r=pr-stock-type/search-stock-type",
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

// async function getAllResponsibilityCenter() {
//   let responsibility_center = [];
//   await $.getJSON(
//     window.location.pathname +
//       "/frontend/web/index.php?r=responsibility-center/get-responsibility-center"
//   ).then(function (data) {
//     $.each(data, function (key, val) {
//       responsibility_center.push({
//         id: val.id,
//         text: val.name,
//       });
//     });
//   });
//   console.log(responsibility_center);
//   return { responsibility_center };
// }
async function getAllResponsibilityCenter() {
  const responsibility_center = [];

  await $.ajax({
    type: "GET",
    url:
      window.location.pathname +
      "?r=responsibility-center/get-responsibility-center",
    success: function (data) {
      const res = JSON.parse(data);
      $.each(res, function (key, val) {
        responsibility_center.push({
          id: val.id,
          text: val.name,
        });
      });
    },
  });
  return { responsibility_center };
}
async function getAllMfo() {
  const mfo = [];
  await $.ajax({
    type: "GET",
    url: window.location.pathname + "?r=mfo-pap-code/get-mfo-pap-codes",
    success: function (data) {
      const res = JSON.parse(data);
      $.each(res, function (key, val) {
        mfo.push({
          id: val.id,
          text: val.name,
        });
      });
    },
  });
  return { mfo };
}

$(".mask-amount").on("keyup change", () => {
  $(".main-amount").val($(this).maskMoney("unmasked"));
});
