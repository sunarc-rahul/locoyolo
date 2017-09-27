<?php
include_once (TEMPPATH."/header.php");

?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHXsI2hOfs6x7NJLR8LnN5wG-2N-ha0S8&libraries=places&callback=initMap" async defer></script>
<script>


function initMap() {

var input = document.getElementById('pac-input');
var autocomplete = new google.maps.places.Autocomplete(input);

var input_mobile = document.getElementById('pac-input_mobile');
var autocomplete_mobile = new google.maps.places.Autocomplete(input_mobile);

var mapOptions = {
    zoom: 13,
    center: new google.maps.LatLng(1.352083, 103.819836),
    mapTypeId: google.maps.MapTypeId.ROADMAP,
	disableDefaultUI: true,
	scrollwheel: false,
  }
 
  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

var searchbutton  = document.getElementById("searchbtnstart");
searchbutton.addEventListener('click', function() {
		 if (autocomplete.getPlace()){
		  var place = autocomplete.getPlace();
			 $(".se-pre-con").fadeIn();
		  }else{
			  alert("Please enter a valid location.");
             return false;
		  }
          // If the place has a geometry, then present it on a map_mobile.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
			map.setZoom(17);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          //marker.setPosition(place.geometry.location);
          //marker.setVisible(true);
			
          var address = '';
          if (place.address_components) {
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
		 var firststarttime = document.getElementById("daterange").value;
    	var firstendtime = document.getElementById("timerange").value;
        if (autocomplete.getPlace())
        {   setTimeout(function(){

            $('#searchbtnstart').attr('type','submit');
            $('#searchbtnstart').click();

        },100);
        }

});}
</script>
<style>
#map-canvas {
  height: 100%;
  position: absolute; 
  top: 0; 
  bottom: -200px; 
  left: 0; 
  right: 0; 
  z-index: -1;
}
#container {
  margin: 0 auto;
  background:white;
  border-radius:3px;
  width:680px;
  padding:20px;
  	/*border: solid 1px #E1E1E1; */
	box-shadow: 0 0 2px rgba(0,0,0,0.2); 
	-moz-box-shadow: 0 0 2px rgba(0,0,0,0.2); 
	-webkit-box-shadow: 0 0 2px rgba(0,0,0,0.2); 
	-o-box-shadow: 0 0 2px rgba(0,0,0,0.2);
}
}
@media screen and (max-width:680px) {
	.display860{
		display:none;
	}
	.display320{
		display:block;
	}
}

@media screen and (min-width:680px) {
	.display860{
		display:block;
	}
	.display320{
		display:none;
	}
}
.col-sm-7.searchsec {
    background: #fff none repeat scroll 0 0;
    float: none;
    margin: auto;
    top: 100px;
    /*overflow: hidden;*/
    padding: 7px;
    height: 270px;

}
.calendar.right,.calendar.left{margin-top:0!important;}

.table-condensed > tbody > tr > td, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > td, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > thead > tr > th {
    padding: 3px;
}
.textboxbottomborder {
    margin-bottom: 10px;
}
</style>

<div class='container event_search_start'>
	<div class='row'>
		<div id='map-canvas'></div>
		<div class='col-sm-7 searchsec' id="has-main-search-form">
			<h1 class="ArialVeryDarkGrey30">Search for events on<span class="logo-colored-text"> LocoYolo</span></h1>
			<p class='ArialVeryDarkGrey18' >Whether you're at home or at work, there is always something to do near you!</p>
			
			<div class="col-sm-12 tableorangeborder searchwrp">
				<h3 class="ArialOrange18">Find events</h3>
				<form method="post" action="<?php echo CreateURL('index.php');?>" id='searchfrm'>
					<div id="searcheventserror"></div>
				<div class="search-form-wrapper" style="overflow:hidden">
					<!--Label removed because of redundancy-->
					<!--<label class="label-for-search" for=""><span class="mandatory">Search:</label>-->
					<div class='col-md-4'>
						<div class="form-group">
							<input class="form-control" placeholder="Where?" name="pac-input" type="text" class="searchinput" id="pac-input" style="width:100%" />
						</div>
					</div>
					<!--Calender-->
					<div class='col-md-4 no-label'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker6'>
								<input type='text' name="daterange" id="daterange" class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<div class='col-md-4 no-label'>
						<div class="form-group">
							<div class='input-group date' id='datetimepicker7'>
								<input type='text'  name="timerange"  id="timerange" class="form-control" />
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
							</div>
						</div>
					</div>
				</div>
        <script type="text/javascript">
            $('#daterange').daterangepicker({
               "autoApply": true,
                "alwaysShowCalendars": true,
                "startDate": "<?php echo date('m-d-Y'); ?>",
                "endDate": "<?php echo date('m-d-Y'); ?>",
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
                "startDate": "00:00:00",
                "endDate": "00:00:00",
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
      <input type="hidden" id="firststarttime" value="<?=date("H:i:s") ?>" />
      <input type="hidden" id="firstendtime" value="<?=date('H:i:s', strtotime('+2 hour', strtotime(date("H:i:s")))) ?>" />
      <input type="hidden" id="maplatNEValue"  name="maplatNEValue"  />
      <input type="hidden" id="maplongNEValue" name="maplongNEValue" />
      <input type="hidden" id="maplatSWValue" name="maplatSWValue" />
      <input type="hidden" id="maplongSWValue" name="maplongSWValue" />
	  <input class="standardbutton" style="cursor:pointer" type="button" id="searchbtnstart" name="searchbtnstart" value="Search" />
  
</form></div>

		</div>
	</div>
</div>

<!----------------------------------------------------------------------------------->
