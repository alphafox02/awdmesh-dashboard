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
		}
		else if(form.form_name.value == "addNode")
		{
			// good, so add
			var point = new google.maps.LatLng(form.latitude.value, form.longitude.value);

			var marker = new nodeMarker(map, form.net_name.value, point, form.node_name.value, form.description.value, form.mac.value, "0", "1700", "1", "true", "0");
			marker.addTab("newnode","<br>Refresh the page to display node info.<br>The nodes checkin every 5 min.");
			marker.addListeners();
			//map.addOverlay(marker.get());
		}
		closeInfoWindow();
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
		
		closeInfoWindow();
		
		if (req.status != 200) {
  			alert("" + req.responseText);
		}

		// Must remove the marker now too!
		//map.removeOverlay(gmarker);  // global - last clicked marker
		gmarker.setMap(null);
	}

	//
	// Select the right icon based upon # users and
	//
	function createIcon(metric, gateway, up, users)
	{
		var baseIcon = {};

		if (!isNumeric(users))
		  users = 0;
		if (users > 9) users = 10;

		switch (users)
		{
			case "0":
				baseIcon.size = new google.maps.Size(24, 24);
				break;

			case "1":
			case "2":
			case "3":
				baseIcon.size = new google.maps.Size(26, 26);
				break;

			case "4":
			case "5":
			case "6":
				baseIcon.size = new google.maps.Size(28, 28);
				break;

			case "7":
			case "8":
			case "9":
				baseIcon.size = new google.maps.Size(30, 30);
				break;

			case 10:
				baseIcon.size = new google.maps.Size(34, 34);
				break;

			default:
				baseIcon.size = new google.maps.Size(24, 24);
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
                baseIcon.url = "../lib/images/gw-gy-" + users + ".png";
                //baseIcon.transparent = "../lib/images/gw-gy-" + users + ".png";
            }
            else
            {
                baseIcon.url = "../lib/images/rr-gy-" + users + ".png";
                //baseIcon.transparent = "../lib/images/rr-gy-" + users + ".png";
            }
		}

		// BAD is outage in last hour or high metric/hops
		else if (metric > 7000)
		{
            if (gateway > 0)
            {
                baseIcon.url = "../lib/images/gw-rd-" + users + ".png";
                //baseIcon.transparent = "../lib/images/gw-rd-" + users + ".png";
            }
            else
            {
                baseIcon.url = "../lib/images/rr-rd-" + users + ".png";
                //baseIcon.transparent = "../lib/images/rr-rd-" + users + ".png";
            }
		}

		// CAUTION is high daily outage, high hops, or moderately high metric/hops
		else if (metric > 4000)
		{
            if (gateway > 0)
            {
                baseIcon.url = "../lib/images/gw-yw-" + users + ".png";
                //baseIcon.transparent = "../lib/images/gw-yw-" + users + ".png";
            }
            else
            {
                baseIcon.url = "../lib/images/rr-yw-" + users + ".png";
                //baseIcon.transparent = "../lib/images/rr-yw-" + users + ".png";
            }
		}

		// GOOD
		else
		{
            if (gateway > 0 && metric > 254)
            {
                baseIcon.url = "../lib/images/gw-gr-" + users + ".png";
                //baseIcon.transparent = "../lib/images/gw-gr-" + users + ".png";
            }
            else
            {
                baseIcon.url = "../lib/images/rr-gr-" + users + ".png";
                //baseIcon.transparent = "../lib/images/rr-gr-" + users + ".png";
            }
		}

		//baseIcon.shadow = null;

		baseIcon.anchor = new google.maps.Point(10, 10);
		//baseIcon.infoWindowAnchor = new GPoint(10, 1);

		//var icon = new Icon(baseIcon);
		return baseIcon;
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
	function drawRoutePolyline(latitude, longitude, lat2, long2, metric, map)
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


		var polyline = new google.maps.Polyline(
			{
				strokeColor:RGB,
				strokeOpacity:0.1,
				strokeWeight:width,
				path: [new google.maps.LatLng(latitude, longitude), new google.maps.LatLng(lat2, long2)]
				//{title:width} - is not supported
			});


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



		polyline.setMap(map);
		return polyline;
	}
	var infoWindow = null;
	function closeInfoWindow()
	{
		if(infoWindow) infoWindow.close();
		
		if(gmarker && gmarker.infoBubble && gmarker.infoBubble.isOpen())
			gmarker.infoBubble.close();
	}
	//
	// Creates a node marker
	//
	var markerIsCliked = false;
	function nodeMarker(map, net_name, point, name, description, mac, gateway, metric, up, draggable, users, ip, gw_route, nbs)
	{
		var icon = createIcon(metric, gateway, up, users);
		var infoBubble = new InfoBubble({
				maxWidth: 600,
				minWidth:400,
				minHeight:250,
				maxHeight:500,
				shadowStyle: 1,
				padding: 15,
				backgroundColor: '#FFFFFF',
				borderRadius: 3,
				arrowSize: 20,
				disableAutoPan: false,
				hideCloseButton: false,
				arrowPosition: 50,
				arrowStyle: 0
		});
			
        var marker = new google.maps.Marker(
			{
				position: point,
				icon:icon, 
				draggable: (draggable==""||draggable==null ) ? false : true, 
				title:name
			});
		marker.infoBubble = infoBubble;	
		var infoTabs = new Array();

		markers[j] = marker;
		gmarker = marker;

		this.point = point;
        points[j] = point;
    	ips[j++] = ip;

    	function addTab(label, content){
			infoTabs[infoTabs.length] = {label:label, content:content};
			if(label != "" && content != "")
				infoBubble.addTab(label, "<div>" + content + "</div>");
				
			return true;
    	};
		
		function removePolylines()
		{
			if (polyline.length > 0) {
            	for (var i=0; i<polyline.length; i++) {
					//map.removeOverlay(polyline[i]);
					polyline[i].setMap(null);
					polyline[i] = null;
				}
				polyline = new Array();
			}
		}
		
    	function addListeners(){
	   		// Show this marker's info when it is clicked
			google.maps.event.addListener(marker, "click", function()
			{
				markerIsCliked = true;
				
                var items = nbs ? nbs.split(";") : new Array();

                removePolylines();
                //var mpoint = new google.maps.Point();
                //mpoint.x = point.x
                //mpoint.y = point.y
                count = 0;

				for (var i=0; i<(items.length); i++)
				{
					for (var z=0; z<ips.length; z++) {
						s = ips[z];
						if (s == items[i]) {
							polyline[count] = new google.maps.Polyline(
							{
								strokeColor:"#0000FF",
								strokeOpacity:0.2,
								strokeWeight:5,
								path: [new google.maps.LatLng(point.lat(), point.lng()), new google.maps.LatLng(points[z].lat(), points[z].lng())],
								map:map
							});
							
							//map.addOverlay(polyline[count]);  // on map, this fails and aborts the loop!  Works on add!
							count++;
					   //     mpoint.x = points[z].x
					   //     mpoint.y = points[z].y
							break;
						}
					}

				}
			});
			google.maps.event.addListener(marker, "dblclick", function()
			{
				if(infoTabs.length>0){
					closeInfoWindow();
					infoBubble.open(map, marker);
					//marker.openInfoWindowTabsHtml(infoTabs);
				} else {
					//nothing!
				}
				gmarker = marker;
			});
			google.maps.event.addListener(marker, "dragstart", function() {
				closeInfoWindow();
			});

			//handles the behavior for dragging
			var req;
			google.maps.event.addListener(marker, "dragend", function() {
				var pDrop = marker.getPosition();
				if (window.XMLHttpRequest)
					req = new XMLHttpRequest();
				else if (window.ActiveXObject)
					req = new ActiveXObject("Microsoft.XMLHTTP");
				req.open("POST", "c_addnode.php", false);
				req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				req.setRequestHeader('Cache-Control', 'private');
				var encoded = "mac=" + mac + "&net_name=" + net_name + "&name=" + name + "&description=" + description + "&latitude=" + pDrop.lat() + "&longitude=" + pDrop.lng();
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

			google.maps.event.addListener(marker, "mouseover", function() {
                var items = gw_route ? gw_route.split(",") : new Array();
                
                removePolylines();
				
               var mpoint = new google.maps.LatLng(point.lat(), point.lng());
               // mpoint.x = point.x
               // mpoint.y = point.y
                count = 0;
                if (gateway != 1) // && items.length >= 6)
                    {
                        for (var i=0; i<(items.length); i++)
                            {
                                for (var z=0; z<ips.length; z++) {
                                    s = ips[z];
                                    if (s == items[i]) {
										polyline[count] = new google.maps.Polyline(
											{
												strokeColor:"#00FF00",
												strokeOpacity:0.85,
												strokeWeight:5,
												path: [new google.maps.LatLng(mpoint.lat(), mpoint.lng()), new google.maps.LatLng(points[z].lat(), points[z].lng())],
												map:map
											});
                                       // polyline[count] = (new GPolyline([new GLatLng(mpoint.y, mpoint.x), new GLatLng(points[z].y, points[z].x)], "#00FF00", 5, 0.85));
                                       // map.addOverlay(polyline[count]);  // on map, this fails and aborts the loop!  Works on add!
                                        count++;
                                        //mpoint.x = points[z].x
                                        //mpoint.y = points[z].y
										mpoint = new google.maps.LatLng(points[z].lat(), points[z].lng());
                                        break;
                                    }
                                }

                            }
                    }


			});
		}


		this.addTab = addTab;
		this.addListeners = addListeners;

		// add marker to map
		marker.setMap(map);
	}

	//FROM rnmap.js -- NOT YET RELEASED
	function myClick(mac)
	{
        for (var i=0; i<ips.length; i++)
            {
                s =  ips[i].replace(/\./g, "0");
                if (s == mac) {
	                google.maps.event.trigger(markers[i], "dblclick");
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
		geocoder.geocode({address: address}, function(results) {
				if (results && results.length > 0) {
					var pos = results[0].geometry.location;
					map.setCenter(pos);
					map.setZoom(15);
				} else {
					//alert("We weren't able to find your default location, so this is our best guess."
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
			var rectBounds = new google.maps.LatLngBounds(new google.maps.LatLng(minY, minX), new google.maps.LatLng(maxY, maxX));
			map.fitBounds(rectBounds);
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
				//var rectBounds = new GLatLngBounds(new GLatLng(42.3584308, -71.0597732), new GLatLng(42.3584308, -71.0597732));
				//map.setCenter(rectBounds.getCenter(), 13);  //-1
				var rectBounds = new google.maps.LatLngBounds(new google.maps.LatLng(42.3584308, -71.0597732), new google.maps.LatLng(42.3584308, -71.0597732));
				map.fitBounds(rectBounds);
			}
// End changes ---
		}
	}
	
	// create options for circle
	function getCircleOptions(map, center, radius, strokeColor, strokeWidth, strokeOpacity, fillColor, fillOpacity) {
		var circleOptions = {
			strokeColor: strokeColor,
			strokeOpacity: strokeOpacity,
			strokeWeight: strokeWidth,
			fillColor: fillColor,
			fillOpacity: fillOpacity,
			map: map,
			center: center,
			clickable: true,
			zIndex: 1000,
			radius: parseInt(radius)
		};
		return circleOptions;
	}


