    <div class="row">


        <div class="col-sm-12 col-lg-13">
            <div class="card">
                <ul data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li class="title-tab">
                        <h4 class="pl15 pt10 pr15"><?php echo app_lang("company_setting"); ?></h4>
                    </li>
                    <!-- <li><a role="active" data-bs-toggle="tab" href="javascript:;" data-bs-target="#invoice-style-settings-tab"> <?php echo app_lang('invoice'); ?></a></li>
                    <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("company/estimate_company"); ?>" data-bs-target="#invoice-reminder-settings-tab"><?php echo app_lang('other'); ?></a></li> -->
                </ul>

                <div class="tab-content">
                    <div role="active" class="tab-pane fade active show" id="invoice-style-settings-tab">
                        <?php echo form_open(get_uri("company/save_invoice_settings"), array("id" => "invoice-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                        <div class="card-body">
                            <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
                            <div class="card-body post-dropzone">
<div id="company_icon-dropzone" class="post-dropzone">


                                <div class="form-group">
                                    <div class="row">
                                        <label for="invoice_color" class=" col-md-2"><?php echo app_lang('invoice_color'); ?></label>
                                        <div class=" col-md-10">
                                            <input type="color"
                                                id="invoice_color"
                                                name="invoice_color"
                                                value="<?php echo $model_info->invoice_color;
                                                        ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="section_background" class=" col-md-2"><?php echo ('section background'); ?></label>
                                        <div class=" col-md-10">
                                            <input type="color"
                                                id="section_background"
                                                name="section_background"
                                                value="<?php echo $model_info->section_background;
                                                        ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="invoice_item_list_background" class="col-md-2"><?php echo app_lang('invoice_item_list_background_color'); ?> </label>
                                        <div class=" col-md-10">
                                            <input type="color" id="invoice_item_list_background" name="invoice_item_list_background" value="<?php echo $model_info->invoice_item_list_background; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <label for="enable_background_image_for_invoice_pdf" class="col-md-2"><?php echo app_lang('enable_background_image_for_pdf'); ?> </label>
                                        <div class="col-md-10">
                                            <?php
                                            echo form_checkbox("enable_background_image_for_invoice_pdf", "1", $model_info->enable_background_image_for_invoice_pdf ? true : false, "id='enable_background_image_for_invoice_pdf' class='form-check-input'");
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="related_to_pdf_background_setting form-group <?php echo $model_info->enable_background_image_for_invoice_pdf ? "" : "hide" ?>">
                                    <div class="form-group">
                                        <div class="row">
                                            <?php if ($model_info->invoice_pdf_background_image) { ?>
                                                <input type="hidden" name="existing_invoice_pdf_background_image" value="<?php echo $model_info->invoice_pdf_background_image; ?>" />
                                            <?php } ?>
                                            <label class="col-md-2"><?php echo app_lang('pdf_background_image'); ?></label>
                                            <div class="col-md-10">
                                                <?php if ($model_info->invoice_pdf_background_image) { ?>
                                                    <div class="float-start mr15">
                                                        <img id="pdf-background-image-preview"
                                                            style="max-width: 55px; max-height: 80px;"
                                                            src="<?php echo get_file_from_setting($model_info->invoice_pdf_background_image); ?>"
                                                            alt="PDF background image" />
                                                    </div>
                                                <?php } ?>

                                                <div class="float-start file-upload btn btn-default btn-sm">
                                                    <i data-feather="upload" class="icon-14"></i> <?php echo app_lang("upload"); ?>
                                                    <input id="invoice_pdf_background_image"
                                                        class="upload"
                                                        name="invoice_pdf_background_image"
                                                        type="file"
                                                        data-preview-container="#pdf-background-image-preview" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

            <div class="form-group">
                <div class="row">
                    <label for="company_logo" class="col-md-3 mt10"><?php echo app_lang('company_logo'); ?> (300x100) </label>
                    <div class="col-md-9">
                        <div class="float-start mr15">
                            <?php echo get_company_logo($model_info->id,true); ?>
                        </div>
                        <div class="float-start mr15">
                            <?php echo view("includes/dropzone_preview"); ?>
                        </div>
                        <div class="float-start upload-file-button btn btn-default btn-sm">
                            <span>...</span>
                        </div>
                    </div>
                </div>
            </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <label for="invoice_footer" class=" col-md-2"><?php echo app_lang('invoice_footer'); ?></label>
                                    <div class=" col-md-10">
                                        <?php
                                        echo form_textarea(array(
                                            "id" => "invoice_footer",
                                            "name" => "invoice_footer",
                                            "value" => process_images_from_content($model_info->invoice_footer, false),
                                            "class" => "form-control",
                                            "data-toolbar" => "pdf_friendly_toolbar",
                                            "data-height" => 100,
                                            "data-encode_ajax_post_data" => "1"
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><span data-feather='check-circle' class="icon-16"></span> <?php echo app_lang('save'); ?></button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="invoice-general-settings-tab"></div>
                    <div role="tabpanel" class="tab-pane fade" id="invoice-reminder-settings-tab"></div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <?php echo view("includes/cropbox"); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#invoice-settings-form").appForm({
                isModal: false,
                onSuccess: function(result) {
                    if (result.success) {
                        appAlert.success(result.message, {
                            duration: 10000
                        });
                    } else {
                        appAlert.error(result.message);
                    }
                }
            });

            $("#invoice-settings-form .select2").select2();

            initWYSIWYGEditor("#invoice_footer");

            $(".cropbox-upload").change(function() {
                showCropBox(this);
            });

            $(".invoice-styles .item").click(function() {
                $(".invoice-styles .item").removeClass("active");
                $(".invoice-styles .item .selected-mark").addClass("hide");
                $(this).addClass("active");
                $(this).find(".selected-mark").removeClass("hide");
                $("#invoice_style").val($(this).attr("data-value"));
            });

            $('[data-bs-toggle="tooltip"]').tooltip();

            $("#enable_background_image_for_invoice_pdf").click(function() {
                if ($(this).is(":checked")) {
                    $(".related_to_pdf_background_setting").removeClass("hide");
                } else {
                    $(".related_to_pdf_background_setting").addClass("hide");
                }
            });

            var uploadUrl = "<?php echo get_uri("uploader/upload_file"); ?>";
            var validationUrl = "<?php echo get_uri("uploader/validate_file"); ?>";

            var dropzone = attachDropzoneWithForm("#company_icon-dropzone", uploadUrl, validationUrl, {
                maxFiles: 1
            });


   

        // Function to generate preview based on input values
        });
    </script>