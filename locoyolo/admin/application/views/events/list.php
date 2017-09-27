
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar"></i> Event Management

      </h1>
    </section>
    <section class="content">

        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNew"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Event List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>events" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>S.no</th>
                      <th>Name</th>
                      <th>Date</th>

                      <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($eventRecords))
                    {$sno=1;
                        foreach($eventRecords as $record)
                        {
                    ?>
                   <tr id="row-<?php  echo $record->id; ?>">
                      <td><?php echo $sno;$sno++; ?></td>
                      <td class="name"><?php echo $record->event_name ?></td>
                      <td><?php echo date('d M Y H:i A',strtotime($record->start_date)) ?></td>

                      <td class="text-center">
                          <a class="btn btn-sm btn-danger" title="Suspend"><i class="glyphicon glyphicon-pause"></i></a>
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'event/view/'.$record->id; ?>"><i class="glyphicon glyphicon-eye-open"></i></a>
                          <a class="btn btn-sm btn-danger deleteUser" data-toggle="modal" data-target="#confirm-delete" href="#" data-id="<?php echo $record->id; ?>"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", "<?php echo site_url();?>" + "events/" + value);
            jQuery("#searchList").submit();
        });
    });

    $('.deleteUser').click(function() {
        $('#confirm-delete .modal-title').text('Confirm Delete');
        var name = $(this).parent().parent().find('td.name').text();
        var id = $(this).attr('data-id');
        $('#confirm-delete #delete_id').val(id);
        $('#confirm-delete #delete_name').val(name);
        $('#confirm-delete .confirm').css('display','block');
        $('#confirm-delete .success').css('display','none');
        $('#confirm-delete .modal-body .confirm p').html('Are you sure you want to delete '+name+'?');

    });
    $('.deleteUserbtn').click(function() {
        var name = $('#confirm-delete #delete_name').val();
        var id =  $('#confirm-delete #delete_id').val();
        $('#confirm-delete .overlay').css('display','block');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('deleteEvent')?>",
            data: {id:id}
        }).done(function( res ) {

            if(res=='Y')
            {

                $('#confirm-delete .modal-body .success p').html('<i class="fa fa-check" style="color:#00a65a !important;font-size:15px"></i> '+name+' has been deleted');
                $('#confirm-delete .confirm').css('display','none');
                $('#confirm-delete .overlay').css('display','none');
                $('#confirm-delete .success').css('display','block');
                $('tr#row-'+id).remove();
            }
        });
    });
</script>
