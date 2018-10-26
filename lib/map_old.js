var gmarker = null;
var count=0;
var j=0;
var polyline = new Array();
var markers = new Array();
var ips = new Array();
var points = new Array();

	function isNumeric(strString)
	{
	   var strValidChars = "0123456789.-";
	   var strChar;
	   var blnResult = true;

		if (strString.length == 0) return false;

	   //  test strString consists of valid characters listed above
		for (i = 0; i < strString.length && blnResult == true; i++)
		{
	      strChar = strString.charAt(i);
	      if (strValidChars.indexOf(strChar) == -1)
	      {
				blnResult = false;
	      }
		}
		return blnResult;
   }

	//
	// Called from Add Node Form (add.php)
	//
	function addNode(form)
	{
		if(form.form_name.value=="basicEdit" || "addNode"){
			if(form.user_type.value=="user"){
				var text;
				if(form.owner_name.value=="" || form.owner_email.value=="" || form.owner_phone.value=="" || form.owner_address.value==""){
					text = "Enter name, email, phone and address of the owner node.";
				}
				if(form.node_name.value==""){
					text = "You must enter at least a node name.";
				}
				if(form.mac.value==""){
					text = "You must enter the MAC in the form of xx: xx: xx: xx: xx: xx.";
				}
				if(text){
					alert(text);
					return;
				}
			}
			else {
				var text;
				if(form.node_name.value==""){
					text = "You must enter at least a node name.";
				}
				if(form.mac.value==""){
					text = "You must enter the MAC in the form of xx: xx: xx: xx: xx: xx.";
				}
				if(text){
					alert(text);
					return;
				}
			}
		}

		var req;

		//
		// check the IP/MAC field to see if user entered an IP or MAC address
		//
      	var items = form.mac.value.split(".");
      	var items2 = form.mac.value.split(":");


		if (items.length != 4 && items2.length != 6)
		{
			var mac="00";

			//
			// user entered MAC no colons (FON, Accton) so convert it...
			//
			for (var i = 2; i <12; i+=2)
			{
				var hex = form.mac.value.substr(i, 2);
				mac = mac + ':' + hex;
			}

		} else if (items2.length == 6) {
			//
			// User entered MAC with colons (Meraki Mini)
			//
			for (var i = 9; i < 17; i+=3)
			mac = form.mac.value;

		}
//		else if (items2.length == 3) {
//			//
//			// User entered only 3-digit MAC (NetEquality Wallplug)
//			// Note from Shaddi: I got rid of the references to ip here in favor
//			// of mac, but I don't know how to properly generate the mac. for now,
//			// we leave this out.
//			mac = "00:18:0A" // always Meraki
//			for (var i = 0; i < 8; i+=3)
//			{
//				mac = mac + ':' + hex;
//			}
//		}

		if (window.XMLHttpRequest)
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");
		req.open("POST", "c_addnode.php", false);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.setRequestHeader('Cache-Control', 'private');
		var encoded = "mac=" + mac + "&net_name=" + form.net_name.value + "&form_name=" + form.form_name.value;
		if(form.node_name){encoded += "&name=" + form.node_name.value;}
		if(form.description){encoded += "&description=" + form.description.value;}
		if(form.latitude){encoded += "&latitude=" + form.latitude.value;}
		if(form.longitude){encoded += "&longitude=" +form.longitude.value;}
		if(form.owner_name){encoded += "&owner_name=" + form.owner_name.value;}
		if(form.owner_email){encoded += "&owner_email=" + form.owner_email.value;}
		if(form.owner_phone){encoded += "&owner_phone=" +	form.owner_phone.value;}
		if(form.owner_address){encoded += "&owner_address=" + form.owner_address.value;}
		req.send(encoded);
		if (req.status != 200) {
  			alert("" + req.responseText);
		} else if (req.responseText.search("Error") == 0){
  			alert(req.responseText);
		}else if(form.form_name.value == "addNode")
		{
			// good, so add
			var point = new GPoint(form.longitude.value, form.latitude.value);

			var marker = new nodeMarker(map, form.net_name.value, point, form.node_name.value, form.description.value, form.mac.value, "0", "1700", "1", "true", "0");
			marker.addTab("newnode","<br>Refresh the page to display node info.<br>The nodes checkin every 5 min.");
			marker.addListeners();
			map.addOverlay(marker.get());
		}

		map.closeInfoWindow();
	}

	//
	// No popups on nodes in this map, so this isn't called right now...
	//
	function deleteNode(form)
	{
		if(!confirm('Are you sure?\n\n')) {
			return false;
		}

		var req;
		if (window.XMLHttpRequest)
			req = new XMLHttpRequest();
		else if (window.ActiveXObject)
			req = new ActiveXObject("Microsoft.XMLHTTP");
		req.open("POST", "c_deletenode.php", false);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.setRequestHeader('Cache-Control', 'private');
		var encoded = "mac=" + form.mac.value + "&net_name=" + form.net_name.value + "&id=" + form.id.value;
		req.send(encoded);
		map.closeInfoWindow();
		if (req.status != 200) {
  			alert("" + req.responseText);
		}

		// Must remove the marker now too!
		map.removeOverlay(gmarker);  // global - last clicked marker
	}

	//
	// Select the right icon based upon # users and
	//
	function createIcon(metric, gateway, up, users)
	{
		var baseIcon = new GIcon();

		if (!isNumeric(users))
		  users = 0;
		if (users > 9) users = 10;

		switch (users)
		{
			case "0":
				baseIcon.iconSize = new GSize(24, 24);
				break;

			case "1":
			case "2":
			case "3":
				baseIcon.iconSize = new GSize(26, 26);
				break;

			case "4":
			case "5":
			case "6":
				baseIcon.iconSize = new GSize(28, 28);
				break;

			case "7":
			case "8":
			case "9":
				baseIcon.iconSize = new GSize(30, 30);
				break;

			case 10:
				baseIcon.iconSize = new GSize(34, 34);
				break;

			default:
				baseIcon.iconSize = new GSize(24, 24);
				break;
		}

		//
		// The order of these is important!
		//
		// DOWN is no data is last 25 minutes
		if (up > 1800 || metric < 0)
		{
            if (gateway > 0)
            {
                baseIcon.image = "../lib/images/gw-gy-" + users + ".png";
                baseIcon.transparent = "../lib/images/gw-gy-" + users + ".png";
            }
            else
            {
                baseIcon.image = "../lib/images/rr-gy-" + users + ".png";
                baseIcon.transparent = "../lib/images/rr-gy-" + users + ".png";
            }
		}

		// BAD is outage in last hour or high metric/hops
		else if (metric > 7000)
		{
            if (gateway > 0)
            {
                baseIcon.image = "../lib/images/gw-rd-" + users + ".png";
                baseIcon.transparent = "../lib/images/gw-rd-" + users + ".png";
            }
            else
            {
                baseIcon.image = "../lib/images/rr-rd-" + users + ".png";
                baseIcon.transparent = "../lib/images/rr-rd-" + users + ".png";
            }
		}

		// CAUTION is high daily outage, high hops, or moderately high metric/hops
		else if (metric > 4000)
		{
            if (gateway > 0)
            {
                baseIcon.image = "../lib/images/gw-yw-" + users + ".png";
                baseIcon.transparent = "../lib/images/gw-yw-" + users + ".png";
            }
            else
            {
                baseIcon.image = "../lib/images/rr-yw-" + users + ".png";
                baseIcon.transparent = "../lib/images/rr-yw-" + users + ".png";
            }
		}

		// GOOD
		else
		{
            if (gateway > 0 && metric > 254)
            {
                baseIcon.image = "../lib/images/gw-gr-" + users + ".png";
                baseIcon.transparent = "../lib/images/gw-gr-" + users + ".png";
            }
            else
            {
                baseIcon.image = "../lib/images/rr-gr-" + users + ".png";
                baseIcon.transparent = "../lib/images/rr-gr-" + users + ".png";
            }
		}

		baseIcon.shadow=null;

		baseIcon.iconAnchor = new GPoint(10, 10);
		baseIcon.infoWindowAnchor = new GPoint(10, 1);

		var icon = new GIcon(baseIcon);
		return icon;
	}

	//set the color of the route
	function setRouteColor(metric)
	{
  var RGB;
		if (metric > 120)
			RGB = "#008000";  // green
		else if (metric > 90)
			RGB = "#FFFF00";  // yellow
		else
            RGB = "#808080";  // grey
//			RGB = "E01D49";  // red
//		else RGB = 0; // flag for no polyline

		return RGB;
	}

	//draw the node route lines for a marker
	function drawRoutePolyline(latitude, longitude, lat2, long2, metric)
	{
        var RGB;
        RGB = setRouteColor(metric);
//		alert (RGB);
//		var RGB="#1e2f10"; //setRouteColor(metric);
		var width;

//		if (metric < 10)
//			return 0;
//		if (metric < 14)
//			width = 1;
//		if (metric < 22)
        if (metric < 90)
		  width = 5;
		else
		  width = 7;



		var polyline = (new GPolyline([new GLatLng(latitude, longitude), new GLatLng(lat2, long2)], RGB, width, 0.1, {title:width}));


//			GEvent.addListener(polyline, "mouseover", function()
//			{
//                polyline.color = "#00FF00";
//                polyline.opacity = 10
//                polyline.redraw(true);

//			});
// 			GEvent.addListener(polyline, "mouseout", function()
//			{
//                polyline.color = "#808080";
//                polyline.opacity = 0.1
//                polyline.redraw(true);

//			});




		return polyline;



	}

	//
	// Creates a node marker
	//
	function nodeMarker(map, net_name, point, name, description, mac, gateway, metric, up, draggable, users, ip, gw_route, nbs)
	{
		var icon = createIcon(metric, gateway, up, users);
        var marker = new GMarker(point, {icon:icon, draggable:draggable, title:name});
		var infoTabs = new Array();

		markers[j] = marker;
		gmarker = marker;

		this.point = point;
        points[j] = point;
    	ips[j++] = ip;

    	function addTab(label,content){
			infoTabs[infoTabs.length] = new GInfoWindowTab(label,content);
			return true;
    	};

    	function addListeners(){
	   		// Show this marker's info when it is clicked
			GEvent.addListener(marker, "click", function()
			{
                var items = nbs.split(";");

                if (polyline.length > 0) {
                    for (var i=0; i<polyline.length; i++) {
                        map.removeOverlay(polyline[i]);
                    }
                }
                var mpoint = new GPoint();
                mpoint.x = point.x
                mpoint.y = point.y
                count = 0;

                        for (var i=0; i<(items.length); i++)
                            {
                                for (var z=0; z<ips.length; z++) {
                                    s = ips[z];
                                    if (s == items[i]) {
                                        polyline[count] = (new GPolyline([new GLatLng(mpoint.y, mpoint.x), new GLatLng(points[z].y, points[z].x)], "#0000FF", 5, 0.2));
                                        map.addOverlay(polyline[count]);  // on map, this fails and aborts the loop!  Works on add!
                                        count++;
                                   //     mpoint.x = points[z].x
                                   //     mpoint.y = points[z].y
                                        break;
                                    }
                                }

                            }

			});
			GEvent.addListener(marker, "dblclick", function()
			{

                gmarker = marker;
				if(infoTabs.length>0){
					marker.openInfoWindowTabsHtml(infoTabs);
				} else {
					//nothing!
				}

			});
			GEvent.addListener(marker, "dragstart", function() {
				map.closeInfoWindow();
			});

			//handles the behavior for dragging
			var req;
			GEvent.addListener(marker, "dragend", function() {
				var pDrop = marker.getPoint();
				if (window.XMLHttpRequest)
					req = new XMLHttpRequest();
				else if (window.ActiveXObject)
					req = new ActiveXObject("Microsoft.XMLHTTP");
				req.open("POST", "c_addnode.php", false);
				req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				req.setRequestHeader('Cache-Control', 'private');
				var encoded = "mac=" + mac + "&net_name=" + net_name + "&name=" + name + "&description=" + description + "&latitude=" + pDrop.y + "&longitude=" + pDrop.x;
				req.send(encoded);
				if (req.status != 200) {
		  			alert("" + req.responseText);
				}

			});

//			GEvent.addListener(marker, "mouseout", function() {
//	      		var items = gw_route.split(",");
//	      		for (var i=0; i<ips.length; i++) {
//				    map.removeOverlay(polyline[i]);
//	      		}
//			});

			GEvent.addListener(marker, "mouseover", function() {
                var items = gw_route.split(",");
                
                if (polyline.length > 0) {
                    for (var i=0; i<polyline.length; i++) {
                        map.removeOverlay(polyline[i]);
                    }
                }
                var mpoint = new GPoint();
                mpoint.x = point.x
                mpoint.y = point.y
                count = 0;
                if (gateway != 1) // && items.length >= 6)
                    {
                        for (var i=0; i<(items.length); i++)
                            {
                                for (var z=0; z<ips.length; z++) {
                                    s = ips[z];
                                    if (s == items[i]) {
                                        polyline[count] = (new GPolyline([new GLatLng(mpoint.y, mpoint.x), new GLatLng(points[z].y, points[z].x)], "#00FF00", 5, 0.85));
                                        map.addOverlay(polyline[count]);  // on map, this fails and aborts the loop!  Works on add!
                                        count++;
                                        mpoint.x = points[z].x
                                        mpoint.y = points[z].y
                                        break;
                                    }
                                }

                            }
                    }


			});
		}

		function get(){return marker;}

		this.addTab = addTab;
		this.addListeners = addListeners;
		this.get = get;
	}

	//FROM rnmap.js -- NOT YET RELEASED
	function myClick(mac)
	{
        for (var i=0; i<ips.length; i++)
            {
                s =  ips[i].replace(/\./g, "0");
                if (s == mac) {
	                GEvent.trigger(markers[i],"dblclick");
                }
	        }
	}

	function setMapSizePos()
	{
	  var hBody = document.body.clientHeight;
		var hTop = document.getElementById("top").offsetHeight;
		var left = document.getElementById("top").offsetLeft;

		//document.getElementById("map").style.height = hBody - hTop + "px";
		//document.getElementById("map").style.marginLeft = 0 + "px";
	}

  function showAddress(address)
  {
    if (geocoder)
    {
      geocoder.getLatLng(address, function(point)
      {
          if (!point)
          {
            //alert("We weren't able to find your default location, so this is our best guess.");
          }
          else
          {
            map.setCenter(point, 15);
          }
      }
      );
    }
  }


	function myCenterAndZoom(map, minY, maxY, minX, maxX, nodeloc)
//BUG1: In addnode.php, the call to myCenterAndZoom is:
// myCenterAndZoom(map, $minX, $maxX, $minY, $maxY, "$node_loc");
// Note how the parameters are mismatched...not sure what the correct
// change should be...
// seanyliu, MIT '10
	{
		setMapSizePos();
		if ((minX + maxX + minY + maxY) != 0)
		{
			var rectBounds = new GLatLngBounds(new GLatLng(minY, minX), new GLatLng(maxY, maxX));
			map.setCenter(rectBounds.getCenter(), map.getBoundsZoomLevel(rectBounds));  //-1
		}
		else
		{
			//showAddress(nodeloc);
// Begin changes ----
// If the nodeloc is empty, then the map will fail to
// initialize to a location.  So, just add a simple check
// and default a location, to say, MIT.
// seanyliu, MIT '10
			if (nodeloc != "") {
				showAddress(nodeloc);
			} else {
				var rectBounds = new GLatLngBounds(new GLatLng(42.3584308, -71.0597732), new GLatLng(42.3584308, -71.0597732));
				map.setCenter(rectBounds.getCenter(), 13);  //-1
			}
// End changes ---
		}
	}


