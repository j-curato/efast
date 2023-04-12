function display(data, act_ttl, period, book_name) {
  $("#con").html("");
  $.each(data, (key, val) => {
    let rcv_by_pos = val[0]["rcv_by_pos"];
    let emp_name = key
      .toLowerCase()
      .replace(/(^|\s)\S/g, function (firstLetter) {
        return firstLetter.toUpperCase();
      });
    let tbl = `
        <table>
            <thead>
                <tr>
                    <td colspan="9" class='ctr'>  
                        <b>REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT AND EQUIPMENT</b> 
                        <br>
                        <br>
                        <b><u>${act_ttl}</u></b>
                        <br>
                        <i>(Type of Property, Plant and Equipment)</i>
                        <br>
                        <span>As At <b><u>${period}</u></b></span>
                        <br>
                        <br>
                        </td>
                    
                    </tr>
                <tr>
                    <th colspan="9">Fund Cluster: <u>${book_name}</u></th>
                </tr>
                <tr>
                    <td colspan="9">For which
                        <b><u>${emp_name}</u></b>,
                        <b><u>${rcv_by_pos}</u></b>,
                        <b><u>Department of Trade and Industry</u> </b>
                        is Accountable, having assumed such accountability on
                    </td>

                </tr>
                <tr>
                    <th class="ctr" rowspan="2">Article</th>
                    <th class="ctr" rowspan="2">Description</th>
                    <th class="ctr" rowspan="2">Property Number</th>
                    <th class="ctr" rowspan="2">Unit of Measure</th>
                    <th class="ctr" rowspan="2">Unit Value</th>
                    <th class="ctr" rowspan="2">Quantity Per Property Card</th>
                    <th colspan="2" class="ctr">Shortage/Overage</th>
                    <th class="ctr" rowspan="2">Remarks</th>

                </tr>
                <tr>
                    <th class="ctr">Quantity</th>
                    <th class="ctr">Value</th>
                </tr>

            </thead>
            <tbody>`;
    $.each(val, (i, itm) => {
      let row = `<tr>
                        <td class='ctr'>${itm["article"]}</td>
                        <td>${itm["description"]}</td>
                        <td  class='ctr'>${itm["property_number"]}</td>
                        <td class='ctr'>${itm["unit_of_measure"]}</td>
                        <td class='ctr'>${thousands_separators(
                          itm["book_val"]
                        )}</td>
                        <td class='ctr'>${itm["qty"]}</td>
                        <td></td>
                        <td></td>
                        <td>${itm["act_usr"]}</td>
                    </tr>`;
      tbl += row;
    });
    tbl += `
        <tr>
                <td colspan="9" class="ctr">
                    <div class="rpcppe-foot-wrp">
                        <div class="rpcppe-foot-col"> <br><br><br>
                            ____________________
                            <br>
                            <span> Signature over Printed Name of <br> Inventory Commitee Chair and <br> Members</span>
                        </div>
                        <div class="rpcppe-foot-col"> <br><br><br>
                        ____________________
                            <br>
                            <span> Signature over Printed Name of <br> Head of Agency/Entity or Authorized Representative</span>
                        </div>
                        <div class="rpcppe-foot-col"> <br><br><br>
                        ____________________
                            <br>
                            <span>

                                Signature over Printed Name of COA <br> representative
                            </span>
                        </div>
                    </div>
                </td>
            </tr>
        
        </tbody></table><p style='page-break-after:always;'></p>`;
    $("#con").append(tbl);
  });
}
