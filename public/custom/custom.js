$(document).ready(function(){

   

    $('#select-all').on('click', function(){
        this.checked ? table.rows().select() : table.rows().deselect();
    });

    // Add options menu to header
    $(".dataTables_filter").prepend("<select class='select' id='table-action'>" +
            "<option value='0'>Select Action</option>" +
            "<option value='1' disabled>Active</option>" +
            "<option value='2' disabled>Deactive</option>" +
            "<option value='3' disabled>Delete</option>"+
            "</select>"
    );

    setSelect2ForAction();
    function setSelect2ForAction() {
        $(".dataTables_filter select").select2({
            minimumResultsForSearch: Infinity,
            closeOnSelect: true,
            width: 'auto'
        });
    };

    $('#table-action').change(function() {
        this.value > 0 ? confirmAction(table, this.value) : 0;
    })

    // Check if no records in datatables, then disabled some features
    table.on('xhr', function() {
        var json = table.ajax.json();
        if (json.data.length == 0) {
            $('#table-action').prop('disabled', true);
            $(".dataTables_length select").prop('disabled', true);
        } else {
            $('#table-action').prop('disabled', false);
            $(".dataTables_length select").prop('disabled', false);
        }
    })

    // On selected row event, activate or deactivate Action selection
    table.on('select', function() {
        checkMoreAction();
    })

    // On deselected row event, activate or deactivate Action selection
    table.on('deselect', function() {
        checkMoreAction();
    })

    function checkMoreAction() {
        if (table.rows({selected: true}).indexes().length > 0) {
            $(".dataTables_filter select#table-action").find("option[value='1']").prop('disabled', false);
            $(".dataTables_filter select#table-action").find("option[value='2']").prop('disabled', false);
            $(".dataTables_filter select#table-action").find("option[value='3']").prop('disabled', false);
        } else {
            $(".dataTables_filter select#table-action").find("option[value='1']").prop('disabled', true);
            $(".dataTables_filter select#table-action").find("option[value='2']").prop('disabled', true);
            $(".dataTables_filter select#table-action").find("option[value='3']").prop('disabled', true);
        }
        setSelect2ForAction();
    }

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    // Styling select all checkbox
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });

    function confirmAction(table, option, url) {
        var ids = $.map(table.rows({ selected: true }).data(), function (item) {
            return item.id
        });

        if (option == 1) {
            url = $('#data-table').data('url') + '/restore/' + ids;
            confirm(table, 1, url, 'POST')
        } else if (option == 2) {
            url = $('#data-table').data('url') + '/' + ids;
            confirm(table, 2, url, 'DELETE')
        } else if (option == 3) {
            url = $('#data-table').data('url') + '/remove/' + ids;
            confirm(table, 3, url, 'DELETE')
        }

        $('#table-action').val(0).change();
    }

    function confirm(table, option, url, type) {
        var title = option == 2 ? 'Are you sure you want to deactivate selected item(s) ?' : 'Are you sure you want to activate selected item(s) ?';
        var text = option == 2 ? 'You will be able to undo your action later!' : 'You will be able to undo your action later!';
        var conf = option == 2 ? 'Yes, deactive!' : 'Yes, activate!';
        var canc = option == 2 ? 'No, cancel deactive please!' : 'No, cancel activating please!';

        if (option == 3) {
            title = 'Are you sure you want to permanently delete selected item(s) ?';
            text = 'You cannot undo your action!';
            conf = 'Yes, delete permanently!';
            canc = 'No, cancel this action please!';
        }

        swal.fire({
            title: 'Are you sure?',
            // text: text,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: conf,
            cancelButtonText: canc,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: type,
                    success: function(response) {
                        if (option == 3) {
                            notification('Success',response.message, 'success','bg-success border-success');
                        } else if (option == 2) {
                            notification('Success',response.message, 'success','bg-success border-success');
                        } else {
                            notification('Success',response.message, 'success','bg-success border-success');
                        }
                        table.ajax.reload();
                        // location.reload();
                    },
                    error: function(response) {
                        if (option == 3) {
                            notification('Error',response.responseJSON.message, 'error','bg-danger border-danger');
                        } else if (option == 2) {
                            notification('Error',response.responseJSON.message, 'error','bg-danger border-danger');
                        } else {
                            notification('Error',response.responseJSON.message, 'error','bg-danger border-danger');
                        }

                    }
                })
            } else {
                table.rows().deselect();
            }
        })        
    }   
});