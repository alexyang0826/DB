function order() {

    var page = document.getElementById('order');
    var shop_name = page.getElementsByClassName('sn').item(0).innerHTML;
    var Subtotal = page.getElementsByClassName('Subtotal').item(0).innerHTML;
    var Delivery_fee = page.getElementsByClassName('Delivery_fee').item(0).innerHTML;
    var Total_Price = page.getElementsByClassName('Total_Price').item(0).innerHTML;
    var table = document.getElementById("table"+shop_name);

    var detail = [];
    detail.push({})
    detail[0]['deliver_fee'] = Delivery_fee;
    detail[0]['subtotal'] = Subtotal;
    detail[0]['total'] = Total_Price;
    detail[0]['shop_name'] = shop_name;
    var index = 1;
    var total = 0;
    for (var i = 1; i < table.rows.length; i++) {
        if (document.getElementById(shop_name + i).value != '0') {
            let price = parseInt(table.rows[i].cells[3].innerHTML);
            let quantity = parseInt(document.getElementById(shop_name + i).value);
            total += price * quantity;
            detail.push({});
            detail[index]['meal'] = table.rows[i].cells[1].innerHTML;
            detail[index]['price'] = table.rows[i].cells[2].innerHTML;
            detail[index]['quantity'] = quantity;
            index += 1;
        }
    }
    //alert(detail[1]);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            alert(this.responseText);
            location.reload();
        }
    };
    xhttp.open("POST", "php/order_meals.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("detail=" + encodeURIComponent(JSON.stringify(detail)));
}