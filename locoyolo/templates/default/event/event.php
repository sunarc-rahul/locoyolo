<?php include_once(TEMPPATH . "/header.php") ?>
<script xmlns="http://www.w3.org/1999/html">
    function insertRow() {
        var index = parseInt(document.getElementById("objective_number").value) + 1;
        var table = document.getElementById("objectivestable");
        var row = table.insertRow(table.rows.length);
        var cell1 = row.insertCell(0);
        cell1.style.height = '40px';
        cell1.innerHTML = "<font class=\"ArialVeryDarkGrey15\">#" + index + "</font>";

        var cell2 = row.insertCell(1);
        cell2.style.height = '40px';
        var t2 = document.createElement("input");
        t2.id = "event_objective" + index;
        t2.setAttribute("name", "event_objective" + index);
        t2.setAttribute("class", 'textboxbottomborder');
        t2.setAttribute("size", '80');
        t2.setAttribute("placeholder", 'Objective #' + index);
        cell2.appendChild(t2);

        var objectivenumber = document.getElementById("objective_number");
        objectivenumber.remove();
        var t3 = document.createElement("input");
        t3.setAttribute("type", 'hidden');
        t3.setAttribute("id", 'objective_number');
        t3.setAttribute("name", 'objective_number');
        t3.setAttribute("value", index);
        cell2.appendChild(t3);
    }
    function insertRow_mobile() {
        var index = parseInt(document.getElementById("objective_number_mobile").value) + 1;
        var table = document.getElementById("objectivestable_mobile");
        var row = table.insertRow(table.rows.length);
        var cell1 = row.insertCell(0);
        cell1.style.height = '40px';
        cell1.innerHTML = "<font class=\"ArialVeryDarkGrey15\">#" + index + "</font>";

        var cell2 = row.insertCell(1);
        cell2.style.height = '40px';
        var t2 = document.createElement("input");
        t2.id = "event_objective" + index;
        t2.setAttribute("name", "event_objective" + index);
        t2.setAttribute("class", 'textboxbottomborder');
        t2.style.width = '100%';
        t2.setAttribute("placeholder", 'Objective #' + index);
        cell2.appendChild(t2);

        var objectivenumber = document.getElementById("objective_number_mobile");
        objectivenumber.remove();
        var t3 = document.createElement("input");
        t3.setAttribute("type", 'hidden');
        t3.setAttribute("id", 'objective_number_mobile');
        t3.setAttribute("name", 'objective_number_mobile');
        t3.setAttribute("value", index);
        cell2.appendChild(t3);
    }
</script>
<script>
    function insertRow() {
        $(document).ready(function () {
            var index = parseInt(document.getElementById("objective_number").value) + 1;

            var table = document.getElementById("objectivestable");

            var row = $('<div class="form-group objec_'+index+'"><input name="event_objective' + index + '" class="form-control input-sm" id="event_objective' + index + '" size="80" placeholder="Objective ..." type="text" ><a class="rmv_obj">Remove</a></div>').appendTo('#objectivestable');
//        alert(index);
            document.getElementById("objective_number").value = index;

        });
    }
