<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jqGrid Example</title>

    <!-- jQuery and jqGrid CSS/JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.5/css/ui.jqgrid.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/grid.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/free-jqgrid/4.15.5/jquery.jqgrid.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>

<body>
<style>
    /* CSS for the details row */
.details-row {
    background-color: #f0f8ff; /* Light grey background */
    border-top: 1px solid #ddd; /* Light grey border at the top */
}

.details-cell {
    padding: 10px; /* Add padding inside the cell */
}

.details-content {
    background-color: #f0f8ff; /* White background for content */
    border: 1px solid #ddd; /* Light grey border around the content */
    padding: 15px; /* Add padding inside the content */
    font-family: 'Arial', sans-serif; /* Change font style */
    font-size: 14px; /* Adjust font size */
    color: #333; /* Dark grey text color */
    border-radius: 5px; /* Rounded corners for the content box */
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle box shadow */
}

/* Style for the text labels */
.details-content strong {
    font-weight: bold;
    color: #444; /* Slightly darker color for labels */
}

        .ui-search-clear {
            display: none;
        }
        .expanded-column {
    background-color: #f0f8ff; /* Light blue or any other distinguishable color */
}

        .caption-button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        #add-new-row-btn {
            margin-left: auto;
        }

    </style>
<div class="grid-container">
    <table id="grid"></table>
    <div id="pager">
    <!-- <button id="add-new-row-btn" style="position:relative;">Add Row</button>      -->
</div>
<button id="add-new-row-btn" style="position:relative;bottom:308px;left:1100px">Add Row</button>
       
    <!-- <button id="add-new-row-btn" style="position:absolute; top: 10px;right: 182px">Add Row</button> Add Button at the Bottom -->

<div id="dialog-form" title="Fill Details" style="display:none;">
    <form>
        <fieldset>
            <label for="user_id">User ID</label><br>
            <input type="text" name="user_id" id="user_id" class="text ui-widget-content ui-corner-all"><br>
            <label for="title">Title</label><br>
            <input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all"><br>
            <label for="response_link">Response Link</label><br>
            <input type="text" name="response_link" id="response_link" class="text ui-widget-content ui-corner-all"><br>
            <label for="is_responsive">Is Responsive</label><br>
            <input type="text" name="is_responsive" id="is_responsive" class="text ui-widget-content ui-corner-all"><br>

            <!-- Allow form submission with keyboard without duplicating the dialog button -->
            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
        </fieldset>
    </form>
