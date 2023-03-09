function display(data) {
  $("#data_tbl tbody").html("");

  $.each(data, (key, val) => {
    let yrHead = `<tr>`;
    let from_to_mnt = `<tr>`;
    let r = `<tr>
        <td rowspan=''>${val.property_number}</td>
        <td rowspan=''>${val.article_name}</td>
        <td rowspan=''>${val.description}</td>
        <td rowspan=''>${val.date}</td>
        <td rowspan=''>${thousands_separators(val.acquisition_amount)}</td>
        <td rowspan=''>${val.book_name}</td>
        <td rowspan=''>${thousands_separators(val.amount)}</td>
        <td rowspan=''>${thousands_separators(val.salage_value)}</td>
        <td rowspan=''>${thousands_separators(val.depreciable_amount)}</td>
        <td rowspan=''>${val.strt_mnth}</td>
        <td rowspan=''>${val.sec_lst_mth}</td>
        <td rowspan=''>${val.useful_life}</td>
        <td rowspan=''>${thousands_separators(val.mnthly_depreciation)}</td>
        <td rowspan=''>${val.lst_mth}</td>
        <td rowspan=''></td>
        <td rowspan=''>${val.depreciation_object_code}-${val.depreciation_account_title}</td>`;

    let startDate = new Date(val.strt_mnth + "-01");
    let endDate = new Date(val.lst_mth + "-01");
    let c = 0;
    let yrDiff = moment(endDate).diff(moment(startDate), "years");
    while (startDate <= endDate) {
      let year = startDate.getFullYear();
      let mnt = (startDate.getMonth() + 1).toString().padStart(2, "0");
      let f_month = c > 0 ? "01" : mnt;
      let l_month = c < yrDiff ? "12" : mnt;
      let date1 = moment(`${year}-${f_month}-01`);
      let date2 = moment(`${year}-${l_month}-01`);
      let diffmnts = date2.diff(date1, "months") + 1;
      let ttlDep = parseFloat(val.mnthly_depreciation) * diffmnts;
      r += `<td colspan='2'>${thousands_separators(ttlDep)}</td>`;
      yrHead += `<th colspan='2'>${year}</th>`;
      from_to_mnt += `<th style='min-width:80px'>${year}-${f_month}</th>`;
      from_to_mnt += `<th style='min-width:80px'>${year}-${l_month}</th>`;
      startDate.setFullYear(startDate.getFullYear() + 1);
      c++;
    }
    r += `</tr>`;
    from_to_mnt += `</tr>`;
    $("#data_tbl tbody").append(`<tr><td colspan='16' rowspan='3'></td></tr>`);
    $("#data_tbl tbody").append(yrHead);
    $("#data_tbl tbody").append(from_to_mnt);
    $("#data_tbl tbody").append(r);
  });
}
