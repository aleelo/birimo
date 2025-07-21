<?php echo form_open(get_uri("companyy/save"), array("id" => "company-form", "class" => "general-form", "role" => "form", "onsubmit" => "return false;")); ?>

<?php
$db = \Config\Database::connect();
$user_id = session()->get('user_id'); 

$user_query = $db->query("SELECT company_access, is_admin,department FROM rise_users WHERE id = ?", [$user_id]);
$user_data = $user_query->getRow();

$company_access = $user_data->company_access;
$is_admin = $user_data->is_admin;
$department=$user_data->department;

$company_options = [];

if ($company_access === "all" ) {
    $query = $db->query("SELECT id, name FROM rise_company");
    $companies = $query->getResultArray();
    $company_options["0"] = "all";
} else {
    $company_ids_array = explode(',', $company_access);
    $placeholders = implode(',', array_fill(0, count($company_ids_array), '?'));

    $query = $db->query("SELECT id, name FROM rise_company WHERE id IN ($placeholders)", $company_ids_array);
    $companies = $query->getResultArray();
}

foreach ($companies as $company) {
    $company_options[$company['id']] = $company['name'];
}
?>

<div class="form-group">
    <label for="department"></label>
    <?php
    echo form_dropdown(
        "department",
        $company_options, 
        "",
        'class="form-control select2" id="department"'
    );
    ?>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function () {
    let savedDepartment = localStorage.getItem("selected_department");
    if (savedDepartment !== null) {
        $("#department").val(savedDepartment).trigger("change");
    }

    $("#department").on("change", function () {
        let department = $(this).val();

        localStorage.setItem("selected_department", department);

        if (department === "0") {
            department = "";
        }

        $.ajax({
            url: "<?php echo get_uri('companyy/save'); ?>",
            type: "POST",
            data: { department: department },
            dataType: "json",
            success: function (result) {
                location.reload();
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
            }
        });
    });
});
</script>
