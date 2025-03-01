<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo view('includes/head'); ?>
    </head>
    <body>

    <div class="form-signin">
    <div class="card bg-white mb15" style="height: 370px;">
        <div class="card-header text-center">
            <?php if (get_setting("show_logo_in_signin_page") === "yes") {?>
                <img class="p20 mw100p" src="<?php echo get_logo_url(); ?>" />
            <?php } else {?>
                <img src="<?php echo base_url() . 'assets/images/sys-logo.png'; ?>" width="300">
            <?php }?>
        </div>
        <div class="card-body p30 rounded-bottom">

            <div class="form-group">

                <?php echo form_open("signin/authenticate_local", array("id" => "signin-form", "class" => "general-form", "role" => "form")); ?>

                <?php
                    $session = \Config\Services::session();
                    $signin_validation_errors = $session->getFlashdata("signin_validation_errors");
                    if ($signin_validation_errors && is_array($signin_validation_errors)) {
                        ?>
                    <div class="alert alert-danger" role="alert">
                        <?php foreach ($signin_validation_errors as $validation_error) {?>
                            <i data-feather="alert-circle" class="icon-16"></i>
                            <?php echo $validation_error; ?>
                            <br />
                        <?php }?>
                    </div>
                <?php }?>


                <div class="scrollable-page">
                    <div class="form-signin">

                    <div class="w-100 mb-3 d-flex ">
                        <div class="w-45" style="height: 1px;background: #bec3d0;width: 35%;"></div>
                            <div class="mx-3  fs-5 d-flex fs-3" style="margin-top: -15px;">

                                Continue
                            </div>
                        <div class="w-45 " style="height: 1px;background: #bec3d0;width: 35%;"></div>
                    </div>

                            <div class="d-flex justify-content-center mb-3">
                                <div class="flex badge badge-secondary  bg-secondary fs-5"><?php echo isset($_GET['email']) ? $_GET['email'] : '';?></div>
                            </div>
                        <div class="form-group" id="password_div">
                            
                        <input type="hidden" name="email" id="email" value="<?php echo isset($_GET['email']) ? $_GET['email'] : ''; ?>">

                            <?php
                                echo form_password(array(
                                    "id" => "password",
                                    "name" => "password",
                                    "class" => "form-control p10",
                                    "placeholder" => app_lang('password'),
                                    "data-rule-required" => true,
                                    "data-msg-required" => app_lang("field_required"),
                                ));
                                ?>
                        </div>
                    </div>
                    <button type = "submit" name="azure-login-btn" id="normal-login-btn" class="btn btn-primary btn-lg w-100 mb-4" >

                        <!-- <img src="<?php echo base_url() . 'assets/images/sys-logo.png'; ?>" alt=""> -->
                        Continue
                    </button>
                </div>
                <div class="mt5"><?php echo anchor("signin/request_reset_password", app_lang("forgot_password")); ?></div>


                <?php
                app_hooks()->do_action('app_hook_signin_extension');
                ?>


            <?php echo form_close(); ?>
            </div>
        </div>
    </div>
 </div>


        <script>
            $(document).ready(function () {

                // function get_login_defaults() {
                //     if($('#login_type').val() == 'Azure Login'){

                //         $('#password').hide();
                //         $('#retype_password').hide();
                //         $('#password').val('aleelo');
                //         $('#retype_password').val('aleelo');

                //         $('#email').attr('placeholder','Enter Microsoft Email');
                        

                //     }else{

                //         $('#password').show();
                //         $('#password').val('');
                //         $('#retype_password').show();
                //         $('#retype_password').val('');
                //         $('#email').attr('placeholder','Enter Email');
                        

                //     }
                // }
            
                // get_login_defaults();

                //  $('#login_type').on('change', function(){
                //     get_login_defaults();
                // });


                initScrollbar('.scrollable-page', {
                    setHeight: $(window).height() - 50
                });
            });
        </script>

        <?php echo view("includes/footer"); ?>
    </body>
</html>