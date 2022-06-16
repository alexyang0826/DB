function search_list(filter) {
    var querystring = "";
    if (filter['shop_name']) querystring += "shop_name=" + filter['shop_name'];
    if (filter['distance']) querystring += "&distance=" + filter['distance'];
    if (filter['price_floor']) querystring += "&price_floor=" + filter['price_floor'];
    if (filter['price_ceiling']) querystring += "&price_ceiling=" + filter['price_ceiling'];
    if (filter['meal']) querystring += "&meal=" + filter['meal'];
    if (filter['category']) querystring += "&category=" + filter['category'];
    if (filter['type']) querystring += "&type=" + filter['type'];
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("result-list").innerHTML = this.responseText;
        }
    };
    console.log(querystring);
    xhttp.open("POST", "php/search.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(querystring);
}

function load_tra() {
    $("#transaction_table tbody tr").remove();
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText) {
                var json = this.responseText;
                var data = JSON.parse(json)
                //alert(data[0]["TID"]);
                for (var i = 0; i < data.length; i++) {
                    if (document.getElementById("transaction_filter").value == "All" || document.getElementById("transaction_filter").value == data[i]['tra_action']) {
                        let row1 = document.createElement('tr');
                        let row2 = document.createElement('th');
                        let row3 = document.createElement('td');
                        let row4 = document.createElement('td');
                        let row5 = document.createElement('td');
                        let row6 = document.createElement('td');

                        row2.setAttribute('scope', 'row');
                        row2.innerHTML = data[i]["TID"];
                        row3.innerHTML = data[i]['tra_action'];
                        row4.innerHTML = data[i]['tra_time'];
                        row5.innerHTML = data[i]["trader"];
                        row6.innerHTML = data[i]["tra_price"];

                        row1.appendChild(row2);
                        row1.appendChild(row3);
                        row1.appendChild(row4);
                        row1.appendChild(row5);
                        row1.appendChild(row6);

                        document.querySelector('#transaction_table_body').appendChild(row1);
                    }
                }
            }
            //alert(this.responseText);
        }
    }
    xhttp.open("POST", "php/load_tra.php", true);
    xhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhttp.send("tra_action=" + document.getElementById("transaction_filter").value);
};