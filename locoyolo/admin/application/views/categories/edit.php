<?php
if (!empty($catInfo)) {
    foreach ($catInfo as $uf) {
        $id         = $uf->id;
        $event_type = $uf->event_type;
        $map_icon   = $uf->map_icon;
        $type_icon  = $uf->type_icon;
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
                if (function_exists('validation_errors')) {
                    echo '<div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    ' . validation_errors() . '
    </div>';
                }
                ?>
                <form role="form" action="<?php
                echo base_url();
                ?>category/edit" method="post" id="editCategory" role="form" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="event_type">Event type</label>
                                    <input type="text" class="form-control" id="event_type" placeholder="Event type" name="event_type" value="<?php
                                    echo $event_type;
                                    ?>" maxlength="128">
                                    <input type="hidden" value="<?php
                                    echo $id;
                                    ?>" name="catId" id="catId" />
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php
                                if (@file_get_contents(''.str_replace('admin/','',site_url()) .$map_icon.'') && $map_icon != '') {
                                    ?>
                                    <img height='50px' src="<?php
                                    echo str_replace('admin/','',site_url()) . $map_icon;
                                    ?>"/>
                                    <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label for="map_icon">Map Icon</label>
                                    <input class="form-control-file" name='map_icon' id="map_icon" aria-describedby="fileHelp" type="file">
                                    <small id="fileHelp" class="form-text text-muted">Please upload squre image only.</small>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <?php
                                if (@file_get_contents(''.str_replace('admin/','',site_url()) . $type_icon.'') && $type_icon != '') {
                                    ?>
                                    <img height='50px' src="<?php
                                    echo str_replace('admin/','',site_url()) . $type_icon;
                                    ?>"/>
                                    <?php
                                }
                                ?>
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
