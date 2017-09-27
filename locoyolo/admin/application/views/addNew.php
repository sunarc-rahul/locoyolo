
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> User Management
        <small>Add / Edit User</small>
      </h1>
    </section>
    <?php
    $userId=$id =  $firstname =$lastname = $email =$contact =$gender = $birthdate =  $address = $about_me =  $achievements = '';
   if(count($this->input->post())>0) {
       $userInfo[] =(object) $this->input->post();
       foreach ($userInfo as $uf) {
           $firstname = $uf->firstname;
           $lastname = $uf->lastname;
           $email = $uf->email;
           $contact = $uf->contact;
           $gender = $uf->gender;
           $birthdate = $uf->birthdate;
           $address = $uf->address;
           $about_me = $uf->about_me;
           $achievements = $uf->achievements;
       }
   }
    ?>

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
                    <?php if(function_exists('validation_errors')&& count($this->input->post())>0){
                        echo '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    '. validation_errors().'
    </div>';


                    }  ?>
                    <form role="form" action="<?php echo base_url() ?>addNewUser" method="post" id="editUser" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">Firstname</label>
                                        <input type="text" class="form-control" id="fname" placeholder="Full Name" name="firstname" value="<?php echo $firstname; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $id; ?>" name="userId" id="userId" />
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Lastname</label>
                                        <input type="lastname" class="form-control" id="lastname" placeholder="Enter Lastname" name="lastname" value="<?php echo $lastname; ?>" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gender ">Gender</label>
                                        <select name="gender" class="form-control" >
                                            <option <?php if($gender=='male') echo 'selected';?> value="male">Male</option>
                                            <option  <?php if($gender=='female') echo 'selected';?> value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Email</label>
                                        <input type="email" class="form-control" value='<?php echo $email;?>' id="email" placeholder="Email" name="email" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="contact">Mobile Number</label>
                                        <input type="text" class="form-control" value="<?php echo $contact?$contact:'';?> " id="contact" placeholder="Mobile Number" name="contact" value="<?php echo $contact?$contact:''; ?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Birthdate</label>
                                        <input id="birthdate" type="text" name='birthdate' value="<?php echo $birthdate?date('d-m-Y',strtotime($birthdate)):'';?>" class="form-control" placeholder="Birthdate">
                                        <script>
                                            $('#birthdate').datepicker({
                                                autoclose: true
                                            });
                                        </script>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <textarea type="text" class="form-control" id="address" placeholder="Address" name="address" ><?php echo $address?$address:""; ?></textarea>
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

<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>