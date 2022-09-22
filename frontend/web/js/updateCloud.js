async function getApi(link) {
  const res = await $.post(
    window.location.pathname + "?r=sync-database/" + link,
    {
      myData: "",
    }
  );
  return res;
}
async function createApi(link, data) {
  const url = "https://fisdticaraga.com/index.php?r=" + link + "/create";
  await $.ajax({
    type: "post",
    url: url,
    contentType: "application/json",
    data: data,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(JSON.parse(newdata));
    },
  });
}
async function updateCloudPayeeApi() {
  const payee_difference = await getApi("payee");
  await createApi("payee-api", payee_difference);
}
async function updateCloudTransactionsApi() {
  const transactions = await $.post(
    window.location.pathname + "?r=sync-database/transaction",
    {
      myData: "",
    }
  );
  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=transaction-api/create",
    contentType: "application/json",
    data: transactions,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(newdata);
    },
  });
}
async function updateCloudProcessOrsApi() {
  const process_ors = await $.post(
    window.location.pathname + "?r=sync-database/process-ors",
    {
      myData: "",
    }
  );
  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=process-ors-api/create",
    contentType: "application/json",
    data: process_ors,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(newdata);
    },
  });
}
async function updateCloudRecordAllotmentApi() {
  const process_ors = await $.post(
    window.location.pathname + "?r=sync-database/record-allotment",
    {
      myData: "",
    }
  );
  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=record-allotment-api/create",
    contentType: "application/json",
    data: process_ors,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(newdata);
    },
  });
}
async function updateCloudCashDisbursementApi() {
  const process_ors = await $.post(
    window.location.pathname + "?r=sync-database/cash-disbursement",
    {
      myData: "",
    }
  );
  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=cash-disbursement-api/create",
    contentType: "application/json",
    data: process_ors,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(newdata);
    },
  });
}

async function updateCloudAdvancesApi() {
  const process_ors = await $.post(
    window.location.pathname + "?r=sync-database/advances",
    {
      myData: "",
    }
  );
  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=advances-api/create",
    contentType: "application/json",
    data: process_ors,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(newdata);
    },
  });
}
async function updateCloudAdvancesEntriesApi() {
  const process_ors = await $.post(
    window.location.pathname + "?r=sync-database/advances-entries",
    {
      myData: "",
    }
  );
  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=advances-entries-api/create",
    contentType: "application/json",
    data: process_ors,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(newdata);
    },
  });
}
