function cambiared() {
	if(document.getElementById("networks").value=="create"){
	    window.location = '../entry/create.php'
	    
	} 
	else {
		if(document.getElementById("networks").value=="birdseye"){
			window.location = '../status/noc.php'
		}
		else {
			var req;
			if (window.XMLHttpRequest)
				req = new XMLHttpRequest();
			else if (window.ActiveXObject)
				req = new ActiveXObject("Microsoft.XMLHTTP");
			req.open("POST", "../lib/change_network.php", false);
			req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			req.setRequestHeader('Cache-Control', 'private');
			var ancla = "url=" + window.location + "&net=" + document.getElementById("networks").value;
			req.send(ancla);
			if (req.status != 200) {
	  			alert("Ha habido un error. " + req.responseText);
			} else if (req.responseText.search("Error") == 0){
	  			alert(req.responseText);
			}
			if(window.location.toString().indexOf('/status/noc.php')==-1)
		 		window.location.reload()
		 	else
		 		window.location = "/status/map.php";
		}
	}

}
