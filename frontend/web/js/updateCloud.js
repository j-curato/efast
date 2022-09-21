async function updateCloudPayeeApi() {
  const payee_difference = await $.post(
    window.location.pathname + "?r=sync-database/payee",
    { myData: "" }
  );
  //   console.log(payee_difference);
  //   var d = JSON.parse(data);

  await $.ajax({
    type: "post",
    url: "https://fisdticaraga.com/index.php?r=payee-api/create",
    contentType: "application/json",
    data: payee_difference,
    dataType: "json",
    headers: {
      Authorization: `Bearer ${localStorage.getItem("token")}`,
    },
    success: function (newdata) {
      console.log(JSON.parse(newdata));
    },
  });
}
