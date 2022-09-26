async function getDifference(link) {
  const res = await $.post(
    window.location.pathname + "?r=sync-database/" + link,
    {
      myData: "",
    }
  );
  return res;
}
async function createDifference(link, data) {
  const url = window.location.pathname + "?r=" + link + "/create";
  await $.ajax({
    type: "POST",
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
  const payee_difference = await getDifference("payee");
  await createDifference("payee-api", payee_difference);
}
async function updateCloudTransactionsApi() {
  const transactions_difference = await getDifference("transaction");
  await createDifference("transaction-api", transactions_difference);
}
async function updateCloudProcessOrsApi() {
  const process_ors_difference = await getDifference("process-ors");
  await createDifference("process-ors-api", process_ors_difference);
}
async function updateCloudRecordAllotmentApi() {
  const record_allotment_difference = await getDifference("record-allotment");
  await createDifference("record-allotment-api", record_allotment_difference);
}
async function updateCloudCashDisbursementApi() {
  const cash_disbursement_difference = await getDifference("cash-disbursement");
  await createDifference("cash-disbursement-api", cash_disbursement_difference);
}

async function updateCloudAdvancesApi() {
  const advances_difference = await getDifference("advances");
  await createDifference("advances-api", advances_difference);
}
async function updateCloudAdvancesEntriesApi() {
  const advances_entries_difference = await getDifference("/advances-entries");
  await createDifference("/advances-entries-api", advances_entries_difference);
}
async function updateCloudDvAucsApi() {
  const advances_entries_difference = await getDifference("/dv-aucs");
  await createDifference("/dv-aucs-api", advances_entries_difference);
}
async function updateCloudDvAucsEntriesApi() {
  const advances_entries_difference = await getDifference("/dv-aucs-entries");
  await createDifference("/dv-aucs-entries-api", advances_entries_difference);
}
async function updateCloudDvAccountingEntriessApi() {
  const advances_entries_difference = await getDifference(
    "/dv-accounting-entries"
  );
  await createDifference(
    "/dv-accounting-entries-api",
    advances_entries_difference
  );
}
