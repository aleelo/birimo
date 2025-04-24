<?php echo form_open(get_uri("companyy/save"), array("id" => "company-form", "class" => "general-form", "role" => "form", "onsubmit" => "return false;")); ?>

<?php
// Get the logged-in user's department_id
$db = \Config\Database::connect();
$user_id = session()->get('user_id'); // Assuming the user ID is stored in the session
$user_query = $db->query("SELECT department_id FROM rise_users WHERE id = ?", [$user_id]);
$user_department_ids = $user_query->getRow()->department_id;

// Convert department_id to an array if it contains multiple IDs (e.g., "2,3")
$department_ids_array = explode(',', $user_department_ids);

// Prepare placeholders for the query
$placeholders = implode(',', array_fill(0, count($department_ids_array), '?'));

// Fetch companies that match the user's department_id(s)
$query = $db->query("SELECT id, name FROM rise_company WHERE id IN ($placeholders)", $department_ids_array);
$companies = $query->getResultArray();

// Prepare the dropdown options
$company_options = array("" => "Choose the company", "0" => "all");
foreach ($companies as $company) {
    $company_options[$company['id']] = $company['name'];
}
?>

<div class="form-group">
    <label for="department"></label>
    <?php
    echo form_dropdown(
        "department",
        $company_options, // Use the dynamically populated options
        "",
        'class="form-control select2" id="department"'
    );
    ?>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
$(document).ready(function () {
    // Restore last selected value from localStorage
    let savedDepartment = localStorage.getItem("selected_department");
    if (savedDepartment !== null) {
        $("#department").val(savedDepartment).trigger("change");
    }

    // Initialize Select2
    $("#department").select2();

    // Handle change event
    $("#department").on("change", function () {
        let department = $(this).val();

        // Save selected department to localStorage
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