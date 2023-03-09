async function cal(
  data,
  salvage_value_percentage,
  useful_life_in_mnths = 0,
  first_month_depreciation
) {
  let calculatedData = [];

  let startDate = new Date(first_month_depreciation);
  var date = moment(first_month_depreciation); // replace with your date
  if (date.date() > 15) {
    startDate = new Date(date.add(1, "months").format("YYYY-MM-DD"));
  }
  let endDateMoment = moment(startDate);
  endDateMoment.add(useful_life_in_mnths, "months");
  const last_month = new Date(endDateMoment.calendar());
  const last_month_depreciation = moment(last_month).format("MMMM, YYYY");
  const second_to_the_last_mnth = moment(
    new Date(endDateMoment.subtract(1, "months").calendar())
  ).format("MMMM, YYYY");

  $.each(data, function (key, val) {
    const acquisition_cost = parseFloat(val.amount);
    const salvage_value =
      useful_life_in_mnths > 0
        ? (salvage_value_percentage / 100) * acquisition_cost
        : 0;
    const depreciable_amount =
      salvage_value > 0 ? acquisition_cost - parseFloat(salvage_value) : 0;
    const monthly_depreciation =
      useful_life_in_mnths > 0
        ? Math.round(depreciable_amount / parseInt(useful_life_in_mnths))
        : 0;
    const ttl_depreciation =
      useful_life_in_mnths > 0
        ? (useful_life_in_mnths - 1) * monthly_depreciation
        : 0;
    const mnthly_depreciation_lst_mnt =
      depreciable_amount > 0 ? depreciable_amount - ttl_depreciation : 0;
    calculatedData.push({
      book_name: val.book_name,
      acquisition_cost: acquisition_cost,
      salvage_value: salvage_value,
      depreciable_amount: depreciable_amount,
      monthly_depreciation: monthly_depreciation,
      ttl_depreciation: ttl_depreciation,
      mnthly_depreciation_lst_mnt: mnthly_depreciation_lst_mnt,
      last_month_depreciation: last_month_depreciation,
      useful_life_in_mnths:
        useful_life_in_mnths - 1 > 0
          ? useful_life_in_mnths - 1
          : "Not Applicable",
      first_month_depreciation: moment(startDate).format("MMMM, YYYY"),
      second_to_the_last_mnth: second_to_the_last_mnth,
    });
  });
  return calculatedData;
}

function displayCalulatedData(data) {
  $("#computation_table tbody").html("");
  $.each(data, (key, val) => {
    const acquisition_cost = parseFloat(val.acquisition_cost);
    const salvage_value = parseFloat(val.salvage_value);
    const depreciable_amount = parseFloat(val.depreciable_amount);
    const monthly_depreciation = parseFloat(val.monthly_depreciation);
    const ttl_depreciation = parseFloat(val.ttl_depreciation);
    const mnthly_depreciation_lst_mnt = parseFloat(
      val.mnthly_depreciation_lst_mnt
    );
    const row = `<tr>
      <td>${val.book_name}</td>
      <td >
          <span class="digits">${thousands_separators(acquisition_cost)}</span>
      </td>
      <td>
          <span class="digits">${thousands_separators(salvage_value)}</span>
      </td>
      <td>
       <span class="digits">${thousands_separators(depreciable_amount)}</span>
      </td>
      <td>${val.first_month_depreciation}</td>
      <td>${val.second_to_the_last_mnth}</td>
      <td>${val.useful_life_in_mnths}</td>
      <td>
         <span class="digits">${thousands_separators(
           monthly_depreciation
         )}</span>
      </td>
      <td>
         <span class="digits">${thousands_separators(ttl_depreciation)}</span>
      </td>
      <td>${val.last_month_depreciation}</td>
      <td>
         <span class="digits">${thousands_separators(
           mnthly_depreciation_lst_mnt
         )}</span>
      </td>

      </tr>`;

    $("#computation_table tbody").append(row);
  });
  // $("span.digits").digits();
}

