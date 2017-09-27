<?php
$pingInfo = $pingInfo[0];
?>
<div class="box box-solid">
    <div class="box-header with-border">
<div class="col-sm-10">
        <h3>
            <?php echo $pingInfo->event_name;?>
        </h3>
    </div>
        <div class="col-sm-2">
            <br>
        <button  class="btn btn-danger" data-id='<?php echo $pingInfo->event_id;?>'  data-toggle="modal" data-target="#confirm-suspend"  id="suspendevent">Suspend</button>
        <a  class="btn btn-warning" href="<?php echo site_url('pings')?>">Back</a>
    </div></div>
    <!-- /.box-header -->
    <div class="box-body">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
        <!-- Default box -->
        <table class="table table-bordered">
            <tr><td>Ping Name</td>
            <td><?php echo $pingInfo->event_name;?></td></tr>
            <tr><td>Ping catrgory</td>
            <td><?php echo $pingInfo->event_type;?></td></tr>
            <tr><td>Organiser Name</td>
            <td><?php echo $pingInfo->firstname.' '.$pingInfo->lastname;?></td></tr>
            <tr><td>Organiser Id</td>
            <td><?php echo $pingInfo->user_id;?></td></tr>
            <tr><td>Ping Date</td>
            <td><?php echo date('d-m-Y h:i',strtotime($pingInfo->start_date));?></td></tr>

            <tr><td>Ping participants max</td>
            <td><?php echo $pingInfo->event_participants_max;?></td></tr>
            <tr><td>Ping description</td>
            <td><?php echo $pingInfo->event_description;?></td></tr>
            <tr><td>Ping Latitude</td>
                <td><?php echo $pingInfo->event_lat;?></td></tr>
            <tr><td>Ping Longitude</td>
                <td><?php echo $pingInfo->event_long;?></td></tr>
              <tr><td>Ping location</td>
                <td><?php echo $pingInfo->event_location;?></td></tr>
            </table>
        <?php
        if(file_get_contents(str_replace('admin/','',site_url()).$pingInfo->event_photo) && $pingInfo->event_photo!=''){?>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Images</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <img height='150' src="<?php echo str_replace('admin/','',site_url()).$pingInfo->event_photo?>">
            </div>
            <!-- /.box-body -->
        </div>
        <?php }?>






        <!-- /.box -->
</div></div>


<!-- /.content -->
