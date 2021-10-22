$(function () {
  $(document).on('click', '.btn-add', function (e) {
    e.preventDefault();

    var dynaForm = $('.dynamic-wrap form:first'),
      currentEntry = $(this).parents('.entry:first'),
      newEntry = $(currentEntry.clone()).appendTo(dynaForm);

    newEntry.find('input').val('');
    dynaForm.find('.entry:not(:last) .btn-add')
      .removeClass('btn-add').addClass('btn-remove')
      .removeClass('btn-success').addClass('btn-danger')
      .html('<span class="glyphicon glyphicon-minus"></span>');
  }).on('click', '.btn-remove', function (e) {
    $(this).parents('.entry:first').remove();
    e.preventDefault();
    return false;
  });
});

function thousands_separators(num) {

  var number = Number(Math.round(num + 'e2') + 'e-2')
  var num_parts = number.toString().split(".");
  num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return num_parts.join(".");
}




