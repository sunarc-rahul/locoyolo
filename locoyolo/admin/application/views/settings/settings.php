
    <section class="content-header">
        <h1><i class="glyphicon glyphicon-cog"></i>Settings</h1>
    </section>

    <section class="content">

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->



                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Change Account details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php if($this->session->flashdata('error')||$this->session->flashdata('nomatch')){?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <p><?php echo $this->session->flashdata('error')?$this->session->flashdata('error'):$this->session->flashdata('nomatch');?></p>
                        </div>
                    <?php } ?>
                    <?php if($this->session->flashdata('success')){?>
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <p><?php echo $this->session->flashdata('success');?></p>
                        </div>
                    <?php } ?>

                    <?php if(function_exists('validation_errors')&& validation_errors()!=''){
                        echo '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    '. validation_errors().'
    </div>';                    }  ?>
                    <form role="form" action="<?php echo site_url('user/edit/settings');?>" method="post" id="editUser">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpass">Current Password</label>
                                        <input class="form-control" id="cpass" placeholder="Current Password" name="currentpassword" maxlength="128" type="password">

                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="npass">New Password</label>
                                        <input class="form-control" id="npass" placeholder="New Password" name="newpassword" maxlength="128" type="password">

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cnpass">Confirm new Password</label>
                                        <input class="form-control" id="cnpass" placeholder="Confirm new Password" name="confirmnewpassword" maxlength="128" type="password">

                                    </div>

                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>

                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <input class="btn btn-primary" value="Submit" type="submit">
                            <a class="btn btn-default" href="<?php echo site_url('users')?>">Back</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>