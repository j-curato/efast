// AJAX chart/subaccounts Select2

const base_url = window.location.pathname;
function ObjectCodesSelect() {
  $(".object-codes").select2({
    ajax: {
      url: base_url + "?r=chart-of-accounts/search-accounting-code",
      dataType: "json",
      data: function (params) {
        return {
          q: params.term,
          page: params.page || 1,
        };
      },
      processResults: function (data) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: data.results,
          pagination: data.pagination,
        };
      },
    },
  });
}
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
//
function ChartOfAccountsSelect() {
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
        return {
          results: data.results,
          pagination: data.pagination,
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
          page: params.page || 1,
        };
      },
      processResults: function (data) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: data.results,
          pagination: data.pagination,
        };
      },
    },
  });
}
let books = [];
async function getAllBooks() {
  if (books.length === 0) {
    await $.getJSON(
      window.location.pathname + "/frontend/web/index.php?r=books/get-books"
    ).then(function (data) {
      var array = [];
      $.each(data, function (key, val) {
        array.push({
          id: val.id,
          text: val.name,
        });
      });
      books = array;
    });
  }
  $(".book").select2({
    data: books,
    placeholder: "Select Books",
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
          page: params.page || 1,
        };
      },
      // processResults: function (data, params) {
      //   params.page = params.page || 1;

      //   // Transforms the top-level key of the response object from 'items' to 'results'
      //   return {
      //     results: data.results,
      //     pagination: {
      //       more: params.page * 10 < data.count_filtered,
      //     },
      //   };
      // },
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
function negativeMaskAmount() {
  console.log("qwer");
  $(".negative-mask-amount").maskMoney({
    allowNegative: true,

    allowNegative: true, // allow negative numbers
    thousands: ",", // set thousands separator
    decimal: ".", // set decimal separator
    precision: 2, // set precision to 2 decimal places
    negativeFormat: "-$ n", // set the negative format to display a minus sign before the currency symbol
  });
}
// SEPARATE WITH COMMA AMOUNT
function thousands_separators(num) {
  if (isNaN(num)) {
    return;
  }
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
function stockSelect() {
  $(".stock").select2({
    ajax: {
      url: base_url + "?r=pr-stock/search-stock",
      dataType: "json",
      data: function (params) {
        return {
          q: params.term,
          page: params.page || 1,
        };
      },
      processResults: function (data) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: data.results,
          // pagination: data.pagination,
        };
      },
    },
  });
}
function paginatedStockSelect() {
  $(".stock-paginated").select2({
    ajax: {
      url: base_url + "?r=pr-stock/search-paginated-stock",
      dataType: "json",
      data: function (params) {
        return {
          q: params.term,
          page: params.page || 1,
          budget_year: $("#budget_year").val(),
          cse_type: $("#cse_type").val(),
        };
      },
      processResults: function (data) {
        // Transforms the top-level key of the response object from 'items' to 'results'
        return {
          results: data.results,
          pagination: data.pagination,
        };
      },
    },
  });
}
function unitOfMeasureSelect() {
  $(".unit-of-measure").select2({
    ajax: {
      url: base_url + "?r=unit-of-measure/search-unit-of-measure",
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
async function getAllFundSource() {
  const fund_sources = [];
  await $.ajax({
    type: "GET",
    url: window.location.pathname + "?r=fund-source/get-fund-sources",
    success: function (data) {
      const res = JSON.parse(data);
      $.each(res, function (key, val) {
        fund_sources.push({
          id: val.id,
          text: val.name,
        });
      });
    },
  });

  return { fund_sources };
}
async function getAllModeOfProcurement() {
  const modes = [];
  await $.ajax({
    type: "GET",
    url:
      window.location.pathname +
      "?r=pr-mode-of-procurement/get-mode-of-procurements",
    success: function (data) {
      const res = JSON.parse(data);
      $.each(res, function (key, val) {
        modes.push({
          id: val.id,
          text: val.mode_name,
        });
      });
    },
  });

  return { modes };
}

// $(".mask-amount").on("keyup change", () => {
//   $(".main-amount").val($(this).maskMoney("unmasked"));
// });
// put comma in numbers
$.fn.digits = function () {
  return this.each(function () {
    $(this).text(
      $(this)
        .text()
        .replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
    );
  });
};

$(".modalButtonCreate").click(function (e) {
  e.preventDefault();
  $("#genericModal")
    .modal("show")
    .find("#modalContent")
    .load($(this).attr("href"));
});

$(".modalButtonUpdate").click(function (e) {
  e.preventDefault();

  $("#genericModal")
    .modal("show")
    .find("#modalContent")
    .load($(this).attr("href"));
});
$(".lrgModal").click(function (e) {
  e.preventDefault();
  $("#lrgModal")
    .modal("show")
    .find("#lrgModalContent")
    .load($(this).attr("href"));
});
function UpdateMainAmount(q) {
  const amt = $(q).maskMoney("unmasked")[0];
  $(q).parent().find(".main-amount").val(amt);
  $(q).parent().find(".main-amount").trigger("change");
}
