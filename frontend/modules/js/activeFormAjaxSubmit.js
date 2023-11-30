function ajaxSubmit(form) {
  //   var form = $(this);
  $.ajax({
    url: form.attr("action"),
    type: form.attr("method"),
    data: form.serialize(),
    success: function (data) {
      console.log(data);
      swal({
        icon: "error",
        title: data,
        type: "error",
        timer: 3000,
        closeOnConfirm: false,
        closeOnCancel: false,
      });
    },
    error: function (data) {},
  });
}
