var req;

function navigate(month,year) {

        var url = "index.php?supermode=calendar_view&month="+month+"&year="+year;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}
function navigateAdmin(month,year) {
        var url = "index.php?supermode=calendar_view&calMode=admin&month="+month+"&year="+year;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}
function eventView(id,ddate,dtime) {
        var url = "index.php?supermode=calendar_view&eventView="+id+"&ddate="+ddate+"&dtime="+dtime;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}
function eventDayView(id,ddate,dtime) {
        var url = "index.php?supermode=calendar_view&eventDayView="+id+"&ddate="+ddate+"&dtime="+dtime;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}
function eventAdminView(id,ddate,dtime) {
        var url = "index.php?supermode=calendar_view&calMode=admin&eventView="+id+"&ddate="+ddate+"&dtime="+dtime;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}

function callback() {

    let $obj=$('#calendar');
    if(req.readyState == 4) {
            if(req.status == 200) {
                    response = req.responseText;
                    $obj.html(response);
            }

            //else {
            //       alert("There was a problem retrieving the data:\n" + req.statusText);
            //}
    }


}
