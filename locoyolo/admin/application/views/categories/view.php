<?php
$catInfo = $catInfo[0];
?>
<div class="box box-solid">
    <div class="box-header with-border">
<div class="col-sm-10">
        <h3>
            <?php echo $catInfo->event_type;?>
        </h3>
    </div>
        <div class="col-sm-2">
            <br>
        <button  class="btn btn-danger" data-id='<?php echo $catInfo->id;?>'  data-toggle="modal" data-target="#confirm-suspend"  id="suspendevent">Suspend</button>
        <a  class="btn btn-warning" href="<?php echo site_url('categories')?>">Back</a>
    </div></div>
    <!-- /.box-header -->
    <div class="box-body">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
        <!-- Default box -->
        <table class="table table-bordered">
            <tr><td>Category Name</td>
            <td><?php echo $catInfo->event_type;?></td></tr>
            </table>
        <?php
        if(file_get_contents(str_replace('admin/','',site_url()).$catInfo->map_icon) && $catInfo->map_icon!=''){?>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Map icon</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <img height='50' src="<?php echo str_replace('admin/','',site_url()).$catInfo->map_icon?>">
            </div>
            <!-- /.box-body -->
        </div>
        <?php }?>

        <?php
        if(file_get_contents(str_replace('admin/','',site_url()).$catInfo->type_icon) && $catInfo->type_icon!=''){?>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Type icon</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <img height='50' src="<?php echo str_replace('admin/','',site_url()).$catInfo->type_icon?>">
            </div>
            <!-- /.box-body -->
        </div>
        <?php }?>






        <!-- /.box -->
</div></div>


<!-- /.content -->
