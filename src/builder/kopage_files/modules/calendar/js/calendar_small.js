var req;

function navigate(month,year) {
        var url = "index.php?supermode=calendar_view&small=1&month="+month+"&year="+year;
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
        var url = "index.php?supermode=calendar_view&small=1&calMode=admin&month="+month+"&year="+year;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject){
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.onreadystatechange = callback;
        req.send(null);
}
function eventView(id,ddate,dtime) {
        var url = "index.php?supermode=calendar_view&small=1&eventView="+id+"&ddate="+ddate+"&dtime="+dtime;
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.actionType=1;
        req.onreadystatechange = callback;
        req.send(null);
}
function eventDayView(id,ddate,dtime){
        var url = "index.php?supermode=calendar_view&small=1&eventDayView="+id+"&ddate="+ddate+"&dtime="+dtime;
		
        if(window.XMLHttpRequest) {
                req = new XMLHttpRequest();
        } else if(window.ActiveXObject) {
                req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.open("GET", url, true);
        req.actionType=1;
        req.onreadystatechange = callback;
        req.send(null);
}
function eventAdminView(id,ddate,dtime) {
        var url = "index.php?supermode=calendar_view&small=1&calMode=admin&eventView="+id+"&ddate="+ddate+"&dtime="+dtime;
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


        
		if(req.readyState == 4) {
				
			// in "small" version, item is not displayed in the same place as calendar...
			
			if(req.actionType==1){
			jQuery("#cCalendarPlus_").html(req.responseText);
			jQuery("#cCalendarPlus").fadeIn();
			
			return
			}
			objXX = document.getElementById("calendar");
		
			if(objXX == null)
			return false;
	
			if(req.status == 200) {
				
					response = req.responseText;
					objXX.innerHTML = response;
			} else {
					alert("There was a problem retrieving the data:\n" + req.statusText);
			}
        }
}