</script>
<style>
    /*Current form design*/
    div {
        font-size: 13px;
    }

    .btn-bs-file{
        position:relative;
    }
    .btn-bs-file input[type="file"]{
        position: absolute;
        top: -9999999;
        filter: alpha(opacity=0);
        opacity: 0;
        width:0;
        height:0;
        outline: none;
        cursor: inherit;going
    }

    .page-header {
        font-weight: bold;
    }

    .page-sub-header {
        color: orangered;
        font-size: 16px;
        font-style: italic;
        font-weight: lighter;
        padding-bottom: 35px;

    }

    ::-webkit-input-placeholder {
        color: #999;
    }

     :-moz-placeholder {
        color: #999;
    }

     ::-moz-placeholder {
        color: #999;
    }

     :-ms-input-placeholder {
        color: #999;
    }

     input:-webkit-autofill,
     textarea:-webkit-autofill {
        background-color: transparent !important;
        -webkit-box-shadow: 0 0 0 1000px white inset !important;
        -moz-box-shadow: 0 0 0 1000px white inset !important;
        box-shadow: 0 0 0 1000px white inset !important;
    }

     input,  textarea,  label {
        font-size: 1.0em;
        box-shadow: none;
        -webkit-box-shadow: none;
    }

     input:focus,
     textarea:focus {
        box-shadow: none;
        -webkit-box-shadow: none;
        /* border-bottom-width: 2px;*/
    }

     textarea:focus {
        /*padding-bottom: 4px;*/
    }

     input,  textarea {
        display: block;
        width: 100%;
        padding: 0.1em 0em 1px 0em;
        border: none;
        border-radius: 0px;
        border-bottom: 1px solid #aaa;
        outline: none;
        margin: 0px;
        background: none;
    }

     textarea {
        padding: 0.1em 0em 5px 0em;
    }


     input.empty + label,
     textarea.empty + label {
        top: 0.1em;
        font-size: 1.5em;
        animation: none;
        -webkit-animation: none;
    }

     input:not(.empty) + label,
     textarea:not(.empty) + label {
        z-index: 1;
    }

     input:not(.empty):focus + label,
     textarea:not(.empty):focus + label {
        color: #aaaaaa;
    }

    .label-bottom label {
        -moz-animation: float-labels-bottom 300ms none ease-out;
        -webkit-animation: float-labels-bottom 300ms none ease-out;
        -o-animation: float-labels-bottom 300ms none ease-out;
        -ms-animation: float-labels-bottom 300ms none ease-out;
        -khtml-animation: float-labels-bottom 300ms none ease-out;
        animation: float-labels-bottom 300ms none ease-out;
    }

    .label-bottom input:not(.empty) + label,
    .label-bottom textarea:not(.empty) + label {
        top: 3em;
    }

    @keyframes float-labels {
        0% {
            opacity: 1;
            color: #aaa;
            top: 0.1em;
            font-size: 1.5em;
        }
        20% {
            font-size: 1.5em;
            opacity: 0;
        }
        30% {
            top: 0.1em;
        }
        50% {
            opacity: 0;
            font-size: 0.85em;
        }
        100% {
            top: -1em;
            opacity: 1;
        }
    }

    @-webkit-keyframes float-labels {
        0% {
            opacity: 1;
            color: #aaa;
            top: 0.1em;
            font-size: 1.5em;
        }
        20% {
            font-size: 1.5em;
            opacity: 0;
        }
        30% {
            top: 0.1em;
        }
        50% {
            opacity: 0;
            font-size: 0.85em;
        }
        100% {
            top: -1em;
            opacity: 1;
        }
    }

    @keyframes float-labels-bottom {
        0% {
            opacity: 1;
            color: #aaa;
            top: 0.1em;
            font-size: 1.5em;
        }
        20% {
            font-size: 1.5em;
            opacity: 0;
        }
        30% {
            top: 0.1em;
        }
        50% {
            opacity: 0;
            font-size: 0.85em;
        }
        100% {
            top: 3em;
            opacity: 1;
        }
    }

    @-webkit-keyframes float-labels-bottom {
        0% {
            opacity: 1;
            color: #aaa;
            top: 0.1em;
            font-size: 1.5em;
        }
        20% {
            font-size: 1.5em;
            opacity: 0;
        }
        30% {
            top: 0.1em;
        }
        50% {
            opacity: 0;
            font-size: 0.85em;
        }
        100% {
            top: 3em;
            opacity: 1;
        }
    }

    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 400px;
    }

    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #infowindow-content .title {
        font-weight: bold;
    }

    #infowindow-content {
        display: none;
    }

    #map #infowindow-content {
        display: inline;
    }

    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }

    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #event_location {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
    }

    #event_location:focus {
        border-color: #4d90fe;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }
        .edit-event-image{width:50%!important;max-width:50%!important;}
    #target {
        width: 345px;
    }
</style>
<?php 

