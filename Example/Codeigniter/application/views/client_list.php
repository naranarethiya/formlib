<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                 <div class="box-title">
                    <a href="<?php echo base_url().'client/client_addform' ?>" id="add_new" class="btn btn-sm btn-primary" title="Add New"><i class="fa fa-plus"></i> Add</a>
                    <a href="#" id="edit_selected" class="btn btn-sm btn-primary" title="Edit selected"><i class="fa fa-pencil-square-o "></i> Edit</a>
                    <a href="#" id="delete_selected" class="btn btn-sm btn-primary" title="Delete Selected"><i class="fa fa-trash-o"></i> Delete</a>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>Email</th>
                            <th>Company Type</th>
                            <th>Place</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($client as $key => $client_row) { ?>
                        <tr id="tr<?php echo $client_row['client_id'] ?>">
                            <td><input type="checkbox" name="row_id[]" value="<?php echo $client_row['client_id'] ?>"></td>
                            <td><?php echo $client_row['client_name'] ?></td>
                            <td><?php echo $client_row['email'] ?></td>
                            <td><?php echo $client_row['company_type'] ?></td>
                            <td><?php echo $client_row['client_city'].', '.$client_row['client_zip'].', '.$client_row['client_country']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

<script type="text/javascript">
    function get_selected(selector) {
        var checked_list = [];
        if(!selector) {
            selector=$('input:checkbox[name^=row_id]:checked');
        }
        $(selector).each(function() {
            checked_list.push($(this).val());
        })
        return checked_list;
    }


    $(document).ready(function() {

        /* Click on delete */
        $('#delete_selected').click(function () {
            var id=get_selected();
            if(id.length<1) {
                swal('Please select at least 1 record');
            }
            else {
                if(confirm("Are you sure to delete records")) {  
                    $.ajax({
                        url     : base_url+"client/client_delete",
                        type    : 'POST',
                        data    : {'id':id},
                        success : function(data){
                            data=$.parseJSON(data);
                            remove_loading();
                            if(data.status == '1') {
                                alert("Deleted!", data.message, "success");
                                location.reload();
                            }
                            else {
                                alert("Oops...", data.message, "error");
                            }
                        }
                    });
                }
            }
        }); 

        /* Edit action */

        $('#edit_selected').click(function () {
            var id=get_selected();
            if(id.length>1) {
                alert('Only one record allowed to edit at a time');
            }
            else if(id.length<1) {
                alert('Select at-least one record');
            }
            else {
                window.location=base_url+"client/client_addform/"+id;  
            }
        });
    });

</script>