async function calculateAndDisplay(
  data,
  salvage_value_percentage,
  useful_life_in_mnths,
  first_month_depreciation
) {
  const q = await cal(
    data,
    salvage_value_percentage,
    useful_life_in_mnths,
    first_month_depreciation
  );
  displayCalulatedData(q);
}
$(document).ready(() => {
  maskAmount();

  $("#items_table").on("change keyup", ".mask-amount", function () {
    $(this)
      .closest("tr")
      .find(".main-amount")
      .val($(this).maskMoney("unmasked")[0]);
  });
  $("#calculate").on("click", (e) => {
    e.preventDefault();
    const depreciation_schedule = $(
      "#otherpropertydetails-depreciation_schedule"
    ).val();
    const property_id = $("#otherpropertydetails-fk_property_id").val();
    // let data = $('[name^="items"]').serializeArray();
    // data.push({ name: "depreciation_schedule", value: depreciation_schedule });
    // data.push({ name: "property_id", value: property_id });
    $.ajax({
      type: "POST",
      url: window.location.pathname + "?r=other-property-details/items",
      data: {
        id: property_id,
      },
      success: function (data) {
        const items = [];
        $('select[name^="items["]').each(function (index) {
          var book = $(this).find("option:selected").text();
          var amount = $(this).closest("tr").find(".main-amount").val();
          items.push({
            amount: amount,
            book_name: book,
          });
        });

        const startDate = data;
        const salvage_value_percentage = parseInt(
          $("#otherpropertydetails-salvage_value_prcnt").val()
        );
        const useful_life = $("#otherpropertydetails-useful_life").val();
        calculateAndDisplay(
          items,
          salvage_value_percentage,
          useful_life,
          startDate
        );
      },
    });
  });

  // $("#otherpropertydetails-fk_chart_of_account_id").change(() => {
  //   $.ajax({
  //     type: "GET",
  //     url:
  //       window.location.pathname +
  //       "?r=other-property-details/search-chart-of-accounts",
  //     data: {
  //       id: $("#otherpropertydetails-fk_chart_of_account_id").val(),
  //     },
  //     success: function (data) {
  //       const res = data.results;
  //       console.log(res);
  //       useful_life_in_mnths =
  //         res[0].life_from > 0 ? parseInt(res[0].life_from) * 12 : 0;
  //       console.log(useful_life_in_mnths);
  //     },
  //   });
  // });
  $("#otherpropertydetails-fk_property_id").change(() => {
    const property_id = $("#otherpropertydetails-fk_property_id").val();
    $.ajax({
      type: "POST",
      url:
        window.location.pathname + "?r=other-property-details/property-details",
      data: {
        property_id: property_id,
      },
      success: function (data) {
        const res = JSON.parse(data);
        $(".property-details").html("");
        const d = `<div>
        <span><b>Property Number:</b></span>
        <span>${res.property_number}</span>
    </div>
    <div>
        <span><b>Article:</b></span>
        <span>${res.article}</span>
    </div>
    <div>
        <span><b>Acquisation Date:</b></span>
        <span>${res.date}</span>
    </div>
    <div>
        <span><b>Acquisition Amount:</b></span>
        <span>${thousands_separators(res.acquisition_amount)}</span>
    </div>`;
        $(".property-details").prepend(d);
      },
    });
  });
  $(".remove_this_row").on("click", function (event) {
    event.preventDefault();
    $(this).closest("tr").remove();
  });
  $(".add_new_row").on("click", function (event) {
    event.preventDefault();
    $(".book").select2("destroy");

    const source = $(this).closest("tr");
    const clone = source.clone(true);
    clone.find(".mask-amount").val(0);
    clone.find(".amount").val(0);
    clone.find(".book").val("").trigger("change");
    clone.find(".amount").attr("name", `items[${row_number}][amount]`);
    clone.find(".book").attr("name", `items[${row_number}][book]`);
    clone.find(".item_id").remove();
    clone.find(".remove_this_row").removeClass("disabled");
    $("#items_table tbody").append(clone);
    maskAmount();
    getAllBooks();
    row_number++;
  });
});
