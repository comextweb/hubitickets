"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    cache: false,
    complete: function () {
        // LetterAvatar.transform();
        $('[data-toggle="tooltip"]').tooltip();
        function select2() {
            if ($(".select2").length > 0) {
                $($(".select2")).each(function (index, element) {
                    var id = $(element).attr('id');
                    var multipleCancelButton = new Choices(
                        '#' + id, {
                        removeItemButton: true,
                    }
                    );
                });
            }
        }
    },
});


// function show_toastr(title, message, type) {
//     var o, i;
//     var icon = '';
//     var cls = '';

//     if (type == 'success') {
//         icon = 'fas fa-check-circle';
//         cls = 'success';
//     } else {
//         icon = 'fas fa-times-circle';
//         cls = 'danger';
//     }

//     $.notify({ icon: icon, title: title, message: message, url: "" }, {
//         element: "body",
//         type: cls,
//         allow_dismiss: !0,
//         placement: { from: 'top', align: 'right' },
//         offset: { x: 15, y: 15 },
//         spacing: 10,
//         z_index: 9999,
//         delay: 2500,
//         timer: 2000,
//         url_target: "_blank",
//         mouse_over: !1,
//         animate: { enter: o, exit: i },
//         template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
//     });
// }

function summernote() {
    $(".summernote-simple").each(function () {
        if (!$(this).hasClass('summernote-initialized')) {
            $(this).addClass('summernote-initialized');
            $(this).summernote({
                dialogsInBody: true,
                minHeight: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                    ['list', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'unlink']],
                ],
                height: 250,
                callbacks: {
                    onKeyup: function () {
                        setTimeout(() => validateSummernote(null, null, this), 0);
                    }
                }
            });
        }
    });
}

function show_toastr(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';
    if (type == 'success') {
        icon = 'fas fa-check-circle';
        // cls = 'success';
        cls = 'primary';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }


    $.notify({
        icon: icon,
        title: " " + title,
        message: message,
        url: ""
    }, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: {
            from: 'top',
            align: 'right'
        },
        offset: {
            x: 15,
            y: 15
        },
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: {
            enter: o,
            exit: i
        },
        // danger
        template: '<div class="toast text-white bg-' + cls +
            ' fade show" role="alert" aria-live="assertive" aria-atomic="true">' +
            '<div class="d-flex">' +
            '<div class="toast-body"> ' + message + ' </div>' +
            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
            '</div>'
        // template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
    });
}





function validation() {
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.forEach.call(forms, function (form) {

        form.addEventListener('submit', function (event) {
            var submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

            if (submitButton) {
                submitButton.disabled = true;
            }
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                //for debug

                // var invalidInputs = Array.from(form.elements).filter(function (input) {
                //     return input.validity.valid === false && input.type !== "submit"; // Exclude submit buttons
                // });

                // invalidInputs.forEach(function (input) {
                //     console.log('Invalid input:', input.name || input.id || input.placeholder);
                // });

                if (submitButton) {
                    submitButton.disabled = false;
                }
            }

            // Custom validation for file input
            let fileInput = form.querySelector('input[type="file"][required]');

            if (fileInput && fileInput.files.length === 0) {
                event.preventDefault();
                event.stopPropagation();
                $(fileInput).closest('.choose-file').find('.multiple_file_selection').text('Please select a file.').addClass('text-danger');
            } else {
                $(fileInput).closest('.choose-file').find('.multiple_file_selection').text('').removeClass('text-danger');
            }

            $(form).find('.summernote-simple').each(function () {
                validateSummernote(event, submitButton, this);
            });
            form.classList.add('was-validated');
        }, false);
    });
}

$(document).ready(function () {
    if ($(".needs-validation").length > 0) {
        validation();
    }
    if ($(".pc-dt-simple").length > 0) {
        $($(".pc-dt-simple")).each(function (index, element) {
            var id = $(element).attr('id');
            const dataTable = new simpleDatatables.DataTable("#" + id);
        });
    }
    // common_bind();
    summernote();


    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var fileclass = $(this).attr('data-filename');
        var finalname = $(this).val().split('\\').pop();
        $('.' + fileclass).html(finalname);
    });
});







function validateSummernote(event = null, submitButton = null, element = null) {
    var $summernote = $(element);
    var isRequired = $summernote.attr('required') !== undefined;
    var service = $('<div>').html($summernote.summernote('code')).text().trim();
    var $errorElement = $summernote.closest('.form-group').find('.summernote_text');

    if (isRequired && service == '') {
        $errorElement.text('This field is required');
        if (event) {
            event.preventDefault();
        }
        if (submitButton) {
            submitButton.disabled = false;
        }
    } else {
        $errorElement.text('');
    }
}


