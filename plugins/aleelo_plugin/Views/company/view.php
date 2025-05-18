    <div class="row">
  

        <div class="col-sm-12 col-lg-13">
            <div class="card">
                <ul data-bs-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                    <li class="title-tab">
                        <h4 class="pl15 pt10 pr15"><?php echo app_lang("company_setting"); ?></h4>
                    </li>
                    <li><a role="active" data-bs-toggle="tab" href="javascript:;" data-bs-target="#invoice-style-settings-tab"> <?php echo app_lang('invoice'); ?></a></li>
                    <li><a role="presentation" data-bs-toggle="tab" href="<?php echo_uri("company/estimate_company"); ?>" data-bs-target="#invoice-reminder-settings-tab"><?php echo app_lang('other'); ?></a></li>
                </ul>

                <div class="tab-content">
                    <div role="active" class="tab-pane fade active show" id="invoice-style-settings-tab">
                        <?php echo form_open(get_uri("company/save_invoice_settings"), array("id" => "invoice-settings-form", "class" => "general-form dashed-row", "role" => "form")); ?>
                        <div class="card-body">
                        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />          
                                    <div class="card-body post-dropzone">
                     

                            <div class="form-group">
                                <div class="row">
                                    <label for="invoice_color" class=" col-md-2"><?php echo app_lang('invoice_color'); ?></label>
                                    <div class=" col-md-10">
                                        <input type="color" 
                                        id="invoice_color"
                                         name="invoice_color"
                                         value="<?php echo $model_info->invoice_color;
                                          ?>" />
                                        <span class="ml10"><?php echo anchor("company", app_lang("change_invoice_logo")); ?></span>
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
                                        src="<?php echo get_file_uri($model_info->invoice_pdf_background_image); ?>"
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

        var dropzone = attachDropzoneWithForm("#invoice-settings-form", uploadUrl, validationUrl, {
            maxFiles: 1
        });

        var showHideInputFields = function() {
            var value = $("#invoice_number_format").val() || "",
                hasYear = value.includes("YEAR"),
                hasMonth = value.includes("MONTH"),
                $yearSection = $("#invoice-number-format-year-section"),
                $resetSection = $("#reset-invoice-number-section"),
                $initialNumber = $("#initial-number-of-the-invoice");


            // Check if the value includes "YEAR" and show/hide the year section accordingly
            if (hasYear) {
                $yearSection.removeClass("hide");
                $resetSection.removeClass("hide");
            }else{
                $resetSection.addClass("hide");
            }

            if (hasMonth) {
                $yearSection.removeClass("hide");
            }

            if (!hasYear && !hasMonth) {
                $yearSection.addClass("hide");
                $resetSection.addClass("hide");
            }

            // Check if the value does not include "YEAR" and show/hide the initial number section accordingly

            if ($("#reset_invoice_number_every_year").is(":checked")) {
                $initialNumber.addClass("hide");
            } else {
                $initialNumber.removeClass("hide");
            }
        }

        $("#invoice_number_format").on("input", function() {
            showHideInputFields();
        });

        showHideInputFields();

        $("#reset_invoice_number_every_year").click(function() {
            if ($("#invoice_number_format").val().includes("YEAR") && $(this).is(":checked")) {
                $("#initial-number-of-the-invoice").addClass("hide");
            } else {
                $("#initial-number-of-the-invoice").removeClass("hide");
            }
        });

        $(".invoice_number_format_variabls").click(function() {
            $("#invoice_number_format").val($("#invoice_number_format").val() + $(this).text());
            $("#invoice_number_format").focus();
            $("#invoice_number_format").trigger("input");
            setTimeout(function() {
                $("#invoice_prefix").focus();
            });
            setTimeout(function() {
                $("#invoice_number_format").focus();
            });

        });

        $("#invoice_number_format").on("input", function() {
            var inputValue = $(this).val();

            var duplicateVariablesFoundMsg = "<?php echo app_lang("please_do_not_use_duplicate_variables") ?>";
            $(this).attr("data-rule-noDuplicateVariables", inputValue);
            $(this).attr("data-msg-noDuplicateVariables", duplicateVariablesFoundMsg);

            var invalidSpecialCharMsg = "<?php echo app_lang("please_do_not_use_invalid_special_character") ?>";
            $(this).attr("data-rule-invalidSpecialChar", inputValue);
            $(this).attr("data-msg-invalidSpecialChar", invalidSpecialCharMsg);

            var mustUseSerialMsg = "<?php echo app_lang('please_use_any_serial') ?>";
            $(this).attr("data-rule-mustUseSerial", inputValue);
            $(this).attr("data-msg-mustUseSerial", mustUseSerialMsg);
        });

        // Initial preview generation
        generatePreview();

        // Call generatePreview function when input values change
        $("#invoice_prefix, #invoice_number_format").on("input", generatePreview);
    });

    $.validator.addMethod("mustUseSerial", function(value, element) {
        var serialVariables = ['{SERIAL}', '{2_DIGIT_SERIAL}', '{3_DIGIT_SERIAL}', '{4_DIGIT_SERIAL}', '{5_DIGIT_SERIAL}', '{6_DIGIT_SERIAL}'];
        return serialVariables.some(function(variable) {
            return value.includes(variable);
        });
    }, 'You must use one of the serial variables.');

    //Finding duplicate variables
    $.validator.addMethod("noDuplicateVariables",
        function(value, element) {
            var variables = value.match(/\{(.*?)\}/g);
            if (variables) {
                var uniqueVariables = new Set(variables);
                if (uniqueVariables.size === variables.length) {
                    var serialVariables = ['{SERIAL}', '{2_DIGIT_SERIAL}', '{3_DIGIT_SERIAL}', '{4_DIGIT_SERIAL}', '{5_DIGIT_SERIAL}', '{6_DIGIT_SERIAL}'];
                    var countSerial = 0;
                    variables.forEach(function(variable) {
                        if (serialVariables.includes(variable)) {
                            countSerial++;
                        }
                    });
                    return countSerial <= 1;
                }
            }

            return false;
        }, 'Duplicate variables found.');


    //Fiending invalid special character
    $.validator.addMethod("invalidSpecialChar",
        function(value, element) {
            var invalidChars = value.match(/[^a-zA-Z0-9\-_/:()#\\{}]|(?<!\{)(?<!YEAR)(?<!MONTH)(?<!SERIAL)(?<!2_DIGIT_SERIAL)(?<!3_DIGIT_SERIAL)(?<!4_DIGIT_SERIAL)(?<!5_DIGIT_SERIAL)(?<!6_DIGIT_SERIAL)\{(?!\w*\})|(?<!\})(?<!YEAR)(?<!MONTH)(?<!SERIAL)(?<!2_DIGIT_SERIAL)(?<!3_DIGIT_SERIAL)(?<!4_DIGIT_SERIAL)(?<!5_DIGIT_SERIAL)(?<!6_DIGIT_SERIAL)\}(?!\{)|(?<!\})(?<!YEAR)(?<!MONTH)(?<!SERIAL)(?<!2_DIGIT_SERIAL)(?<!3_DIGIT_SERIAL)(?<!4_DIGIT_SERIAL)(?<!5_DIGIT_SERIAL)(?<!6_DIGIT_SERIAL)(?<!\/)\//g); // Get all invalid special characters except those inside {}
            if (!invalidChars) {
                return true;
            }
        }, 'Invalid special character found.');


    // Function to generate preview based on input values
    function generatePreview() {
        var prefix = $("#invoice_prefix").val() || 'INVOICE #';
        var format = $("#invoice_number_format").val();

        // Ensure both prefix and format are defined
        if (prefix && format) {
            format = format.replace("{YEAR}", new Date().getFullYear());
            format = format.replace("{MONTH}", ('0' + (new Date().getMonth() + 1)).slice(-2));

            format = format.replace("{SERIAL}", ('1'));
            format = format.replace("{2_DIGIT_SERIAL}", ('01'));
            format = format.replace("{3_DIGIT_SERIAL}", ('001'));
            format = format.replace("{4_DIGIT_SERIAL}", ('0001'));
            format = format.replace("{5_DIGIT_SERIAL}", ('00001'));
            format = format.replace("{6_DIGIT_SERIAL}", ('000001'));

            $("#invoice-display-id-preview-section").text(prefix + format);
        } else {
            $("#invoice-display-id-preview-section").text('');
        }
    }
</script>