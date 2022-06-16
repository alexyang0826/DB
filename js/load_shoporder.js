function LoadShopOrder(){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(this.responseText){
                var json = this.responseText;
                var data = JSON.parse(json);
                var idx = 0;
                for(var i=0;i<data.length;i++){
                    if(document.getElementById("ShopOrderstatus").value=="All" || document.getElementById("ShopOrderstatus").value==data[i]['status']){
                        let row1 = document.createElement('tr');
                        let row2 = document.createElement('th');
                        let row3 = document.createElement('td');
                        let row4 = document.createElement('td');
                        let row5 = document.createElement('td');
                        let row6 = document.createElement('td');
                        let row7 = document.createElement('td');
                        let row8 = document.createElement('td');
                        let row10 = document.createElement('td');
                        idx+=1;
                        if(data[i]['status']=="Not Finished"){
                            row10.innerHTML = '<input type="checkbox" id="ShopOrderBox'+idx+'"></button>';
                        }
                        else{
                            row10.innerHTML = '';
                        }
                        row1.appendChild(row10);
                        row2.setAttribute('scope', 'row');
                        row2.innerHTML=data[i]["OID"];
                        row3.innerHTML=data[i]['status'];
                        row4.innerHTML=data[i]['start'];
                        row5.innerHTML=data[i]["end"];
                        row6.innerHTML=data[i]["shop"];
                        row7.innerHTML=data[i]["price"];
                        row8.innerHTML='<button type="button" style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal" data-target="#Order'+data[i]["OID"]+'")>order details</button>';

                        row1.appendChild(row2);
                        row1.appendChild(row3);
                        row1.appendChild(row4);
                        row1.appendChild(row5);
                        row1.appendChild(row6);
                        row1.appendChild(row7);
                        row1.appendChild(row8);

                        if(data[i]['status']=="Not Finished"){
                            let row9 = document.createElement('td');
                            let row10 = document.createElement('td');

                            row9.innerHTML = '<button type="button" class="btn btn-success"  onclick=CompleteOrder(['+data[i]["OID"]+'])>Done</button>';
                            row10.innerHTML = '<button type="button" class="btn btn-danger" onclick=CancelOrder(['+data[i]["OID"]+'])>Cancel</button>';

                            row1.appendChild(row9);
                            row1.appendChild(row10);
                        }

                        document.querySelector('#ShopOrderTableContent').appendChild(row1);

                        if(document.getElementById("Order"+data[i]["OID"])!=null){
                            $('#Order'+data[i]["OID"]).remove();
                        }

                        var detail = JSON.parse(data[i]["detail"]);

                        const modal = document.createElement('div');
                        modal.id = "Order"+data[i]["OID"];
                        modal.className = 'modal fade';
                        modal.setAttribute('data-modal', 'true');
                        modal.setAttribute('data-backdrop', 'static');
                        modal.setAttribute('data-keyboard','true');
                        var tbody = "";
                        var table = document.getElementById('table6');
                        for(var j=1;j<detail.length;j++){
                            tbody += "<tr>";
                            tbody += "<th scope='row'>"+j+"</th>";
                            tbody += "<td>"+detail[j]["img"]+"</td>";
                            tbody += "<td>"+detail[j]["meal"]+"</td>";
                            tbody += "<td>"+detail[j]["price"]+"</td>";
                            tbody += "<td>"+detail[j]["quantity"]+"</td>";
                            tbody += "</tr>";
                        }
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
                            +tbody+
                            `</tbody>
                                  </table> 
                                </div>
                              </div>`
                            +`<p>Subtotal  $`+detail[0]["subtotal"]+`</p>`
                            +`<p>Delivery fee  $`+detail[0]["deliver_fee"]+`.</p>`
                            +`<p>Total Price   $`+detail[0]["total"]+`</p>
                        </div>            
                      </div>
                    </div>
                  `;
                        document.querySelector('body').appendChild(modal);
                    }
                }
            }
        }
    };
    xhttp.open("POST", "load_ShopOrder.php", true);
    xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhttp.send();
}