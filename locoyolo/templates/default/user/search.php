<?php
include_once (TEMPPATH."/header.php");
$locationstart = $_POST['pac-input'];
$daterange = explode('-',"".$_REQUEST['daterange']."");
$enddate = $daterange[1];
$startdate = $daterange[0];
$timerange = explode('-',"".$_REQUEST['timerange']."");
$endtime = $timerange[1];
$starttime = $timerange[0];
?>
<div class="se-pre-con"></div>
<script>
function show_search_box() {
	document.getElementById("searchtable").style.display = "block";
	document.getElementById("eventlist_details").style.display = "none";
}
// JavaScript Document
//INITIALISING VARIABLES...
var firsttime = "yes";
var gmarkers = [];
var map;
var eventmarker;
var activate_marker_id = 20;
		
//CLEARING MARKERS ARRAY
function removeMarkers(){
    for(i=0; i<gmarkers.length; i++){
        gmarkers[i].setMap(null);
    	}
		gmarkers = [];		
	}
                               
function initMap() {
        // Create the map.
		 var lat = document.getElementById('maplatNEValue');
		 var lng = document.getElementById('maplongNEValue');
         
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 17,
		  center: {lat:parseFloat(document.getElementById('maplatNEValue').value), lng:parseFloat(document.getElementById('maplongNEValue').value)},
		  disableDefaultUI: true,
		  scrollwheel: false,
        });
		
		//DECLARE ALL ESSENTIAL VARIABLES FOR INITMAP()
		var input = document.getElementById('pac-input');
		var searchbutton = document.getElementById("searchbtn");
        var autocomplete = new google.maps.places.Autocomplete(input);
		var firstdaterange = document.getElementById("daterange").value;
		var firsttimerange = document.getElementById("timerange").value;
		var rightoveralldiv = document.getElementById("rightoverall");
		var getEvents = 0;
		var getPings = 0;
		var eventType = $("#type").val();
		if($("#getpings").prop("checked") == true)
		{
			getPings = '1';
		}
		var pingchecked = document.getElementById("getpings");
		if($("#getevents").prop("checked") == true)
		{
			getEvents = '1';
		}
		var eventchecked = document.getElementById("getevents");
		var latNEValue;
		var longNEValue;
		var latSWValue;
		var longSWValue;

		//NOT REALLY NEEDED. FIGURE OUT LATER...
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(1.2644602, 103.8208577)
        });
		
		var geocoder = new google.maps.Geocoder();
		//SET NEW MAP CENTRE
		var address = "<?php echo $locationstart ?>";
        geocoder.geocode({'address': address}, function(results, status) {
          if (status === 'OK') {
            map.setCenter(results[0].geometry.location);
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
		
			
		//GET BOUNDS OF MAP. EVENT BASED CODE, WHICH WORKS WHEN BOUNDS ARE CHANGED DURING INITIALISATION...
	google.maps.event.addListener(map, 'idle', function() {

		$(".se-pre-con").fadeIn();
			//PAGE NUMBER FOR RESULTS, START WITH FIRST PAGE
			currentpagenumber = 1;


			
			//GET EVENT DATE FROM VALUES OF DROPDOWNS

			
			 var latNEValue =  map.getBounds().getNorthEast().lat();
			// NorthEast Longitude : 180
			 var longNEValue = map.getBounds().getNorthEast().lng();
			 // SouthWest Latitude : -87.71179927260242
			 var latSWValue =  map.getBounds().getSouthWest().lat();
			 // Southwest Latitude :  -180
			 var longSWValue = map.getBounds().getSouthWest().lng();
			 
			 document.getElementById('maplatNEValue').value = latNEValue;
			 document.getElementById('maplongNEValue').value = longNEValue;
			 document.getElementById('maplatSWValue').value = latSWValue;
			 document.getElementById('maplongSWValue').value = longSWValue;
			 
			 //TAKE DATES FROM INITIAL TIME DROPDOWNS
		 	var firstdaterange = document.getElementById("daterange").value;
			var firsttimerange = document.getElementById("timerange").value;
			var getEvents = 0;
			var getPings = 0;
			var eventType = $("#type").val();
			if($("#getpings").prop("checked") == true)
			{
				getPings = '1';
			}
			var pingchecked = document.getElementById("getpings");
			if($("#getevents").prop("checked") == true)
			{
				getEvents = '1';
			}
			//alert(firsttimerange);
			//alert(eventdate);
			//POST BY AJAX TO PUT EVENT ICONS ON MAP
			
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=events_on_map'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange, currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 $.each(data, function(index, element) {
				eventmarker = new google.maps.Marker({
				  position: { lat: parseFloat(element.eventlat), lng: parseFloat(element.eventlng )},
				  icon: element.eventicon,
				  map: map
			  		}); 
					
					gmarkers.push(eventmarker);
		   		});
			}
			});
			
			for (var i = 0; i < gmarkers.length; i++) {
          		gmarkers[i].setMap(map);
        	}
			
			//POST BY AJAX TO DISPLAY EVENTS IN MAP LIST
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=eventslistupdate&m=2'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange, currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			//dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 if(data == 'OK') {
    				result = data;
    			} else {
    				result = data;
    			}
    			$('#eventlistdisplay').html(result);
			}
			});
		$(".se-pre-con").fadeOut("slow");
		});


