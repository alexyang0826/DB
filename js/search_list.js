
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