if (count($error) >= 1|| $_SESSION['success']){?>
<div class="shows-errors_div has-error-message">
    <div class="container is-error-message">
        <div class="Show-error">
		<span class="error-close">X</span>
            <?php
            if (count($error) >= 1){
                foreach ($error as $errormessage) {

                    if ($errormessage !== "") { ?>
                        <div class="alert alert-danger">
                            <?php echo $errormessage; ?>
                        </div>
                   <?php }
                }

            } else {
            ?>
            <div class="row errors">
                <div class=" col-sm-6 ArialVeryDarkGrey15" style="color:#F33">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']);

                    ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="container fixed-footer">
    <div  class="goes-on-left">
        <h3>Organise an event</h3>
    </div>

            <form role="form" method="post" class="form-vertical" action="" enctype="multipart/form-data">
                

                <h5 class="page-sub-header">Basic Information</h5>
<!--
    ToDO:- Remove Achievement statement Section
    Developer: Nitin Soni
    Date:- 05Aug/2017

  -->                
<!-- Commented by Nitin Soni For show responsive design -->
<!-- 
                 <div class="form-group">
                     <label for="event_name"><span class="mandatory">*</span>Event name:</label>                                              <input type="text" name="event_name" class="form-control input-sm" id="event_name"
                         value="<?php echo  $_POST["event_name"] ?>" placeholder="Name your event">
                 </div> -->
<!-- Finished By Nitin Soni -->                 
                    <div class="row">
                        <div class="col-sm-12"> 
                            <div class="form-group ">
                                         <label for="event_name"><span class="mandatory">*</span>Event name:</label>                                              <input type="text" maxlength="50" placeholder="Name should not exceed more than 50 words." value="<?php echo $_POST['event_name']? $_POST['event_name']:$userEventData->event_name; ?>" id="event_name" class="form-control input-sm" name="event_name">
                            </div>
                      </div>
                    </div>
                      
                        <div class="form-group">
                            <label for=""><span class="mandatory">*</span> Event date:</label>
                            <div class='input-group date' id='datetimepicker6'>
                                <input type='text' name="daterange" id="daterange" class="form-control" />
                                <span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
                            </div>
                        </div>

                    <script type="text/javascript">
						$('span.error-close').click(function(){	{ $('.Show-erroe').hide(); } });
                        $('#daterange').daterangepicker({
                            "autoApply": true,
                            "alwaysShowCalendars": true,
                            "startDate": "<?php echo $userEventData->start_date?date('m-d-Y h:i:s A',strtotime($userEventData->start_date)):date('m-d-Y')?>",
                            "endDate":  "<?php echo $userEventData->end_date?date('m-d-Y h:i:s A',strtotime($userEventData->end_date)):date('m-d-Y')?>",
                            "drops": "down",
                            "timePicker": true,
                             locale: {
                                format: 'MM/DD/YYYY h:mm A'
                            }
                        }, function(start, end, label) {
                            console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
                        });

                    </script>

                <div class="row">
                    <div class="col-sm-3">
                         <div class="form-group">
                             <label for="event_price">
                                <span class="mandatory">*</span>Price S$:</label>
                                <input name="event_price" id="event_price" type="text" class="form-control input-sm" id="event_price"
                                       value="<?php echo $_POST['event_price']?$_POST['event_price']:$userEventData->event_price;  ?>" size="5"/>

                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="event_participants_max">
                                 <span class="mandatory">*</span>Max. participants:
                                </label>
                                <input name="event_participants_max" type="text" class="form-control input-sm"
                                       id="event_participants_max"
                                       value="<?php echo $_POST['event_participants_max']?$_POST['event_participants_max']:$userEventData->event_participants_max; ?>" size="5"/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                            <label for="event_category">Event category:</label>
                                <select name="event_category" id="event_category"
                                        class="form-control btn-sm input-sm" id="event_category">
                                    <option value="1" <?php if ($_POST["event_category"] == "1"|| $userEventData->event_category == "1") { ?> selected="selected" <?php } ?>  <?php if ($_POST["event_category"] == "") { ?> selected="selected" <?php } ?> >
                                        Sports
                                    </option>
                                    <option value="2" <?php if ($_POST["event_category"] == "2"|| $userEventData->event_category == "2") { ?> selected="selected" <?php } ?> >
                                        Relaxation
                                    </option>
                                    <option value="3" <?php if ($_POST["event_category"] == "3"|| $userEventData->event_category == "3") { ?> selected="selected" <?php } ?> >
                                        Food
                                        &amp; Drink
                                    </option>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="event_cancellation">
                                Cancellation:
                                </label>
                                <select name="event_cancellation" class="form-control btn-sm input-sm"
                                        id="event_cancellation">
                                    <option <?php if ($_POST["event_cancellation"] == "F"|| $userEventData->event_cancellation == "F") { ?> selected="selected" <?php } ?>
                                            value="F">
                                        Flexible
                                    </option>
                                    <option <?php if ($_POST["event_cancellation"] == "P"|| $userEventData->event_cancellation == "P") { ?> selected="selected" <?php } ?>
                                            value="P">
                                        Penalty
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="city2" value="<?php echo $_POST['event_location']?$_POST['event_location']:$userEventData->event_location ?>" name="city2"/>
                    <input type="hidden" id="event_lat" value="<?php echo $_POST['event_lat']?$_POST['event_lat']:$userEventData->event_lat ?>" name="event_lat"/>
                    <input type="hidden" id="event_long" value="<?php echo $_POST['event_long']?$_POST['event_long']:$userEventData->event_long ?>" name="event_long"/>

                <div class="form-group">
                    <label for="event_location">
                            <span class="mandatory">*</span>Location:
                    </label>

                            <div id="map"></div>
                            <input type="text" style="border: 1px solid lightgray;" class="form-control input-sm"
                                   id="event_location" name="event_location"
                                   placeholder="Search for the exact or nearest location of your event..."  value="<?php echo $_POST['event_location']?$_POST['event_location']:$userEventData->event_location  ?>" size="60">

                </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="event_location">
                            Describe the event location:</label>
                                <textarea name="event_location_description"
                                          cols="40" rows="4" class="form-control input-sm"
                                          id="event_location_description"
                                          placeholder="Describe the event location..."><?php echo $POST['event_location_description']?$POST['event_location_description']:$userLocationData->event_location_description; ?></textarea>
                    </div>
                 </div>
                <div class="col-sm-6">
                                <label for="event_location">Travel directions:</label>
                                <textarea name="travel_directions" cols="40" rows="4" class="form-control input-sm"
                                          id="travel_directions" placeholder="Travel directions..."><?php echo $_POST['travel_directions']?$_POST['travel_directions']:$userLocationData->travel_directions ?></textarea>
                </div>
            </div>
                <h5 class="page-sub-header">Summary</h5>

                         <div class="form-group">
                             <label for="event_description">Describe the event:</label>
                                <textarea name="event_description" cols="100" rows="4" class="form-control input-sm"
                                      id="event_description"
                                      placeholder="Describe the event..."><?php echo $_POST['event_description']?trim($_POST['event_description']):trim($userEventData->event_description); ?></textarea>
                        </div>

                <h5 class="page-sub-header">*Event Objectives:</h5>
                <?php
                $numberofobjectives = $_POST['objective_number']?$_POST['objective_number']:(count($objectives)-1);
                $i = $_POST['objective_number']?1:0;
                $toval = $_POST['objective_number']?3:2;
                ?>
                <div id="objectivestable">
                    <div class="form-group objec_1">

                            <input name="event_objective1" type="text" class="form-control input-sm"
                                   id="event_objective1"
                                   size="80" placeholder="Objective..."
                                   value="<?php echo $_POST['event_objective1']?$_POST['event_objective1']:$objectives[0]; ?>"/>

                        </div>
                    <div class="form-group objec_2">

                            <input name="event_objective2" type="text" class="form-control input-sm"
                                   id="event_objective2"
                                   size="80" placeholder="Objective..."
                                   value="<?php echo $_POST['event_objective2']?$_POST['event_objective2']:$objectives[1]; ?>"/>
                            <?php if ($numberofobjectives < 3) { ?>
                                <input id="objective_number" name="objective_number" type="hidden" value="2"/><?php } ?>


                    </div>


                        <?php
                        $a = 2;
                       while ($i <= ($numberofobjectives)) {
                            if ($_POST["event_objective" . $i] !== ""|| $objectives[$i]!== "") {
                                if ($i >= $toval) { ?>
                                    <div class="form-group  objec_<?php echo  ($a + 1) ?>">

                                    <input name="event_objective<?php echo  ($a + 1) ?>" type="text" class="form-control input-sm"
                                               id="event_objective<?php echo  ($a + 1) ?>" size="80"
                                               placeholder="Objective..."
                                               value="<?php echo $_POST["event_objective".$i]?$_POST["event_objective".$i]:$objectives[$i]; ?>"/>
                                        <a class="rmv_obj">Remove</a>
                                   </div>

                                    <?php $a++;
                                }
                            }
                            $i++;
                        }

                        ?>
                    <?php
                    if ($numberofobjectives > 2) { ?>
                            <input id="objective_number" name="objective_number" type="hidden"
                                   value="<?php echo $a ?>" /><?php } ?>

                </div>
                <button type="button" class="btn warning" onclick="insertRow();" style="cursor:pointer">
                    &nbsp;&nbsp;<span class="ArialVeryDarkGrey15" style="color:#09C">+Add another objective</span>
                </button>


            <h5 class="page-sub-header requirementheading"> Requirements:</h5>

            <div class="row">
            <div class="col-sm-6">
            <div class="form-group">
                       <label>Attire:</label>

                                <select name="event_attire" class="form-control btn-sm input-sm" id="event_attire">
                                    <option value="Casual" <?php if ($_POST["event_attire"] == "Casual"|| $userEventData->event_attire == "Casual") { ?> selected="selected" <?php } ?>  <?php if ($_POST["event_attire"] == "") { ?> selected="selected" <?php } ?> >
                                        Casual
                                    </option>
                                    <option value="Formal" <?php if ($_POST["event_attire"] == "Formal" || $userEventData->event_attire == "Formal") { ?> selected="selected" <?php } ?> >
                                        Formal
                                    </option>
                                    <option value="Sporty" <?php if ($_POST["event_attire"] == "Sporty" || $userEventData->event_attire == "Sporty") { ?> selected="selected" <?php } ?> >
                                        Sporty
                                    </option>
                                </select>
            </div>
            </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <label>Notes about attire:</label>
                                            <textarea name="event_attire_desc" cols="40" rows="4" class="form-control"
                                                      id="event_attire_desc"
                                                      placeholder="Notes about attire..."><?php echo $_POST['event_attire_desc']?trim($_POST['event_attire_desc']):trim($userEventData->event_attire_desc); ?></textarea>
                </div> </div>
                        </div>
                <div class="row">
                    <div class="col-sm-6">

                              <label>  Food and drinks:</label>

                                <select name="event_food_and_drinks" class="form-control btn-sm input-sm"
                                        id="event_food_and_drinks">
                                    <option value="provided" <?php if ($_POST["event_food_and_drinks"] == "provided"|| $userEventData->event_food_and_drinks == "provided") { ?> selected="selected" <?php } ?>  <?php if ($_POST["event_food_and_drinks"] == "") { ?> selected="selected" <?php } ?> >
                                        provided
                                    </option>
                                    <option value="Not provided" <?php if ($_POST["event_food_and_drinks"] == "Not provided" ||$userEventData->event_food_and_drinks == "Not provided") { ?> selected="selected" <?php } ?> >
                                        Not provided
                                    </option>
                                </select>

                        </div>

                            <div class="col-sm-6">
                                <label>Notes about F&B:</label>
                                            <textarea name="event_food_and_drinks_desc" cols="40" rows="4" class="form-control input-sm"
                                                      id="event_food_and_drinks_desc"
                                                      placeholder="Notes about F&B..."><?php echo $_POST['event_food_and_drinks_desc']?trim($_POST['event_food_and_drinks_desc']):trim($userEventData->event_food_and_drinks_desc);?></textarea>
                            </div>
                        </div>
                <div class="row">
                    <div class="col-sm-6">
            <div class="form-group">

                            <label>
                                Essentials:
                            </label>
                           <textarea name="event_essentials" cols="40" rows="4"
                                     class="form-control input-sm"
                                     id="event_essentials"
                                     placeholder="Notes about essentials..."><?php echo $_POST['event_essentials']?trim($_POST['event_essentials']):trim($userEventData->event_essentials);?></textarea>
                            </div>
                        </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                        <label>
                                Safety:
                            </label>

                            <textarea name="event_safety" cols="40" rows="4" class="form-control input-sm"
                                      id="event_safety" placeholder="Notes about safety..."><?php echo $_POST['event_safety']?trim($_POST['event_safety']):trim($userEventData->event_safety); ?></textarea>
                        </div>
                     </div>
            </div>
                <div class="row">
                    <div class="col-sm-6">
                         <div class="form-group">
                             <label> Fitness:</label>
                             <select name="event_fitness" class="form-control btn-sm input-sm"
                                     id="event_fitness">
                                    <option <?php if ($_POST["event_fitness"] == "Not applicable"|| $userEventData->event_fitness == "Not applicable")
                                    { ?> selected="selected" <?php } ?> <?php if ($_POST["event_fitness"] == "")
                                    { ?> selected="selected" <?php } ?>
                                            value="Not applicable">Not applicable
                                    </option>
                                    <option <?php if ($_POST["event_fitness"] == "Semi Fit"|| $userEventData->event_fitness == "Semi Fit")
                                    { ?> selected="selected" <?php } ?>
                                            value="Semi Fit">Semi Fit
                                    </option>
                                    <option <?php if ($_POST["event_fitness"] == "Fit"|| $userEventData->event_fitness == "Fit")
                                    { ?> selected="selected" <?php } ?>
                                            value="Fit">Fit
                                    </option>
                                    <option <?php if ($_POST["event_fitness"] == "Very Fit"|| $userEventData->event_fitness == "Semi Fit"){
                                        ?> selected="selected" <?php } ?>
                                            value="Very Fit">Very Fit
                                    </option>
                                </select>
                            </div>
                        </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Notes about required fitness: </label>
                                    <textarea name="event_fitness_desc" cols="40" rows="4"
                                              class="form-control input-sm"
                                              id="event_fitness_desc"
                                              placeholder="Notes about required fitness..."><?php echo $_POST['event_fitness_desc']?trim($_POST['event_fitness_desc']):trim($userEventData->event_fitness_desc);?></textarea>
                            </div>
                        </div>
                    </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>
                                Additional notes:
                           </label>

                          <textarea name="event_additional_notes" cols="40" rows="4"
                                              class="form-control input-sm"
                                              id="event_additional_notes"
                                              placeholder="Anything else?"><?php echo $_POST['event_additional_notes']?trim($_POST['event_additional_notes']):trim($userEventData->event_additional_notes);?></textarea>
                         </div>
                        </div>
                    <div class="col-sm-6">
                     <div class="form-group">
                        <label>
                                            <label>
                                                Do you require participants to be 21 years or older?
                                            </label>
                            </label>

                                <select name="event_age_21" class="form-control btn-sm input-sm" id="event_age_21">
                                    <option <?php if ($_POST["event_age_21"] == "N" || $userEventData->event_age_21 == "N") { ?> selected="selected" <?php } ?> <?php if ($_POST["event_age_21"] == "") { ?> selected="selected" <?php } ?>
                                            value="N">No
                                    </option>
                                    <option <?php if ($_POST["event_age_21"] == "Y" || $userEventData->event_age_21 == "Y" ) { ?> selected="selected" <?php } ?>
                                            value="Y">Yes
                                    </option>
                                </select>
                         <label style="margin-top:14px">Do you wish to manually accept all bookings?</label>


                         <select name="event_filter" class="form-control btn-sm input-sm" id="event_filter">
                             <option <?php if ($_POST["event_filter"] == "N"|| $userEventData->event_filter == "N") { ?> selected="selected" <?php } ?> <?php if ($_POST["event_filter"] == ""|| $userEventData->event_filter == "") { ?> selected="selected" <?php } ?>
                                 value="N" selected="selected">No
                             </option>
                             <option <?php if ($_POST["event_filter"] == "Y" ||$userEventData->event_filter == "Y") { ?> selected="selected" <?php } ?>
                                 value="Y">Yes
                             </option>
                         </select>
                            </div>
                        </div>

                    </div>
				<div class="event-image-upload">
				<div class="row">
					<h5 class="page-sub-header">*Image Upload:</h5>
				</div>
                <div class="row">
                    <div class="col-sm-6">
                           <?php if ($userEventData->event_photo != "") {?>
                            <img class=" edit-event-image image-doesnt-has-radius" src="<?php echo ROOTURL . '/' . $userEventData->event_photo; ?>" alt="image">
                        <?php } ?>
                        <div class="form-group">
<!--                             <label>
                                <span class="mandatory">*</span><span class="h5"> Image </span> Upload
                            </label> -->
                            <input  type="file" name="event_photo" id="event_photo"/>

                            </div>
                        </div>
                </div>
				</div>






<!--
    ToDO:- Remove Achievement statement Section
    Developer: Nitin Soni
    Date:- 05Aug/2017

  -->

<!--
            <h5 class="page-sub-header"> Achievement statement</h5>

            <div class="form-group">
                 <label>
                                <span class="mandatory">* </span><span class="h5">
                                Assign an achievement statement for any participant who completes your event.</br>
                                    Example: The participant has "learnt how to cook an awesome Lasgna!"
                                            </span>

                            </label>
                        </div>
-->                        

<!--
                <div class="form-group">
                    <label>
                                <span class="h5">The participant has...</span>
                    </label>
                                <input name="achievement_statement" type="text" class="form-control input-sm"
                                       id="achievement_statement" size="80"
                                       placeholder="What has the participant achieved?"
                                       value="<?php //= $_POST["achievement_statement"] ?>"/>
                  </div>

-->

<!-- Finished by Nitin Soni -->


                        <input type="hidden" id="new_event_entry" name="new_event_entry" value="Y"/>
                        <button class="btn btn-default" style="cursor:pointer" type="submit"
                               id="submit" <?php if(!$userEventData){?>  name="event_submit" value="Publish">Submit<?php } else{?> name="edit_event_submit" value="update">Update<?php }?></button>



            </form>

  </div>



<script>
    // This example adds a search box to a map, using the Google Place Autocomplete
    // feature. People can enter geographical searches. The search box will return a
    // pick list containing a mix of places and predicted search terms.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initAutocomplete() {

        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var marker;

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 1.2652, lng: 103.8201},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('event_location');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();
            places.forEach(function (place) {
                document.getElementById('city2').value = place.name;
            });
            //document.getElementById('event_lat').value = places.geometry.location.lat();
            //document.getElementById('event_long').value = places.geometry.location.lng();
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('event_location').value;
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById('event_lat').value = results[0].geometry.location.lat();
                    document.getElementById('event_long').value = results[0].geometry.location.lng();
                }
            });

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                google.maps.event.addListener(map, 'click', function (event) {
                    placeMarker(event.latLng);
                });

                function placeMarker(location) {

                    if (marker == null) {
                        marker = new google.maps.Marker({
                            position: location,
                            icon: 'images/red_pos_marker.fw.png',
                            map: map
                        });
                        document.getElementById('event_lat').value = marker.getPosition().lat();
                        document.getElementById('event_long').value = marker.getPosition().lng();
                        //getDirections(document.getElementById('city2').value);
                    } else {
                        marker.setPosition(location);
                        document.getElementById('event_lat').value = marker.getPosition().lat();
                        document.getElementById('event_long').value = marker.getPosition().lng();
                        //getDirections(document.getElementById('city2').value);
                    }
                }

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

    
    $(document).on('click','.rmv_obj', function() {
   $(this).parent().remove();
});
    /*function getDirections(destination) {
     var start = marker.getPosition();
     var dest = destination;
     alert(dest);
     var request = {
     origin: start,
     destination: dest,
     travelMode: google.maps.TravelMode.WALKING
     };
     directionsService.route(request, function (result, status) {
     if (status == google.maps.DirectionsStatus.OK) {
     directionsDisplay.setDirections(result);
     }
     });
     }*/

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHXsI2hOfs6x7NJLR8LnN5wG-2N-ha0S8&libraries=places&callback=initAutocomplete"
        async defer></script>
</body>
</html>