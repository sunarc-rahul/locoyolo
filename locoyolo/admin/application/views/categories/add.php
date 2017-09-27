<?php
$event_type='';
if($this->input->post()) {
    $catInfo[] = (object)$this->input->post();
}
if (!empty($catInfo)) {
    foreach ($catInfo as $uf) {
        $event_type = $uf->event_type;
    }
}
?>


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <i class="fa fa-users"></i> Category Management
    </h1>
</section>

<section class="content">

    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->



            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Enter Category Details</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <?php
                if (function_exists('validation_errors')&& validation_errors()!='') {
                    echo '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    ' . validation_errors() . '
    </div>';
                }
                ?>
                <form role="form" action="<?php
                echo base_url();
                ?>category/save" method="post" id="editCategory" role="form" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="event_type">Event type</label>
                                    <input type="text" class="form-control" id="event_type" placeholder="Event type" name="event_type" value="<?php  echo $event_type?$event_type:'';  ?>" maxlength="128">


                            </div>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                               <div class="form-group">
                                    <label for="map_icon">Map Icon</label>
                                    <input class="form-control-file" name='map_icon' id="map_icon" aria-describedby="fileHelp" type="file">
                                    <small id="fileHelp" class="form-text text-muted">Please upload squre image only.</small>
                                </div>

                            </div>
                            <div class="col-md-6">
                                    <div class="form-group">
                                    <label for="type_icon">Type Icon</label>
                                    <input class="form-control-file" name='type_icon' id="type_icon" aria-describedby="fileHelps" type="file">
                                    <small id="fileHelps" class="form-text text-muted">Please upload squre image only.</small>
                                </div>

                            </div>

                        </div>
                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <input type="submit" class="btn btn-primary" value="Submit" />
                        <a class="btn btn-default" href="<?php
                        echo site_url('categories');
                        ?>">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
</div>