//======================UPDATE MAP DISPLAY EVENTS WITH DRAG===============//		
/*
google.maps.event.addListener(map, 'dragend', function() {

	$('#jqtyp').append('<p>dragend1</p>');
	$(".se-pre-con").fadeIn();
		if (gmarkers.length !== 0){
		removeMarkers();
		}

		currentpagenumber = 1;
		
		marker.setVisible(false);
//		var eventdate = document.getElementById("event_year").value+"-"+document.getElementById("event_month").value+"-"+document.getElementById("event_day").value;
		//Get coordinates of map extreme bounds
		// NorthEast Latitude : 89.45016124669523
		 var latNEValue =  map.getBounds().getNorthEast().lat();
		// NorthEast Longitude : 180
		 var longNEValue = map.getBounds().getNorthEast().lng();
		 // SouthWest Latitude : -87.71179927260242
		 var latSWValue =  map.getBounds().getSouthWest().lat();
		 // Southwest Latitude :  -180
		 var longSWValue = map.getBounds().getSouthWest().lng();

		 document.getElementById('maplatNEValue').value = latNEValue;
		 document.getElementById('maplongNEValue').value = longNEValue;
		 document.getElementById('maplatSWValue').value = latSWValue;
		 document.getElementById('maplongSWValue').value = longSWValue;
		 
		 //Get start and end time. If not submitted, get start and end times specified by hidden text boxes.
		 
		var firstdaterange = document.getElementById("daterange").value;
		var firsttimerange = document.getElementById("timerange").value;
		var getEvents = 0;
		var getPings = 0;
		var eventType = $("#type").val();
		if($("#getpings").prop("checked") == true)
		{
			getPings = '1';
		}
		var pingchecked = document.getElementById("getpings");
		if($("#getevents").prop("checked") == true)
		{
			getEvents = '1';
		}
		$('#eventlistdisplay').css("opacity",0.5);
		//$('#map').css("opacity",0.5);
			
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=events_on_map'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange, currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 $.each(data, function(index, element) {
				eventmarker = new google.maps.Marker({
				  position: { lat: parseFloat(element.eventlat), lng: parseFloat(element.eventlng )},
				  icon: element.eventicon,
				  map: map
			  		}); 
					
					gmarkers.push(eventmarker);
		   		});

			}
			});
			
			for (var i = 0; i < gmarkers.length; i++) {
          		gmarkers[i].setMap(map);
        	}
			
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=eventslistupdate&m=3'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange, currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			//dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 if(data == 'OK') {
    				result = data;
    			} else {
    				result = data;
    			}
    			$('#eventlistdisplay').html(result);
				$('#eventlistdisplay').css("opacity",1);
				//$('#map').css("opacity",1);
			}
			});
	$(".se-pre-con").fadeOut("slow");
			
		 });
		 */
   
				
