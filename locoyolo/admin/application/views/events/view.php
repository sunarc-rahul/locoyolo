<?php
$eventInfo = $eventInfo[0];
?>
<div class="box box-solid">
    <div class="box-header with-border">
<div class="col-sm-10">
        <h3>
            <?php echo $eventInfo->event_name;?>
        </h3>
    </div>
        <div class="col-sm-2">
            <br>
        <button  class="btn btn-danger" data-id='<?php echo $eventInfo->event_id;?>'  data-toggle="modal" data-target="#confirm-suspend"  id="suspendevent">Suspend</button>
        <a  class="btn btn-warning" href="<?php echo site_url('events')?>">Back</a>
    </div></div>
    <!-- /.box-header -->
    <div class="box-body">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
        <!-- Default box -->
        <table class="table table-bordered">
            <tr><td>Event Name</td>
            <td><?php echo $eventInfo->event_name;?></td></tr>
            <tr><td>Event catrgory</td>
            <td><?php echo $eventInfo->event_type;?></td></tr>
            <tr><td>Organiser Name</td>
            <td><?php echo $eventInfo->firstname.' '.$eventInfo->lastname;?></td></tr>
            <tr><td>Organiser Id</td>
            <td><?php echo $eventInfo->user_id;?></td></tr>
            <tr><td>Start Date</td>
            <td><?php echo date('d-m-Y h:i',strtotime($eventInfo->start_date));?></td></tr>
            <tr><td>End Date</td>
            <td><?php echo date('d-m-Y h:i',strtotime($eventInfo->end_date));?></td></tr>
            <tr><td>Event price</td>
            <td><?php echo $eventInfo->event_price;?></td></tr>
            <tr><td>Event participants max</td>
            <td><?php echo $eventInfo->event_participants_max;?></td></tr>
            <tr><td>Event fitness</td>
            <td><?php echo $eventInfo->event_fitness;?></td></tr>
            <tr><td>Event age 21</td>
            <td><?php echo $eventInfo->event_age_21;?></td></tr>
            <tr><td>Event status</td>
            <td><?php echo $eventInfo->event_status;?></td></tr>
            <tr><td>Event Latitude</td>
            <td><?php echo $eventInfo->event_lat;?></td></tr>
            <tr><td>Event Longitude</td>
            <td><?php echo $eventInfo->event_long;?></td></tr>
            <tr><td>Event safety</td>
            <td><?php echo $eventInfo->event_safety;?></td></tr>
            <tr><td>event location</td>
            <td><?php echo $eventInfo->event_location;?></td></tr>
            <tr><td>event achievement statement</td>
            <td><?php echo $eventInfo->achievement_statement;?></td></tr>
            <tr><td>Event additional notes</td>
            <td><?php echo $eventInfo->event_additional_notes;?></td></tr>
            <tr><td>Event fitness desc</td>
            <td><?php echo $eventInfo->event_fitness_desc;?></td></tr>
            <tr><td>Event attire desc</td>
            <td><?php echo $eventInfo->event_attire_desc;?></td></tr>
            <tr><td>Event food and drinks</td>
            <td><?php echo $eventInfo->event_food_and_drinks;?></td></tr>
            <tr><td>Event food and drinks desc</td>
            <td><?php echo $eventInfo->event_food_and_drinks_desc;?></td></tr>
            <tr><td>Event description</td>
            <td><?php echo $eventInfo->event_description;?></td></tr>
            <tr><td>Event objectives</td>
            <td><?php echo $eventInfo->event_objectives;?></td></tr>
            <tr><td>Event participants max</td>
            <td><?php echo $eventInfo->event_participants_max;?></td></tr>

            </table>
        <?php if(file_get_contents(str_replace('admin/','',site_url()).$eventInfo->event_photo) && $eventInfo->event_photo!=''){?>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Images</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <img height='150' src="<?php echo str_replace('admin/','',site_url()).$eventInfo->event_photo?>">
            </div>
            <!-- /.box-body -->
        </div>
        <?php }?>






        <!-- /.box -->
</div></div>


<!-- /.content -->
