<?php echo form_open(get_uri("Screen_size/save"), array("id" => "note-form", "class" => "general-form", "role" => "form")); ?>
<div id="notes-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <div class="container-fluid">
            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
            <input type="hidden" id="is_grid" name="is_grid" value="" />
       
<div class="form-group">
    <div class="row">
        <label for="name" class="<?php echo $label_column; ?>"><?php echo app_lang('screen_size'); ?></label>
        <div class="<?php echo $field_column; ?>">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info->screen_size	 ? $model_info->screen_size	 : "",
                "class" => "form-control",
                "placeholder" => app_lang('name')
            ));
            ?>

        </div>
    </div>
</div>









    <div class="modal-footer">
      <!--  <?php echo view("includes/upload_button", array("show_link_copy_button" => true)); ?>
        -->
        <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
      
        $("#note-form").appForm({
            onSuccess: function(result) {
                if (window.isNoteGridView) {
                    var $noteGrid = $("#note-grid-" + result.id);
                    if ($noteGrid.length) {
                        // editing existing note
                        $noteGrid.html(result.data);
                    } else {
                        // adding new note
                        $(".notes-grid-container .row").prepend("<div id='note-grid-" + result.id + "' class='col-md-3 col-sm-6'>" + result.data + "</div>");
                    }
                } else {
                    $("#note-table").appTable({
                        newData: result.data,
                        dataId: result.id
                    });
                }
            }
        });
      
        $("#mark_as_public").click(function() {
            if ($(this).is(":checked")) {
                $("#mark_as_public_help_message").removeClass("hide");
            } else {
                $("#mark_as_public_help_message").addClass("hide");
            }
        });

       
    });
</script>