//======================CHECK WHEN SEARCH BUTTON IS CLICKED===============//		
searchbutton.addEventListener('click', function() {


		firsttime = "no";

		currentpagenumber = 1;
		
		if (gmarkers.length !== 0){
		removeMarkers();
		}
          //infowindow.close();
          marker.setVisible(false);
//		  var eventdate = document.getElementById("event_year").value+"-"+document.getElementById("event_month").value+"-"+document.getElementById("event_day").value;
		  if (autocomplete.getPlace()){
		 var place = autocomplete.getPlace();

          // If the place has a geometry, then present it on a map_mobile.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
			map.setZoom(17);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
		  
          var address = '';
		  if(place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }
		  
		  // NorthEast Latitude : 89.45016124669523
		 var latNEValue =  map.getBounds().getNorthEast().lat();
		// NorthEast Longitude : 180
		 var longNEValue = map.getBounds().getNorthEast().lng();
		 // SouthWest Latitude : -87.71179927260242
		 var latSWValue =  map.getBounds().getSouthWest().lat();
		 // Southwest Latitude :  -180
		 var longSWValue = map.getBounds().getSouthWest().lng();
			  document.getElementById('maplatNEValue').value = latNEValue;
			  document.getElementById('maplongNEValue').value = longNEValue;
			  document.getElementById('maplatSWValue').value = latSWValue;
			  document.getElementById('maplongSWValue').value = longSWValue;

		  }else{
			  var latNEValue = document.getElementById('maplatNEValue').value;
			  // NorthEast Longitude : 180
			  var longNEValue =  document.getElementById('maplongNEValue').value ;
			  // SouthWest Latitude : -87.71179927260242
			  var latSWValue =   document.getElementById('maplatSWValue').value;
			  // Southwest Latitude :  -180
			  var longSWValue = document.getElementById('maplongSWValue').value ;
		  }


		 var firstdaterange = document.getElementById("daterange").value;
		var firsttimerange = document.getElementById("timerange").value;
		var getEvents = 0;
		var getPings = 0;
		if($("#getpings").prop("checked") == true)
		{
			getPings = '1';
		}
		var pingchecked = document.getElementById("getpings");
		if($("#getevents").prop("checked") == true)
		{
			getEvents = '1';
		}
		var eventType = $("#type").val();
		$('#eventlistdisplay').css("opacity",0.5);
		//$('#map').css("opacity",0.5);
		 
		 $.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=events_on_map'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange,  currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 $.each(data, function(index, element) {
					eventmarker = new google.maps.Marker({
				  position: { lat: parseFloat(element.eventlat), lng: parseFloat(element.eventlng )},
				  icon: element.eventicon,
				  map: map
			  		}); 
					
					gmarkers.push(eventmarker);
					
					//firsttime = element.firsttimepost;
					
		   		});
			}
			});
			
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=eventslistupdate&m=4'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange,  currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			//dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 if(data == 'OK') {
    				result = data;
    			} else {
    				result = data;
    			}
    			$('#eventlistdisplay').html(result);
				$('#eventlistdisplay').css("opacity",1);
				//$('#map').css("opacity",1);
			}
			});
	$(".se-pre-con").fadeOut("slow");
        });
		

//======================UPDATE MAP DISPLAY EVENTS WITH DRAG===============//		
/*google.maps.event.addListener(map, 'dragend', function() {
	$('#jqtyp').append('<p>dragend</p>');
	$(".se-pre-con").fadeIn();
		if (gmarkers.length !== 0){
		removeMarkers();
		}
	
		currentpagenumber = 1;
		
		marker.setVisible(false);
//		var eventdate = document.getElementById("event_year").value+"-"+document.getElementById("event_month").value+"-"+document.getElementById("event_day").value;
		//Get coordinates of map extreme bounds
		// NorthEast Latitude : 89.45016124669523
		 var latNEValue =  map.getBounds().getNorthEast().lat();
		// NorthEast Longitude : 180
		 var longNEValue = map.getBounds().getNorthEast().lng();
		 // SouthWest Latitude : -87.71179927260242
		 var latSWValue =  map.getBounds().getSouthWest().lat();
		 // Southwest Latitude :  -180
		 var longSWValue = map.getBounds().getSouthWest().lng();
	//alert('latNEValue='+latNEValue+'=longNEValue'+longNEValue+'='+latSWValue+'='+longSWValue);
		var getEvents = 0;
		var getPings = 0;
		var eventType = $("#type").val();
		if($("#getpings").prop("checked") == true)
		{
			getPings = '1';
		}
		var pingchecked = document.getElementById("getpings");
		if($("#getevents").prop("checked") == true)
		{
			getEvents = '1';
		}
		 document.getElementById('maplatNEValue').value = latNEValue;
		 document.getElementById('maplongNEValue').value = longNEValue;
		 document.getElementById('maplatSWValue').value = latSWValue;
		 document.getElementById('maplongSWValue').value = longSWValue;
		 
		 //Get start and end time. If not submitted, get start and end times specified by hidden text boxes.
		 
		var firstdaterange = document.getElementById("daterange").value;
		var firsttimerange = document.getElementById("timerange").value;
		
		$('#eventlistdisplay').css("opacity",0.5);
		//$('#map').css("opacity",0.5);
			
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=events_on_map'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange, currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 $.each(data, function(index, element) {
				eventmarker = new google.maps.Marker({
				  position: { lat: parseFloat(element.eventlat), lng: parseFloat(element.eventlng )},
				  icon: element.eventicon,
				  map: map
			  		}); 
					
					gmarkers.push(eventmarker);
		   		});
			}
			});
			
			for (var i = 0; i < gmarkers.length; i++) {
          		gmarkers[i].setMap(map);
        	}
			
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=eventslistupdate&m=5'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange,  currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			//dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 if(data == 'OK') {
    				result = data;
    			} else {
    				result = data;
    			}
    			$('#eventlistdisplay').html(result);
				$('#eventlistdisplay').css("opacity",1);
				//$('#map').css("opacity",1);
			}
			});
	$(".se-pre-con").fadeOut("slow");
		 });*/

}


