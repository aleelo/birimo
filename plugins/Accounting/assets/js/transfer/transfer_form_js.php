
 <script type="text/javascript">
    $(document).ready(function () 
    { 
        "use strict";
        $("#transfer-form").appForm({
            onSuccess: function (result) {
                if (typeof RELOAD_VIEW_AFTER_UPDATE !== "undefined" && RELOAD_VIEW_AFTER_UPDATE) {
                    location.reload();
                } else {
                    if ($("#transfer-table").length) {
                        //it's from invoice details view
                        $("#transfer-table").appTable({reload: true});
                    } else {
                        //it's from invoices list view
                        //update table data
                        $("#" + $(".dataTable:visible").attr("id")).appTable({reload: true});
                    }
                }
            }
        });
        
        $("button[type=submit]").click(function () {
            $(this).trigger("submit");
        });

        $("#transfer-form .select2").select2();
        setDatePicker("#date");
    });
</script>