$(document).ready(function () {
    $(window).resize();

    // loadConfirm();

    if ($("#selection-datatable").length) {
        $("#selection-datatable").DataTable({
            order: [],
            select: { style: "multi" },
            "language": dataTableLang,
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
            }
        });
    }

    // LetterAvatar.transform();
    $('[data-toggle="tooltip"]').tooltip();

    $('#commonModal-right').on('shown.bs.modal', function () {
        $(document).off('focusin.modal');
    });

    if ($(".select2").length) {
        $('.select2').select2({
            "language": {
                "noResults": function () {
                    return "No result found";
                }
            },
        });
    }

    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var names = '';

        var fileclass = $(this).attr('data-filename');
        var attr = $(this).attr('multiple');

        if (typeof attr !== typeof undefined && attr !== false) {
            var files = $(this)[0].files;
            for (var i = 0; i < files.length; i++) {
                names += files[i].name + '<br>';
            }
        } else {
            names = $(this).val().split('\\').pop();
        }

        $('.' + fileclass).html(names);
    }
    );
});

// Common Modal
$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"]', function (e) {
    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $('#commonModal .modal-body ').html(data);
            $("#commonModal").modal('show');
            commonLoader();
            validation();
            summernote();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
    e.stopImmediatePropagation();
    return false;
});

// Common Modal from right side
$(document).on('click', 'a[data-ajax-popup-right="true"], button[data-ajax-popup-right="true"], div[data-ajax-popup-right="true"], span[data-ajax-popup-right="true"]', function (e) {
    var url = $(this).data('url');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $('#commonModal-right').html(data);
            $("#commonModal-right").modal('show');
            commonLoader();
            validation();
            summernote();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
});


$(document).on('click', 'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]', function () {

    var validate = $(this).attr('data-validate');
    var id = '';
    if (validate) {
        id = $(validate).val();
    }

    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModalOver .modal-title").html(title);
    $("#commonModalOver .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url + '?id=' + id,
        success: function (data) {
            $('#commonModalOver .modal-body').html(data);
            $("#commonModalOver").modal('show');
            // taskCheckbox();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

});


function commonLoader() {

    // LetterAvatar.transform();


    $('[data-toggle="tooltip"]').tooltip();

    if ($(".select2").length) {
        $('.select2').select2({
            "language": {
                "noResults": function () {
                    return "No result found";
                }
            },
        });
    }



    if ($(".datepicker").length) {
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            format: 'yyyy-mm-dd',
            locale: date_picker_locale,
        });
    }

}

function loadConfirm() {
    $('[data-confirm]').each(function () {
        var me = $(this),
            me_data = me.data('confirm');

        me_data = me_data.split("|");
        me.fireModal({
            title: me_data[0],
            body: me_data[1],
            buttons: [
                {
                    text: me.data('confirm-text-yes') || 'Yes',
                    class: 'btn btn-sm btn-danger rounded-pill',
                    handler: function () {
                        eval(me.data('confirm-yes'));
                    }
                },
                {
                    text: me.data('confirm-text-cancel') || 'Cancel',
                    class: 'btn btn-sm btn-secondary rounded-pill',
                    handler: function (modal) {
                        $.destroyModal(modal);
                        eval(me.data('confirm-no'));
                    }
                }
            ]
        })
    });
}


if ($(".multi-select").length > 0) {
    $($(".multi-select")).each(function (index, element) {
        var id = $(element).attr('id');
        var multipleCancelButton = new Choices(
            '#' + id, {
            removeItemButton: true,
        }
        );
    });

}

$(document).on('click', '.show_confirm', function () {
    var form = $(this).closest("form");
    var title = $(this).attr("data-confirm");
    var text = $(this).attr("data-text");
    if (title == '' || title == undefined) {
        title = "Are you sure?";

    }
    if (text == '' || text == undefined) {
        text = "This action can not be undone. Do you want to continue?";

    }

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })


    swalWithBootstrapButtons.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
});

// $(document).ready(function () {

//     $(".chat-header .setting-icon").click(function(){
//         $(".msg-card-wrp").addClass("active")   
//         $(this).hide()
//         $(".chat-header .close-icon").show()
//     });
//     $(".chat-header .close-icon").click(function(){
//         $(".msg-card-wrp").removeClass("active")
//         $(this).hide()
//         $(".chat-header .setting-icon").show()
//     });

//     // alert close js
//     $(".alert .close-alert").click(function(){
//         $(".alert").hide()
//     })
// });

$(".chat-header .info-icon").on('click', function () {
    $(".msg-card-wrp").addClass("active")
    $(".chat-main-wrapper .chat-wrapper-right").addClass('info-active');
    $(this).hide()
    $(".msg-card-wrp .close-icon").show();
});

$(document).on('click', '.msg-card-wrp .close-icon', function () {
    $(".msg-card-wrp").removeClass("active")
    $(".chat-main-wrapper .chat-wrapper-right").removeClass('info-active');
    $(this).hide()
    $(".chat-header .info-icon").show();
});

