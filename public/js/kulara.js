// datatable settings
$.extend(true, $.fn.dataTable.defaults, {
    autoWidth: false,
    language: {search: '', searchPlaceholder: 'Search', lengthMenu: '_MENU_ per page'},
    lengthMenu: [5, 10, 25, 50, 100, 250, 500],
    pageLength: 25,
    responsive: true,
    stateDuration: 0,
    stateSave: true,
    stateLoadCallback: function (settings, callback) {
        return JSON.parse(localStorage.getItem($(this).attr('id')));
    },
    stateSaveCallback: function (settings, data) {
        localStorage.setItem($(this).attr('id'), JSON.stringify(data));
    },
    drawCallback: function (settings) {
        let api = this.api();

        // fix pagination if saved page is empty
        if (api.page() > 0 && api.rows({page: 'current'}).count() === 0) {
            api.page('previous').state.save();
            location.reload();
        }
    },
    initComplete: function (settings, json) {
        let api = this.api();

        // fix search input to use buttons
        let search_input = $('<input type="search" class="form-control form-control-sm" placeholder="Search">').val(api.search());
        let search_button = $('<button type="button" class="btn btn-sm btn-link text-secondary p-1" title="Search"><i class="far fa-fw fa-search"></i></button>')
            .click(function () {
                api.search(search_input.val()).draw();

                if (search_input.val().length) {
                    search_button.addClass('d-none');
                    clear_button.removeClass('d-none');
                }
                else {
                    search_button.removeClass('d-none');
                    clear_button.addClass('d-none');
                }
            });
        let clear_button = $('<button type="button" class="btn btn-sm btn-link text-secondary p-1" title="Clear"><i class="far fa-fw fa-times-circle"></i></button>')
            .click(function () {
                search_input.val('');
                search_button.click();
            });

        if (api.search().length) {
            search_button.addClass('d-none');
        }
        else {
            clear_button.addClass('d-none');
        }

        $('#' + settings.nTable.id + '_filter input').unbind();
        $('#' + settings.nTable.id + '_filter').html($('<div class="table-search"></div>').append(search_input, search_button, clear_button));

        $(document).keypress(function (event) {
            if (event.which === 13) {
                search_button.click();
            }
        });
    }
});
$(document).ready(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // flash alert if present on body
    let body = $('body');

    if (body.attr('data-flash-class')) {
        flash(body.attr('data-flash-class'), body.attr('data-flash-message'));
        body.removeAttr('data-flash-class').removeAttr('data-flash-message');
    }

    // toggle sidebar
    $(document).on('click', '.sidebar-toggle', function (event) {
        event.preventDefault();
        $('.wrapper').toggleClass('wrapper-toggled');
    });

    // submit logout form when link clicked
    $(document).on('click', '#logout_link', function (event) {
        event.preventDefault();
        $(this).closest('form').submit();
    });

    // ajax form processing
    $(document).on('click', '[data-ajax-form] [type="submit"]', function () {
        $(this).closest('[data-ajax-form]').find('[data-button-clicked]').removeAttr('data-button-clicked');
        $(this).attr('data-button-clicked', true);
    });

    $(document).on('submit', '[data-ajax-form]', function (event) {
        event.preventDefault();
        let form = $(this);
        let form_data = new FormData(form[0]);
        let button_clicked = form.find('[data-button-clicked]');
        let _method = form.find('input[name=_method]').val();
        if (_method === 'DELETE') {
            Swal.fire({
                title: "Are you sure?",
                text: "This is a 1 way ticket action.",
                type: "warning",
                focusConfirm: false,
                showConfirmButton: true,
                showCancelButton: true
            }).then((result) => {
                if (result.value) {
                    post_form(form, form_data, button_clicked);
                }
            });
        } else {
            let confirm_msg = button_clicked.data('confirm_msg');
            let confirmwithinput_msg = button_clicked.data('confirmwithinput_msg');
            let confirmwithinput_html = button_clicked.data('confirmwithinput_html');
            let confirm_title = button_clicked.data('confirm_title');
            if (confirm_title == undefined) {
                confirm_title = 'Are you sure?';
            }
            if (confirm_msg != undefined) {
                Swal.fire({
                    title: confirm_title,
                    text: confirm_msg,
                    type: "question",
                    focusConfirm: false,
                    showConfirmButton: true,
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        post_form(form, form_data, button_clicked);
                    }
                });
            } else if (confirmwithinput_msg != undefined) {
                let confirm_input_type = button_clicked.data('confirm_input_type');
                if (confirm_input_type == undefined) {
                    confirm_input_type = 'password';
                }
                Swal.fire({
                    title: confirm_title,
                    input: confirm_input_type,
                    text: confirmwithinput_msg,
                    type: "warning",
                    focusConfirm: false,
                    showConfirmButton: true,
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please fill up the value.'
                        }
                    }
                }).then((result) => {
                    if (result.value) {
                        form_data.append('swal_input',result.value);
                        post_form(form, form_data, button_clicked);
                    }
                });
            } else if (confirmwithinput_html != undefined) {
                confirmwithinput_html = '<div id="swalFormWrapper">'+confirmwithinput_html+'</div>';
                Swal.fire({
                    title: confirm_title,
                    html: confirmwithinput_html,
                    type: "warning",
                    focusConfirm: false,
                    showConfirmButton: true,
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        let ok = false;
                        $.each($('#swalFormWrapper').find('input,textarea,select').serializeArray(),function(index, val) {
                            if (val.value != '') {
                                form_data.append(val.name,val.value);
                                ok = true;
                            } else {
                                ok = false;
                                return ;
                            }
                        });
                        if (ok) {
                            post_form(form, form_data, button_clicked);
                        } else {
                            Swal.fire({
                                type: "error",
                                title: "whoops! Mistake found!",
                                text: "Please fill up the field."
                            }).then((result) => {
                                form.trigger('submit');
                            });
                        }
                    }
                });
            } else {
                post_form(form, form_data, button_clicked);
            }
        }
    });

    // re-enable form submit when ajax complete
    $(document).ajaxComplete(function () {
        $('[data-ajax-form="submitted"]').attr('data-ajax-form', '');
    });

    // remove invalid style on input entry
    $(document).on('keyup change', '.is-invalid', function () {
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group, [class^="col"]').find('.invalid-feedback').remove();
    });

    // confirm action
    // $(document).on('click', '[data-confirm]', function (event) {
    //     if (!confirm($(this).data('confirm').length ? $(this).data('confirm') : 'Are you sure?')) {
    //         event.preventDefault();
    //     }
    // });

    // hide/show target based on changed value
    $(document).on('change', '[data-show-hide]', function () {
        let element = $(this);
        let target = $(element.data('show-hide'));

        target.addClass('d-none');
        target.each(function () {
            if (element.find(':checked, :selected').data('show') === $(this).data('show')) {
                $(this).removeClass('d-none');
            }
        });
    });

    // show file names in label when selected
    $(document).on('change', '.custom-file-input', function() {
        let files = [];
        let input = $(this)[0];
        let placeToInsertImagePreview = $(this).closest('.custom-file').next('.custom-file-gallery');
        // for (let i = 0; i < input.files.length; i++) {
        //     files.push(input.files[i].name);
        // }
        // Multiple images preview in browser
        if (input.files) {
            let filesAmount = input.files.length;
            placeToInsertImagePreview.html('');
            for (i = 0; i < filesAmount; i++) {
                let reader = new FileReader();
                let name = input.files[i].name;
                reader.onload = function(event) {
                    $($.parseHTML('<div class="col-2"><img class="img-fluid img-thumb" title="'+name+'" src="'+event.target.result+'"></div>')).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }

        // $(this).next('.custom-file-label').html(files.join(', '));
    });

    // set user timezone on login
    let auth_user_timezone = $('#auth_user_timezone');

    if (auth_user_timezone.length) {
        auth_user_timezone.val(Intl.DateTimeFormat().resolvedOptions().timeZone);
    }

    // convert textarea to markdown
    let form_control_markdown = $('.form-control-markdown');

    if (form_control_markdown.length) {
        form_control_markdown.each(function () {
            let textarea = this;
            let easymde = new EasyMDE({
                autoDownloadFontAwesome: false,
                element: textarea,
                showIcons: ['code', 'table'],
                spellChecker: false,
                status: false
            });
            easymde.codemirror.on('change', function () {
                $(textarea).trigger('keyup');
            });
        });
    }

    $('.datepicker').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('.datepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
    $('.datepicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('.datetimepicker').daterangepicker({
        autoUpdateInput: false,
        singleDatePicker: true,
        timePicker: true,
        autoApply: true,
        locale: {
            format: 'YYYY-MM-DD hh:mm A'
        }
    });
    $('.datetimepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD hh:mm A'));
    });
    $('.datetimepicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('.rangedatepicker').daterangepicker({
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            '1 Week': [moment(), moment().add(7, 'days')]
        },
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('.rangedatepicker').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    $('.rangedatepicker').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('.date_filter_range').daterangepicker({
        autoUpdateInput: false,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('.date_filter_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
    $('.date_filter_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $summernote = $('.summernote').summernote({
        height: 300,
        minHeight: null,
        maxHeight: null,
        callbacks: {
            onImageUpload: function(files) {
                for(let i=0; i < files.length; i++) {
                    let file = files[i];
                    let out = new FormData();
                    out.append('file', file, file.name);
                    $.ajax({
                        method: 'POST',
                        url: route('admin.summernote.imageUpload'),
                        contentType: false,
                        cache: false,
                        processData: false,
                        data: out,
                        success: function (url) {
                            $summernote.summernote("insertImage", url, function ($image) {
                                $image.css('width', '100%');
                            });
                        }
                    });
                }
            }
        }
    });
});
$(document).on({
    ajaxStart: function() { $("body").addClass("loading"); },
    ajaxStop: function() { $("body").removeClass("loading"); }
});
function post_form(form, form_data, button_clicked) {
    if (form.attr('data-ajax-form') !== 'submitted') {
        // stop additional form submits
        form.attr('data-ajax-form', 'submitted');

        // append value of submit button clicked
        if (button_clicked.attr('name')) {
            form_data.append(button_clicked.attr('name'), button_clicked.val());
        }

        // remove existing alert & invalid field styles
        $('.alert-flash').remove();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            contentType: false,
            processData: false,
            data: form_data,
            success: function (response) {
                // redirect to page
                if (response.hasOwnProperty('redirect')) {
                    $(location).attr('href', response.redirect);
                }
                // reload current page
                if (response.hasOwnProperty('reload_page')) {
                    location.reload();
                }
                // reload datatables on page
                if (response.hasOwnProperty('reload_datatables')) {
                    $($.fn.dataTable.tables()).DataTable().ajax.reload(null, false);
                }
                // flash message using class
                if (response.hasOwnProperty('flash')) {
                    flash(response.flash[0], response.flash[1]);
                }
            },
            beforeSend: function() {
                $("body").addClass("loading");
            },
            complete: function() {
                $("body").removeClass("loading");
            },
            error: function (response) {
                // flash error message
                flash('error', response.responseJSON.message);

                // show error for each input
                if (response.responseJSON.hasOwnProperty('errors')) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        let input = $('#' + $.escapeSelector(key));
                        let container = input.closest('.form-group, [class^="col"]');

                        input.addClass('is-invalid');
                        container.append('<div class="invalid-feedback d-block">' + value[0] + '</div>');
                    });
                }
            }
        });
    }
}
function flash(alert_class, alert_message) {
    if (alert_class == 'danger') { alert_class = 'error'; }
    Swal.fire({
        title: alert_class.toUpperCase() + '!',
        text: alert_message,
        type: alert_class
    });
}