</div>


    <script>
        //For adding a new row
        $(document).ready(function () {
    var base_url = "<?php echo base_url(); ?>";

    // Initialize the dialog form
    var dialog, form;

    function addNewRow() {
        var newRowData = {
            user_id: $("#user_id").val(),
            title: $("#title").val(),
            description: $("#description").val(),
            is_published: $("#is_published").val(),
            response_link: $("#response_link").val(),
            is_responsive: $("#is_responsive").val()
            // actions: '' // Leave actions empty, it will be filled after saving
        };
        // AJAX call to save the new row data to the database
        $.ajax({
            url: base_url + 'JqgridController/addData',
            type: 'POST',
            data: newRowData,
            success: function (response) {
            
                // console.log();
                let res = JSON.parse(response);
                if (res?.status === "success") {
                    // $("#grid").jqGrid('addRowData', new Date().getTime(), newRowData, 'first');
                    Swal.fire('Saved!', 'The new row has been added.', 'success');
                    $("#grid").trigger("reloadGrid");
                }else {
                    Swal.fire('Error!', 'There was an issue adding the new row.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error!', 'There was an issue processing your request.', 'error');
            }
        });
        dialog.dialog("close");
    }

    dialog = $("#dialog-form").dialog({
        autoOpen: false,
        height: 400,
        width: 350,
        modal: true,
        buttons: {
            "Save": addNewRow,
            "Cancel": function () {
                dialog.dialog("close");
            }
        },
        close: function () {
            form[0].reset();
        }
    });

    form = dialog.find("form").on("submit", function (event) {
        event.preventDefault();
        addNewRow();
    });

    $('#add-new-row-btn').on('click', function () {
        dialog.dialog("open");
    });
});

        $(document).ready(function () {
            var base_url = "<?php echo base_url(); ?>";
            var profileColumnsAdded = false; // Flag to track if profile columns are added
            var columnsExpanded = false; // Flag to track if columns are expanded
            $(document).ready(function () {
    var columnsExpanded = false;

    $("#grid").jqGrid({
        url: base_url + 'JqgridController/getData',
        datatype: 'json',
        mtype: 'GET',
        colNames: [
            'Serial_ID', 'ID', 'Title', 'Response Link', 'Created At',
            'Is Responsive', 'User ID', 'Profile Name', 'Mail', 'Gender', 'Actions'
        ],
        colModel: [
            { name: 'Serial_ID', index: 'Serial_ID', width: 55, align: 'center', key: true },
            { name: 'id', index: 'id', width: 55, align: 'center', formatter: idFormatter },
            { name: 'title', index: 'title', width: 150, align: 'center', editable: true },
            { name: 'response_link', index: 'response_link', width: 150, editable: true },
            { name: 'created_at', index: 'created_at', width: 120, align: 'center', formatter: 'date', formatoptions: { srcformat: 'Y-m-d H:i:s', newformat: 'M/d/Y H:i:s' } },
            { name: 'is_responsive', index: 'is_responsive', width: 90, align: 'center', editable: true },
            { name: 'user_id', index: 'user_id', width: 120, align: 'center' },
            { name: 'profile_name', index: 'profile_name', width: 150, hidden: true, align: 'center', classes: 'expanded-column' },
            { name: 'mail', index: 'mail', width: 200, hidden: true, align: 'center', classes: 'expanded-column' },
            { name: 'gender', index: 'gender', width: 100, hidden: true, align: 'center', classes: 'expanded-column' },
            { name: 'actions', index: 'actions', width: 100, align: 'center', sortable: false, formatter: actionFormatter, search: false }
        ],
        pager: '#pager',
        rowNum: 20,
        rowList: [5, 10, 15, 20],
        sortname: 'id',
        sortorder: 'asc',
        viewrecords: true,
        caption: 'Forms Data',
        height: 'auto',
        autowidth: true,
        toolbar: [true, "top"],
        gridComplete: function () {
            // Add the plus button to the User ID column header
            if (!columnsExpanded) {
                $("#grid_user_id").html('User ID <button class="expand-header-btn">+</button>');
            }
        }
    });

    // Formatter function for ID column
    function idFormatter(cellValue, options, rowObject) {
        return cellValue + ' <button class="toggle-details-btn" data-id="' + options.rowId + '">+</button>';
    }

    // Handle the expanding and collapsing of columns
    $(document).on('click', '.expand-header-btn', function () {
        if (!columnsExpanded) {
            // Fetch profile data
            $.ajax({
                url: base_url + 'JqgridController/getAllProfilesData',
                method: 'GET',
                success: function (response) {
                    var profilesData = JSON.parse(response);
                    // Show the hidden profile columns
                    $("#grid").jqGrid('showCol', ['profile_name', 'mail', 'gender']);

                    // Update the rows with the profile information
                    var gridData = $("#grid").jqGrid('getRowData');
                    gridData.forEach(function (row) {
                        var userId = row.user_id;
                        var profile = profilesData.find(function (p) {
                            return p.profile_id === userId;
                        });
                        if (profile) {
                            // Update the row data with the corresponding profile information
                            $('#grid').jqGrid('setRowData', row.Serial_ID, {
                                profile_name: profile.profile_name,
                                mail: profile.mail,
                                gender: profile.gender
                            });
                        }
                    });
                    $('.expand-header-btn').text('-');
                    columnsExpanded = true;
                }
            });
        } else {
            // Collapse and hide the profile columns
            $("#grid").jqGrid('hideCol', ['profile_name', 'mail', 'gender']);
            // Change button back to plus
            $('.expand-header-btn').text('+');
            columnsExpanded = false;
        }
    });

    // Handle row-specific detail toggle
    $(document).on('click', '.toggle-details-btn', function () {
        var id = $(this).data('id');
        var $btn = $(this);

        if ($btn.text() === '+') {
            // Expand to show details
            $.ajax({
                url: base_url + 'JqgridController/getFormDetails/' + id,
                method: 'GET',
                success: function (response) {
                    var formData = JSON.parse(response);
                    var newRowData = '<tr id="details-row-' + id + '" class="details-row">';
                    newRowData += '<td colspan="11" class="details-cell">';
                    newRowData += '<div class="details-content">';
                    newRowData += '<strong>Description:</strong> ' + formData.description + '<br>';
                    newRowData += '<strong>Is Published:</strong> ' + formData.is_published + '</div></td></tr>';
                    $('#grid').find('tr[id="' + id + '"]').after(newRowData);
                    $btn.text('-');
                }
            });
        } else {
            // Collapse to hide details
            $('#details-row-' + id).remove();
            $btn.text('+');
        }
    });

});



            $("#grid").jqGrid('filterToolbar', {
                stringResult: true, // Filter results as a string
                searchOnEnter: false, // Search on key press, not just on Enter
                defaultSearch: "cn" // Default search operator 'contains'
            });
            $('#grid').find('.ui-search-clear').css('display', 'none');
            // Formatter function for action buttons (Edit/Delete)
            function actionFormatter(cellvalue, options, rowObject) {
                var editBtn = "<button class='edit-btn' data-id='" + options.rowId + "'>Edit</button>";
                var deleteBtn = "<button class='delete-btn' data-id='" + options.rowId + "'>Delete</button>";
                return editBtn + " " + deleteBtn;
            }

            // Formatter for Save/Cancel buttons in a new row
            function saveCancelFormatter(rowId) {
                var saveBtn = "<button class='save-new-btn' data-id='" + rowId + "'>Save</button>";
                var cancelBtn = "<button class='cancel-new-btn' data-id='" + rowId + "'>Cancel</button>";
                return saveBtn + " " + cancelBtn;
            }

            // Cancel button click event for new rows
            $(document).on('click', '.cancel-new-btn', function () {
                var id = $(this).data('id');
                $("#grid").jqGrid('delRowData', id); // Remove the row
            });

            // Edit button click event for existing rows
            $(document).on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $("#grid").jqGrid('editRow', id, { keys: true, focusField: 4 });
                $(this).replaceWith("<button class='save-btn' data-id='" + id + "'>Save</button>");
                $(this).next('.delete-btn').replaceWith("<button class='cancel-btn' data-id='" + id + "'>Cancel</button>");
            });

            // Save button click event for existing rows
            $(document).on('click', '.save-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#grid").jqGrid('saveRow', id, {
                            url: base_url + 'JqgridController/updateData',
                            aftersavefunc: function () {
                                Swal.fire('Saved!', 'The record has been updated.', 'success');
                                $("#grid").trigger("reloadGrid");
                            },
                            errorfunc: function () {
                                Swal.fire('Error!', 'There was an issue updating the record.', 'error');
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        $("#grid").jqGrid('restoreRow', id);
                        revertButtons(id);
                    }
                });
            });

            function revertButtons(id) {
                $(".save-btn[data-id='" + id + "']").replaceWith("<button class='edit-btn' data-id='" + id + "'>Edit</button>");
                $(".cancel-btn[data-id='" + id + "']").replaceWith("<button class='delete-btn' data-id='" + id + "'>Delete</button>");
            }

            // Cancel button click event for existing rows
            $(document).on('click', '.cancel-btn', function () {
                var id = $(this).data('id');
                $("#grid").jqGrid('restoreRow', id);
                revertButtons(id);
            });

            // Delete button click event
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: base_url + 'JqgridController/deleteData/' + id,
                            // console.log("id")
                            type: 'POST',
                            success: function () {
                                Swal.fire('Deleted!', 'The record has been deleted.', 'success');
                                $("#grid").trigger("reloadGrid");
                            },
                            error: function () {
                                Swal.fire('Error!', 'There was an issue deleting the record.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>
