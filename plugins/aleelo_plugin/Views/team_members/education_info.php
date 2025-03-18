<div class="tab-content">
    <?php echo form_open(get_uri("team_member/save_education_info/" . $user_id), array("id" => "education-info-form", "class" => "education-form dashed-row white", "role" => "form")); ?>
    <input name="user_id" type="hidden" value="<?php echo $user_id; ?>" />
    <div class="card">
        <div class=" card-header">
            <h4> <?php echo app_lang('education_info'); ?></h4>
        </div>
        <div class="card-body">

            <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                <div class="row">
                    <label for="education_level" class="col-md-2"><?php echo 'Education Level'; ?></label>
                    <div class="col-md-10">
                        <?php
                        echo form_dropdown(array(
                            "id" => "education_level",
                            "name" => "education_level",
                            "class" => "form-control select2",
                            "value" => $education_info->education_level,
                            "placeholder" => 'Education Level'
                        ),$education_levels,[$education_info->education_level =>$education_levels[$education_info->education_level]]);//$education_levels[$user_info->education_level]);
                        ?>
                    </div>
                </div>
            </div>

            <div id="primary_school_section">

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="primary_school_name" class="col-md-2"><?php echo 'Primary School Name'; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "primary_school_name",
                                "name" => "primary_school_name",
                                "class" => "form-control",
                                "value" => $education_info->primary_school_name,
                                "placeholder" => 'Primary School Name',
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="primary_graduation_date" class="col-md-2"><?php echo 'Primary Graduation Date'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "primary_graduation_date",
                                "name" => "primary_graduation_date",
                                "class" => "form-control",
                                'value'=> $education_info->primary_graduation_date,
                                "placeholder" => 'Primary Graduation Date',
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            
            <div id="secondary_school_section">

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="secondary_school_name" class="col-md-2"><?php echo 'Secondary School Name'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "secondary_school_name",
                                "name" => "secondary_school_name",
                                "class" => "form-control",
                                "value" => $education_info->secondary_school_name,
                                "placeholder" => 'Secondary School Name',
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="secondary_graduation_date" class=" col-md-2"><?php echo 'Secondary Graduation Date'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "secondary_graduation_date",
                                "name" => "secondary_graduation_date",
                                "class" => "form-control",
                                'value'=> $education_info->secondary_graduation_date,
                                "placeholder" => 'Secondary Graduation Date',
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <div id="diploma_section">

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="university_name_diploma" class=" col-md-2"><?php echo 'University Name Diploma'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_dropdown(array(
                                "id" => "university_name_diploma",
                                "name" => "university_name_diploma",
                                "class" => "form-control select2",
                                "placeholder" => 'University Name Diploma',
                            ),$university_names,[$education_info->university_name_diploma]); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="field_of_study_diploma" class=" col-md-2"><?php echo 'Field of Study Diploma'; ?></label>
                        <div class=" col-md-10">
                        <?php 
                        echo form_dropdown(array( 
                                'id'=> "field_of_study_diploma",
                                'name'=> "field_of_study_diploma",
                                'class' => "form-control select2",
                                'placeholder' => 'Field of Study Diploma',
                            ),$field_of_study,[$education_info->field_of_study_diploma]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_diploma" class=" col-md-2"><?php echo 'Graduation Date Diploma'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_diploma",
                                "name" => "graduation_date_diploma",
                                "class" => "form-control date",
                                'value'=> $education_info->graduation_date_diploma,
                                "placeholder" => 'Graduation Date Diploma',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <div id="foculty_1_section">

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="university_name_foculty_1" class=" col-md-2"><?php echo 'University Name Foculty'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_dropdown(array(
                                "id" => "university_name_foculty_1",
                                "name" => "university_name_foculty_1",
                                "class" => "form-control select2",
                                "placeholder" => 'University Name Foculty',
                            ),$university_names,[$education_info->university_name_foculty_1]); ?>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="field_of_study_foculty_1" class=" col-md-2"><?php echo 'Field of Study Foculty'; ?></label>
                        <div class=" col-md-10">
                        <?php 
                        echo form_dropdown(array( 
                                'id'=> "field_of_study_foculty_1",
                                'name'=> "field_of_study_foculty_1",
                                'class' => "form-control select2",
                                'placeholder' => 'Field of Study Foculty',
                                "autocomplete" => "off"
                            ),$field_of_study,[$education_info->field_of_study_foculty_1]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_foculty_1" class="col-md-2"><?php echo 'Graduation Date Foculty'; ?></label>
                        <div class="col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_foculty_1",
                                "name" => "graduation_date_foculty_1",
                                "class" => "form-control",
                                'value'=> $education_info->graduation_date_foculty_1,
                                "placeholder" => 'Graduation Date Foculty',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                </div>

            <div id="foculty_2_section">
            
                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="university_name_foculty_2" class=" col-md-2"><?php echo 'University Name Faculty 2'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_dropdown(array(
                                "id" => "university_name_foculty_2",
                                "name" => "university_name_foculty_2",
                                "class" => "form-control select2",
                                "placeholder" => 'University Name Faculty 2',
                            ),$university_names,[$education_info->university_name_foculty_2]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="field_of_study_foculty_2" class=" col-md-2"><?php echo 'Field of Study Foculty 2'; ?></label>
                        <div class=" col-md-10">
                        <?php 
                        echo form_dropdown(array( 
                                'id'=> "field_of_study_foculty_2",
                                'name'=> "field_of_study_foculty_2",
                                'class' => "form-control select2",
                                'placeholder' => 'Field of Study Foculty 2',
                            ),$field_of_study,[$education_info->field_of_study_foculty_2]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_foculty_2" class=" col-md-2"><?php echo 'Graduation Date Foculty 2'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_foculty_2",
                                "name" => "graduation_date_foculty_2",
                                "class" => "form-control date",
                                'value'=> $education_info->graduation_date_foculty_2,
                                "placeholder" => 'Graduation Date Foculty 2',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <div id="master_1_section">

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="university_name_master_1" class=" col-md-2"><?php echo 'University Name Master'; ?></label>
                        <div class=" col-md-10">
                            <?php 
                            echo form_dropdown(array(
                                "id" => "university_name_master_1",
                                "name" => "university_name_master_1",
                                "class" => "form-control select2",
                                "placeholder" => 'University Name Master',
                                "autocomplete" => "off",
                            ),$university_names,[$education_info->university_name_master_1]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="field_of_study_master_1" class=" col-md-2"><?php echo 'Field of Study Master'; ?></label>
                        <div class=" col-md-10">
                        <?php 
                        echo form_dropdown(array( 
                                'id'=> "field_of_study_master_1",
                                'name'=> "field_of_study_master_1",
                                'class' => "form-control select2",
                                'placeholder' => 'Field of Study Master',
                                "autocomplete" => "off"
                            ),$field_of_study,[$education_info->field_of_study_master_1]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_master_1" class=" col-md-2"><?php echo 'Graduation Date Master'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_master_1",
                                "name" => "graduation_date_master_1",
                                "class" => "form-control date",
                                'value'=> $education_info->graduation_date_master_1,
                                "placeholder" => 'Graduation Date Master',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <div id="master_2_section">
            
                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="university_name_master_2" class=" col-md-2"><?php echo 'University Name Master 2'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_dropdown(array(
                                "id" => "university_name_master_2",
                                "name" => "university_name_master_2",
                                "class" => "form-control select2",
                                "placeholder" => 'University Name Master 2',
                            ),$university_names,[$education_info->university_name_master_2]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="field_of_study_master_2" class=" col-md-2"><?php echo 'Field of Study Master 2'; ?></label>
                        <div class=" col-md-10">
                        <?php 
                        echo form_dropdown(array( 
                                'id'=> "field_of_study_master_2",
                                'name'=> "field_of_study_master_2",
                                'class' => "form-control select2",
                                'placeholder' => 'Field of Study Master 2',
                            ),$field_of_study,[$education_info->field_of_study_master_2]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_master_2" class=" col-md-2"><?php echo 'Graduation Date Master 2'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_master_2",
                                "name" => "graduation_date_master_2",
                                "class" => "form-control date",
                                'value'=> $education_info->graduation_date_master_2,
                                "placeholder" => 'Graduation Date Master 2',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <div id="php_section">
            
            <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="university_name_phd" class=" col-md-2"><?php echo 'University Name PHD'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_dropdown(array(
                                "id" => "university_name_phd",
                                "name" => "university_name_phd",
                                "class" => "form-control select2",
                                "placeholder" => 'University Name PHD',
                                "autocomplete" => "off",
                            ),$university_names,[$education_info->university_name_phd]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="field_of_study_phd" class=" col-md-2"><?php echo 'Field of Study PHD'; ?></label>
                        <div class=" col-md-10">
                        <?php 
                        echo form_dropdown(array( 
                                'id'=> "field_of_study_phd",
                                'name'=> "field_of_study_phd",
                                'class' => "form-control select2",
                                'placeholder' => 'Field of Study PHD',
                            ),$field_of_study,[$education_info->education_level]); ?>
                        </div>
                    </div>
                </div> 

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_phd" class=" col-md-2"><?php echo 'Graduation Date PHD'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_phd",
                                "name" => "graduation_date_phd",
                                "class" => "form-control date",
                                'value'=> $education_info->graduation_date_phd,
                                "placeholder" => 'Graduation Date PHD',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <div id="other_skills_section">

                <div class="form-group" style="border-bottom:none; padding-bottom: 15px;">
                    <div class="row">
                        <label for="other_skills" class=" col-md-2"><?php echo 'Other Skills'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_textarea(array(
                                "id" => "other_skills",
                                "name" => "other_skills",
                                "value" => $education_info->other_skills,
                                "class" => "form-control",
                                "placeholder" => 'Other Skills'
                            ));
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="border-bottom: 1px dashed #e2e4e8; padding-bottom: 15px;">
                    <div class="row">
                        <label for="graduation_date_other_skills" class=" col-md-2"><?php echo 'Graduation Date Other Skills'; ?></label>
                        <div class=" col-md-10">
                            <?php
                            echo form_input(array(
                                "id" => "graduation_date_other_skills",
                                "name" => "graduation_date_other_skills",
                                "class" => "form-control date",
                                'value'=> $education_info->graduation_date_other_skills,
                                "placeholder" => 'Graduation Date PHD',
                                "autocomplete" => "off"
                            ));
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <?php echo view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-2", "field_column" => " col-md-10")); ?> 

        </div>
        <?php if($can_edit_profile){?>
            <div class="card-footer rounded-0">
                <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo app_lang('save'); ?></button>
            </div>
        <?php }?>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        $("#education-info-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                appAlert.success(result.message, {duration: 10000});
                setTimeout(function () {
                    window.location.href = "<?php echo get_uri("team_member/view/" . $user_id); ?>" + "/education_info";
                    // window.location.reload();
                }, 500);

            }
        });
        
        $("#education-info-form .select2").select2();

        setDatePicker("#primary_graduation_date");
        setDatePicker("#secondary_graduation_date");
        setDatePicker("#graduation_date_diploma");
        setDatePicker("#graduation_date_foculty_1");
        setDatePicker("#graduation_date_foculty_2");
        setDatePicker("#graduation_date_master_1");
        setDatePicker("#graduation_date_master_2");
        setDatePicker("#graduation_date_phd");
        setDatePicker("#graduation_date_other_skills");

        // Initially hide all sections
        function hideAllSections() {
            $('#primary_school_section').hide();
            $('#secondary_school_section').hide();
            $('#diploma_section').hide();
            $('#foculty_1_section').hide();
            $('#foculty_2_section').hide();
            $('#master_1_section').hide();
            $('#master_2_section').hide();
            $('#php_section').hide();
            $('#other_skills_section').hide();
        }

    // Call this function whenever the education level changes
    $('#education_level').on('change', function () {
        var educationLevel = $(this).val();

        // Hide all sections first
        hideAllSections();

        // Show the appropriate section(s) based on the selected education level
        switch (educationLevel) {
            case 'Primary':
                $('#primary_school_section').show();
                break;
            case 'Secondary':
                $('#secondary_school_section').show();
                break;
            case 'Diploma':
                $('#diploma_section').show();
                break;
            case 'Bachelor':
                $('#foculty_1_section').show();
                break;
            case 'Bachelor & Master':
                $('#foculty_1_section').show();
                $('#master_1_section').show();
                break;
            case '2 Bachelors':
                $('#foculty_1_section').show();
                $('#foculty_2_section').show();
                break;
            case '2 Bachelors & Master':
                $('#foculty_1_section').show();
                $('#foculty_2_section').show();
                $('#master_1_section').show();
                break;
            case '2 Bachelors & 2 Masters':
                $('#foculty_1_section').show();
                $('#foculty_2_section').show();
                $('#master_1_section').show();
                $('#master_2_section').show();
                break;
            case 'Doctor':
                $('#php_section').show();
                break;
            case 'Other/Skill':
                $('#other_skills_section').show();
                break;
            default:
                hideAllSections(); // If no valid selection is made, hide all sections
        }
    });

    // Trigger change on page load to set the correct visibility based on any pre-selected value
    $('#education_level').trigger('change');

    });
</script>    