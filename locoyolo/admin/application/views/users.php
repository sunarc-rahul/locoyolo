
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> User Management

      </h1>
    </section>
    <section class="content">
<?php if($this->session->flashdata('error')){?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <p><?php echo $this->session->flashdata('error');?></p>
            </div>
        <?php } ?>
<?php if($this->session->flashdata('success')){?>
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <p><?php echo $this->session->flashdata('success');?></p>
            </div>
        <?php } ?>
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
                    <h3 class="box-title">Users List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>users" method="POST" id="searchList">
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
                      <th>S. No.</th>
                      <th>Name</th>
                      <th>Email</th>

                      <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($userRecords))
                    {
                        if($page>0)
                        {
                            $sno = $page+1;
                        }else
                        {
                            $sno = 1;
                        }
                        foreach($userRecords as $record)
                        {
                    ?>
                    <tr id="row-<?php  echo $record->id; ?>">
                      <td><?php echo $sno; $sno++; ?></td>
                      <td class="name"><?php echo $record->name ?></td>
                      <td><?php echo $record->email ?></td>

                      <td class="text-center">
                          <a class="btn btn-sm btn-info" href="<?php echo base_url().'user/edit/'.$record->id; ?>"><i class="fa fa-pencil"></i></a>
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
        $('ul.pagination li a').click(function (e) {
            e.preventDefault();
            var link = $(this).get(0).href;
            var value = link.substring(link.lastIndexOf('/') + 1);
            $("#searchList").attr("action", "<?php echo site_url("users/")?>"+value);
            $("#searchList").submit();
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
            url: "<?php echo site_url('deleteUser')?>",
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
