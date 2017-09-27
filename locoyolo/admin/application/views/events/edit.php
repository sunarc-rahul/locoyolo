<?php
echo '<pre>';print_r($eventInfo);exit;
$event_id = '';
$name = '';
$email = '';
$mobile = '';
$roleId = '';

if(!empty($userInfo))
{
    foreach ($eventInfo as $uf)
    {
        $event_id =$id = $uf->event_id;
        $event_name = $uf->event_name;
        $start_date = $uf->start_date;
        $end_date = $uf->end_date;
        $event_description = $uf->event_description;
        $event_objectives = $uf->event_objectives;
        $event_category= $uf->event_category;
        $event_participants = $uf->event_participants;
        $event_cancellation= $uf->event_cancellation;
        $event_price= $uf->event_price;
        $event_fitness_desc= $uf->event_fitness_desc;
        $event_participants_max= $uf->event_participants_max;
        $event_attire= $uf->event_attire;
        $event_essentials= $uf->event_essentials;
        $event_fitness= $uf->event_fitness;
        $event_age_21= $uf->event_age_21;
        $event_attire_desc= $uf->event_attire_desc;
        $event_additional_notes= $uf->event_additional_notes;
        $event_entry_date= $uf->event_entry_date;
        $event_status= $uf->event_status;
        $event_food_and_drinks= $uf->event_food_and_drinks;
        $event_food_and_drinks_desc= $uf->event_food_and_drinks_desc;
        $event_safety= $uf->event_safety;
        $event_location= $uf->event_location;
        $event_lat= $uf->event_lat;
        $event_long= $uf->event_long;
        $achievement_statement= $uf->achievement_statement;

    }
}


?>


    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> User Management
        <small>Add / Edit User</small>
      </h1>
    </section>

    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter User Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php if(function_exists('validation_errors')){
                        echo '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    '. validation_errors().'
    </div>'; }  ?>
                    <form role="form" action="<?php echo base_url() ?>editUser" method="post" id="editEvent" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Event name</label>
                                        <input type="text" class="form-control" id="fname" placeholder="Full Name" name="event_name" value="<?php echo $event_name; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $id; ?>" name="eventId" id="eventId" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Category</label>
                                    <select name="event_category">
                                        <?php foreach($category as $val){ ?>
                                        <option value="<?php echo $val->id; ?>" <?php if($val->id==$event_category){echo 'selected';}?>><?php echo $val->event_type; ?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender ">Start Date</label>
                                       <input type="text" readonly value="<?php echo date('d-m-Y',strtotime($start_date));?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">End Date</label>
                                        <input type="text" readonly value="<?php echo date('d-m-Y',strtotime($end_date));?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Event_description</label>
                                       <textarea name="event_description"><?php echo $event_description; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Event objectives</label>
                                             <textarea name="event_objectives"><?php str_replace(';','\n',$event_objectives); ?></textarea>
                                                                            </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Event price </label>
                                       <input type="text" name="event_price" value="<?php echo $event_price; ?>"
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="about_me">About me </label>
                                        <textarea type="text"  class="form-control" id="about_me" placeholder="About me" name="about_me" ><?php echo $about_me?$about_me:""; ?></textarea>
                                    </div>
                                </div>
                            </div><div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Achievements</label>
                                        <textarea type="text" class="form-control" id="achievements"
                                                  placeholder="Achievements" name="achievements" ><?php echo $achievements?$achievements:"" ; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <!-- <div class="form-group">
                                             <label for="about_me">About_me </label>
                                                 <input id="about_me" type="text" placeholder="About me ">
                                                      <textarea type="text" class="form-control" id="about_me" placeholder="About me"
                                                       name="about_me" >
                                                           <?php //echo $about_me; ?>
                                                               </textarea>
                                          </div>-->
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <a class="btn btn-default" href="<?php echo site_url('users')?>">Back</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>    
    </section>
</div>

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>