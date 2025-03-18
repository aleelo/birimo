<?php echo form_open(get_uri("companyy/save"), array("id" => "company-form", "class" => "general-form", "role" => "form", "onsubmit" => "return false;")); ?>

    <div class="form-group">
        <label for="department"><?php ?></label>
        <?php
        echo form_dropdown("department", 
            array(
                "" => "Choose the company",
                "1" => "aleelo pixel",
                "2" => "aleelo solution",
                "0" =>"all"
            ), 
            "",  
            'class="form-control select2" id="department"'
        );
        ?>
    </div>

</form>
<script type="text/javascript">
 $(document).ready(function() {
    $("#company-form .select2").select2();

    $("#department").on("change", function() {
        let department = $(this).val();
        
        if (department === "0") {
            department = ""; 
        }

        $.ajax({
            url: "<?php echo get_uri('companyy/save'); ?>",
            type: "POST",
            data: { department: department },
            dataType: "json",
            success: function(result) {
                location.reload();
            },
            error: function(xhr, status, error) {
                console.log("Error:", error);
            }
        });
    });
});



</script>