//=====================SHOW MORE EVENTS WITH PAGINATION=====================//
function show_more_events(currentpagenumber) {

		if (gmarkers.length !== 0){
		removeMarkers();
		}
		
//		var eventdate = document.getElementById("event_year").value+"-"+document.getElementById("event_month").value+"-"+document.getElementById("event_day").value;
		//Get coordinates of map extreme bounds
		// NorthEast Latitude : 89.45016124669523
		 var latNEValue =  document.getElementById('maplatNEValue').value;
		// NorthEast Longitude : 180
		 var longNEValue = document.getElementById('maplongNEValue').value;
		 // SouthWest Latitude : -87.71179927260242
		 var latSWValue =  document.getElementById('maplatSWValue').value;
		 // Southwest Latitude :  -180
		 var longSWValue = document.getElementById('maplongSWValue').value;

			var firstdaterange = document.getElementById("daterange").value;
		var firsttimerange = document.getElementById("timerange").value;
		var getEvents = 0;
		var getPings = 0;
		var eventType = $("#type").val();
		if($("#getpings").prop("checked") == true)
		{
			getPings = '1';
		}
		var pingchecked = document.getElementById("getpings");
		if($("#getevents").prop("checked") == true)
		{
			getEvents = '1';
		}
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=events_on_map'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange,  currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			dataType: 'json',
			cache: false,
			success: function(data)
			{ alert();
			 	 $.each(data, function(index, element) {
					eventmarker = new google.maps.Marker({
				  position: { lat: parseFloat(element.eventlat), lng: parseFloat(element.eventlng )},
				  icon: element.eventicon,
				  map: map
			  		}); 
					
					gmarkers.push(eventmarker);
		   		});
			}
			});	
			
			for (var i = 0; i < gmarkers.length; i++) {
          		gmarkers[i].setMap(map);
        	}
			
			
			//Post by ajax to display events on map
			$.ajax({
			type: "POST",
			url: "<?php echo CreateURL('index.php','mod=ajax&do=eventslistupdate&m=1'); ?>",
			data: { SWlat: latSWValue, SWlng: longSWValue, NElat: latNEValue, NElng: longNEValue, daterange: firstdaterange, timerange: firsttimerange,currentpage: currentpagenumber,getEvents:getEvents,getPings:getPings,eventType:eventType },
			//dataType: 'json',
			cache: false,
			success: function(data)
			{
			 	 if(data == 'OK') {
    				result = data;
    			} else {
    				result = data;
    			}
    			$('#eventlistdisplay').html(result);
			}
			});
		 }
//@TODO Run ajax on click these fields
		$('#getpings').click(function(){alert(); $('#searchbtn').trigger('click');});
		$('#type').change(function(){alert();$('#searchbtn').trigger('click');});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHXsI2hOfs6x7NJLR8LnN5wG-2N-ha0S8&libraries=places&callback=initMap" async defer></script>
<script type="text/javascript">
	$('#daterange').daterangepicker({
		"autoApply": true,
		"alwaysShowCalendars": true,
		"startDate": "<?php echo $startdate;?>",
		"endDate": "<?php echo $enddate;?>",
		"drops": "down",
		locale: {
			format: 'MM/DD/YYYY'
		}
	}, function(start, end, label) {
		console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
	});
	$('#timerange').daterangepicker({
		"timePicker": true,
		"autoApply": true,
		"alwaysShowCalendars": true,
		"startDate": "<?php echo $starttime;?>",
		"endDate": "<?php echo $endtime;?>",
		"drops": "down",
				locale: {
			format: 'h:mm A'
		}
	}, function(start, end, label) {
		console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
	});

	$('#timerange,#daterange').click(function(){

		$('.daterangepicker').each(function(e) {
			if(e==1)
			{
				$(this).addClass('timeonly');
			}
		});

	});
</script>

</head>

