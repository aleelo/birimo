<div class="tab-content">
    <?php echo form_open(get_uri("team_members/save_job_info/". $user_id), array("id" => "job-info-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

    <input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
    <div id="team-dropzone" class="post-dropzone">
        <div class="card">

            <div class="card-header">
                <h4><?php echo app_lang('job_info'); ?></h4>
            </div>
            <div class="card-body">
           <?php  if($login_user->is_admin || $can_manage_team_members_job_information) {?>
            <div class="form-group">
                    <div class="row">
                        <label for="company" class="col-md-2"><?php echo app_lang('company'); ?></label>
                        <div class="col-md-10">
                        <?php
                        echo form_dropdown(
                            "company",
                            $company_dropdown,
                            $job_info->company_id, // Set the selected value here
                            "class='select2' id='company' data-allow-clear='true' data-no-results-text='" . app_lang('no_result_found') . "' data-width='100%'"
                        ); ?>
                            <div id="user-role-help-block" class="help-block ml10 hide"><i data-feather="alert-triangle" class="icon-16 text-warning"></i> <?php echo app_lang("admin_user_has_all_power"); ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group">
                <div class="row">
                    <label for="job_title" class=" col-md-2"><?php echo app_lang('job_title'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "job_title",
                            "name" => "job_title",
                            "value" => $job_info->job_title_en,
                            "class" => "form-control",
                            "placeholder" => app_lang('job_title')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="salary" class=" col-md-2"><?php echo app_lang('salary'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "salary",
                            "name" => "salary",
                            "value" => $job_info->salary ? to_decimal_format($job_info->salary) : "",
                            "class" => "form-control",
                            "placeholder" => app_lang('salary')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="salary_term" class=" col-md-2"><?php echo app_lang('salary_term'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "salary_term",
                            "name" => "salary_term",
                            "value" => $job_info->salary_term,
                            "class" => "form-control",
                            "placeholder" => app_lang('salary_term')
                        ));
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <label for="date_of_hire" class=" col-md-2"><?php echo app_lang('date_of_hire'); ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_input(array(
                            "id" => "date_of_hire",
                            "name" => "date_of_hire",
                            "value" => $job_info->date_of_hire,
                            "class" => "form-control",
                            "placeholder" => app_lang('date_of_hire'),
                            "autocomplete" => "off"
                        ));
                        ?>
                    </div>
                </div>
            </div>
        </div>
            <div class="form-group">
    <div class="row">
        <label class="col-md-2 "><?php echo app_lang('has_signature'); ?></label>
        <div class="col-md-10 ">
            <?php
            
            echo form_dropdown("signature_type", array(
                ""=> "Select Signature Type",
                "image" => "Upload Image",
                "digital" => "Digital Signature",
            ), "", "class='form-control select2'  id='signature_type'");
            ?>
        </div>
    </div>
</div>


<div id="signature_image_field" class="form-group hide">
    <div class="row">
        <label class="col-md-3 col-xs-5 col-sm-4"><?php echo app_lang ('Upload_Signature_Image')?></label>
        <div class="col-md-6 col-xs-7 col-sm-8">
        <button class="btn btn-default upload-file-button float-start me-auto btn-sm round" type="button" style="color:#7988a2"><i data-feather="camera" class="icon-16"></i> <?php echo app_lang("upload_file"); ?></button>
        <?php echo view("includes/dropzone_preview");?>
        </div>
    </div>
</div>
<div id="has" class="form-group">
<div class="row">
            <div class="col-md-6 col-xs-7 col-sm-8">

            <?php 


// Try the first method
if (!empty($job_info->signature)) {
    $signature_data = @unserialize($job_info->signature);

    if ($signature_data !== false && !empty($signature_data[0]['file_name'])) {
        // Handle serialized signature data
        $signature_file_name = $signature_data[0]['file_name'];
    } else {
        // Handle direct file path
        $signature_file_name = $job_info->signature;
    }

    // Construct the full path to the signature file
    $signature_path = FCPATH . 'files/signature/' . $signature_file_name;

    // Check if the file exists
    if (file_exists($signature_path)) {
        // Display the signature image
        echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style="width: 150px; height: auto;">';
    } else {
        // File not found, try the second method
        $signature_data = @unserialize($job_info->signature);

        if (!empty($signature_data['file_name'])) {
            $signature_file_name = $signature_data['file_name'];

            $signature_path = FCPATH . 'files/signature/' . $signature_file_name;

            if (file_exists($signature_path)) {
                echo '<img src="' . base_url('files/signature/' . $signature_file_name) . '" alt="Signature" style="width: 150px; height: auto;">';
            } else {
                echo '<p>Signature file not found.</p>';
            }
        } else {
            echo '<p>Signature file not found.</p>';
        }
    }
} else {
    echo '<p>No signature available.</p>';
}?>
</div>
</div>


<div id="signature_digital_field" class="form-group hide">
    <div class="row">
        <label class="col-md-3 col-xs-5 col-sm-4"><?php echo app_lang('digital_signature'); ?></label>
        <div class="col-md-6 col-xs-7 col-sm-8">
            <canvas id="signature-pad" class="signature-pad" width="400" height="200"></canvas>
            <input type="hidden" name="digital_signature" id="signature" />
        </div>
    </div>
</div>


            <?php if ($login_user->is_admin || $can_manage_team_members_job_information) { ?>
                <div class="card-footer rounded-0">
                    <button type="submit" id="save-signature" class="btn btn-primary">
                        <span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?>
                    </button>
                </div>
            <?php } ?>

        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    var signaturePad = new SignaturePad(document.getElementById('signature-pad'));
   
    $("#signature_type").change(function () {
        if ($(this).val() === "digital") {
            $("#signature_image_field").addClass("hide");
            $("#signature_digital_field").removeClass("hide");
        } else {
            $("#signature_digital_field").addClass("hide");
            $("#signature_image_field").removeClass("hide");
        }
    });
    $("#job-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                window.location.href = "<?php echo get_uri("team_members/view/" . $job_info->user_id); ?>" + "/job_info";
            }
        });
    $("#job-info-form .select2").select2();
    var uploadUrl = "<?php echo get_uri("team_members/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("team_members/validate_team_file"); ?>";
        var dropzone = attachDropzoneWithForm("#team-dropzone", uploadUrl, validationUri);
        setDatePicker("#date_of_hire");

    $('#save-signature').click(function (e) {
        if ($("#signature_type").val() === "digital") {
            if (!signaturePad.isEmpty()) {
                $('#signature').val(signaturePad.toDataURL());
            } else {
                alert("Please provide your digital signature.");
                e.preventDefault();
            }
        }
    });
});

</script>
