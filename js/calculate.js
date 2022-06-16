function calculate(shop_name, distance) {
    //alert(distance)
    var table = document.getElementById("table" + shop_name);
    var deliver_fee = Math.max(10, Math.round(distance / 1000 * 10)); //max fee is 100
    var fee = (document.getElementById("type" + shop_name).value == "Delivery") ? deliver_fee : 0;
    //alert(document.getElementById("type"+shop_name).value)
    var total = 0;
    var tbody = "";
    var flag = false;
    for (var i = 1; i < table.rows.length; i++) {
        if (document.getElementById(shop_name + i).value != '0') {
            let price = parseInt(table.rows[i].cells[2].innerHTML);
            let quantity = parseInt(document.getElementById(shop_name + i).value);
            tbody += "<tr>";
            tbody += "<th scope='row'>" + i + "</th>";
            tbody += "<td>" + table.rows[i].cells[0].innerHTML + "</td>";
            tbody += "<td>" + table.rows[i].cells[1].innerHTML + "</td>";
            tbody += "<td>" + price + "</td>";
            tbody += "<td>" + quantity + "</td>";
            total += price * quantity;
            tbody += "</tr>";
            flag = true;
        }
    }
    if (!flag) {
        alert("No food ordered!")
        return;
    }
    if (document.getElementById("order") != null) {
        $('#order').remove();
    }
    const modal = document.createElement('div');
    modal.id = "order";
    modal.className = 'modal fade';
    modal.setAttribute('data-modal', 'true');
    modal.setAttribute('data-backdrop', 'static');
    modal.setAttribute('data-keyboard', 'true')
    //alert(shop_name);
    modal.innerHTML = `
          <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Order</h4>
                      </div>
                      <div class="modal-body">
                            <div class="row">
                              <div class="  col-xs-12">
                                <table class="table" style=" margin-top: 15px;">
                                  <thead>
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Picture</th>
                                    
                                      <th scope="col">meal name</th>
                                  
                                      <th scope="col">price</th>
                                      <th scope="col">Order Quantity</th>
                                    </tr>
                                  </thead>
                                  <tbody>`
        + tbody +
        `</tbody>
                                </table> 
                              </div>
                            </div>`
        + `<p style="text-align:left;"  class = 'sn' hidden>`+ shop_name + `</p>`
        + `<p style="text-align:left;"  class = 'Subtotal' hidden>`+ total + `</p>`
        + `<p style="text-align:left;"  class = 'Delivery_fee' hidden>`+ fee + `</p>`
        + `<p style="text-align:left;"  class = 'Total_Price' hidden>`+ (total + fee) + `</p>`
        + `<p style="text-align:right;" >Subtotal  $` + total + `</p>`
        + `<p style="text-align:right;" >Delivery fee  $` + fee + `</p>`
        + `<p style="text-align:right;" >Total Price   $` + (total + fee) + `</p>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-info" data-dismiss="modal" onclick=order()>Order</button>
                    </div>
              </div>            
            </div>
          </div>
        `;
    //alert(shop_name)
    document.querySelector('body').appendChild(modal);
    $('#order').modal('show');
}