<body style="overflow:auto">
<div class=" searchpage display860" id="searchpage">
	<div class="right">
		<div class='right-container'>
			<div class='top-search-sec'>
      <h3 class="turn-to-orange find-events_heading">Find events
      </h3>
      
      <div class="has-all-search">
		<div class='search-input'>
       <div id="searcheventserror"></div><input placeholder="Where?" name="pac-input" type="text" class="textboxbottomborder" id="pac-input" style="width:100%" value="<?php echo $_POST['pac-input'];?>" />
      </div>
		  <!--        Calender-->

		  <div class='col-md-5 datefield'>
			  <div class="form-group">
				  <div class='input-group date' id='datetimepicker6'>
					  <input type='text' name="daterange" id="daterange" value="<?php //echo $_REQUEST['daterange'];?>" class="form-control" />

                </span>
				  </div>
			  </div>
		  </div>
		  <div class='col-md-5 timefield'>
			  <div class="form-group">
				  <div class='input-group date' id='datetimepicker7'>
				<input type='text'  name="timerange"  id="timerange" value="<?php //echo $_REQUEST['timerange'];?>" class="form-control" />


				  </div>
			  </div>
		  </div>
		  <script type="text/javascript">
			  $('#daterange').daterangepicker({
				  "autoApply": true,
				  "alwaysShowCalendars": true,
				  "startDate": "<?php echo $startdate;?>",
				  "endDate": "<?php echo $enddate;?>",
				  "drops": "down",
				  locale: {
					  format: 'MM/DD/YYYY'
				  }
			  }, function(start, end, label) {
				  console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
			  });
			  $('#timerange').daterangepicker({
				  "timePicker": true,
				  "autoApply": true,
				  "alwaysShowCalendars": true,
				  "startDate": "<?php echo $starttime;?>",
				  "endDate": "<?php echo $endtime;?>",
				  "drops": "down",

				  locale: {
					  format: 'h:mm A'
				  }
			  }, function(start, end, label) {
				  console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
			  });

			  $('#timerange,#daterange').click(function(){

				  $('.daterangepicker').each(function(e) {
					  if(e==1)
					  {
						  $(this).addClass('timeonly');
					  }
				  });

			  });
			  $('#getevents, #getevents').click(function(){
			  	if($(this).attr('checked'))
				{
					$(this).removeAttr('checked');
				}else
				{
					$(this).attr('checked','true');
				}
				  $('#searchbtn').click();
			  });
		  </script>

		  <input type="hidden" id="firstdaterange" value="<?php echo date("H:i:s") ?>" />
<input type="hidden" id="firsttimerange" value="<?php echo date('H:i:s', strtotime('+2 hour', strtotime(date("H:i:s")))) ?>" />
<input type="hidden" id="maplatNEValue" value='<?php echo $_POST['maplatNEValue'];?>' />
<input type="hidden" id="maplongNEValue" value='<?php echo $_POST['maplongNEValue'];?>' />
<input type="hidden" id="maplatSWValue" value='<?php echo $_POST['maplatSWValue'];?>' />
<input type="hidden" id="maplongSWValue" value='<?php echo $_POST['maplongSWValue'];?>'  />

         <input class="standardbutton" style="cursor:pointer" type="button" id="searchbtn" value="Search">
		 </div>
		  <div class="has-some-search-att">
			  <div class="col-sm-4">
				  <div class="form-group">

					  <select class="form-control" name='eventType' id="type">
						  <option value="0">All Types</option>
						  <?php
						  $types = $DB->selectRecords('event_types');
						  foreach($types as $type){?>
							  <option value="<?php echo $type->id;?>"><?php echo $type->event_type; ?></option>
						  <?php } ?>
					  </select>

				  </div>
			  </div>
			  <div class="col-sm-4">
				  <div class="checkbox">
					 <input type="checkbox"  checked="true" id="getpings" value="P"> <label for="getpings" style='padding-right:25px'>Ping</label>
					 <input type="checkbox"  checked="true"  id="getevents" value="E"> <label for="getevents" style='padding-right:25px'>Event</label>
				  </div>
			  </div>
		  </div>
        </div>
      <!--<tr>
    <td height="90" colspan="3" align="center" valign="middle">&nbsp;</td>
  </tr>-->
   </div>

  
  
  <!--------------DISPLAY EVENT LIST HERE------------->
<div id="eventlistdisplay">
</div>
  <!--------------DISPLAY EVENT LIST END------------->
</div>
    
     <div class="left">
		<div id="map" style="width:100%; height:100%;margin-top:50px"></div>
    </div>
</div>
</body>
</html>