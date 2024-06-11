/**
 * Theme: Ubold Dashboard
 * Author: SilkTech SA
 * Foo table for orders
 */

const confirmMessageOnDeleteButtonClick = () => {
  document.addEventListener("click", (e) => {
    if (typeof e.target.dataset.delete_button == "undefined") {
      return;
    }
    e.preventDefault();
    const type = e.target.dataset.type;
    swal({
      title: `Είσαι σίγουρος ότι θες να διαγράψεις αυτό το ${type}?`,
      text: "Τα δεδομένα θα χαθούν για πάντα!",
      showCancelButton: true,
      confirmButtonClass: "btn-warning",
      confirmButtonText: "Ναι",
      cancelButtonText: "Όχι",
      allowOutsideClick: false,
    })
      .then(
        (confirm) => {
          window.location = e.target.href;
        },
        (dismiss) => {
          return;
        }
      )
      .catch(swal.noop);
    return;
  });
  return;
};

var CiDatatablesFunctions = function () {
  var exports = {};
  var base_url = config.base_url;
  var assets_url = config.assets_url;

  function setAjaxTableFunctions() {
    $("form#ci_datatable_form input#current_page").val(
      $("#ci_datatable_container input#refreshed_page").val()
    );

    $('#ci_datatable_container [data-toggle="tooltip"]').tooltip();

    $("table.ci_datatable .ajax-form").submit(function (e) {
      e.preventDefault();
      var this_ = $(this);
      $(this).find('button[type="submit"]').hide();
      this_.find('.ci_save_loader').show();
      $.post(
        $(this).attr('action'),
        $(this).serializeArray(),
        function (data) {
          this_.find('button[type="submit"]').show();
          this_.find('.ci_save_loader').hide();
        },
        "json"
      );
      return false;
    });

    $("table.ci_datatable .btn-ajax-confirm-action").click(function () {
      var confirmation_text = $(this).attr("data-confirmation");
      var url = $(this).attr("href");
      swal({
        title: "Are you sure?",
        text: confirmation_text,
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        allowOutsideClick: false,
      }).then(
        function (text) {
          $.post(
            url,
            [],
            function (data) {
              if (data.resp) {
                swal({
                  title: "Εντάξει",
                  text: data.msg,
                  icon: "success",
                  timer: 3000,
                });
              } else {
                swal({
                  title: "Πρόβλημα",
                  text: data.msg,
                  icon: "warning",
                  timer: 3000,
                });
              }
              $("form#ci_datatable_form").submit();
            },
            "json"
          );
        },
        function (dismiss) {}
      );
      return false;
    });

    
    
    $("table.ci_datatable .btn-update-publishdate").click(function () {
      $("#schedule_modal form#schedule_publish_form").attr(
        "data-ajax-url",
        $(this).attr("href")
      );
      $("#schedule_modal h5.modal-title").html("Ενημέρωση ημ/νίας δημοσίευσης");
      $('#schedule_modal input[type="submit"]').val("Ενημέρωση");
      $('#schedule_modal .submit_loader').hide();
      $('#schedule_modal input[type="submit"]').removeAttr("disabled","disabled");
      $("#schedule_modal").modal("show");
      return false;
    });

    $("table.ci_datatable .btn-schedule-action").click(function () {
      $("#schedule_modal form#schedule_publish_form").attr(
        "data-ajax-url",
        $(this).attr("href")
      );
      $("#schedule_modal h5.modal-title").html("Προγραμματισμός δημοσίευσης");
      $('#schedule_modal input[type="submit"]').val("Προγραμματισμός");
      $('#schedule_modal .submit_loader').hide();
      $('#schedule_modal input[type="submit"]').removeAttr("disabled","disabled");
      $("#schedule_modal").modal("show");
      return false;
    });

    $("table.ci_datatable .btn-ajax-save").click(function () {
      var tr = $(this).parents("tr.tr-row");
      if (!tr.find('input[name="title"]').val()) {
        swal({
          title: "Πρόβλημα",
          text: "Ο τίτλος είναι υποχρεωτικός!",
          icon: "warning",
          timer: 3000,
        });
      }
      $.post(
        $(this).attr("href"),
        {
          title: tr.find('input[name="title"]').val(),
          seo_alt: tr.find('input[name="seo_alt"]').val(),
          seo_description: tr.find('textarea[name="seo_description"]').val(),
        },
        function (data) {
          if (data.resp) {
            swal({
              title: "Εντάξει",
              text: data.msg,
              icon: "success",
              timer: 3000,
            });
          } else {
            swal({
              title: "Πρόβλημα",
              text: data.msg,
              icon: "warning",
              timer: 3000,
            });
          }
        },
        "json"
      );
      return false;
    });

    $("table.ci_datatable .btn-confirm-action").click(function () {
      var confirmation_text = $(this).attr("data-confirmation");
      var url = $(this).attr("href");
      swal({
        title: "Are you sure?",
        text: confirmation_text,
        showCancelButton: true,
        confirmButtonClass: "btn-warning",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        allowOutsideClick: false,
      }).then(
        function (text) {
          window.location.href = url;
        },
        function (dismiss) {}
      );
      return false;
    });

    $("table.ci_datatable th.do-sorting").click(function () {
      $('form#ci_datatable_form input[name="sortingColumn"]').val(
        $(this).attr("data-sorting-col")
      );
      $('form#ci_datatable_form input[name="sortingType"]').val(
        $(this).attr("data-sorting-type")
      );
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
    });

    $("a.pagination_link").click(function () {
      if ($(this).hasClass("disabled")) return false;
      $("form#ci_datatable_form input#current_page").val(
        $(this).attr("data-page-number")
      );
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
      return false;
    });

    $("table.ci_datatable .update-flag-input").on(
      "change.bootstrapSwitch",
      function (e) {
        var url = $(this).parents(".update-flag").attr("data-ajax-url");
        var lo = $(this).parents("td").find(".my-loader");
        var v = e.target.checked;
        lo.show();
        $.post(
          url + (e.target.checked ? "1" : "0"),
          [],
          function (data) {
            if (data.resp == false) {
              swal({
                title: "Error",
                text: data.msg,
                type: "warning",
                timer: 3000,
              });
            }
            lo.hide();
          },
          "json"
        );
      }
    );
  }
  exports.setAjaxTableFunctions = setAjaxTableFunctions;

  function init() {
    confirmMessageOnDeleteButtonClick();
    $("#ci_datatable_form :input:not(textarea)").keydown(function (e) {
      if (e.keyCode == 13) {
        //ENTER
        e.preventDefault();
        $("form#ci_datatable_form input#current_page").val(1);
        $("form#ci_datatable_form input#form_data_changed").val(1);
        $("form#ci_datatable_form").submit();
      }
    });

    if ($("form#ci_datatable_form #daterange_picker").length) {
      
       $('form#ci_datatable_form  input#daterange_picker').daterangepicker({
          locale: {
            format: "YYYY-MM-DD",
            firstDay: 1,
            cancelLabel: "Clear",
          }
       });

       if($('form#ci_datatable_form input#daterange_picker').hasClass('no_input')){
          $('form#ci_datatable_form input#daterange_picker').val('');
       }

       $('form#ci_datatable_form input#daterange_picker').on('apply.daterangepicker', function(ev, picker) {
          $("form#ci_datatable_form input#current_page").val(1);
          $("form#ci_datatable_form input#form_data_changed").val(1);
          $("form#ci_datatable_form").submit();
      });
      
      $('form#ci_datatable_form input#daterange_picker').on('cancel.daterangepicker', function(ev, picker) {
        $('form#ci_datatable_form input#daterange_picker').val('');
        $("form#ci_datatable_form input#current_page").val(1);
        $("form#ci_datatable_form input#form_data_changed").val(1);
        $("form#ci_datatable_form").submit();
     });
    }

    if ($("form#ci_datatable_form #datatable-datepicker").length) {
      $("form#ci_datatable_form #datatable-datepicker")
        .datepicker({
          format: "dd/mm/yyyy",
          autoclose: true,
          todayHighlight: true,
        })
        .on("changeDate", function (ev) {
          if ($(this).val()) {
            $("form#ci_datatable_form input#current_page").val(1);
            $("form#ci_datatable_form input#form_data_changed").val(1);
            $("form#ci_datatable_form").submit();
          }
        });
    }

    $("form#ci_datatable_form .btngroup-submit-on-change button").click(
      function () {
        var p = $(this).parents(".btngroup-submit-on-change");
        p.find("button.active").removeClass("active");
        $(this).addClass("active");
        $(
          'form#ci_datatable_form input[name="' +
            p.attr("data-target-input") +
            '"]'
        ).val($(this).attr("data-val"));
        $("form#ci_datatable_form").submit();
      }
    );

    if ($("form#ci_datatable_form #datatable_date_selector").length) {
      $("form#ci_datatable_form #datatable_date_selector").pickadate({
        format: "dd/mm/yyyy",
        formatSubmit: "dd/mm/yyyy",
        hiddenName: true,
        hiddenSuffix: "",
        onSet: function (context) {
          if (context.select) $("form#ci_datatable_form").submit();
        },
      });
    }

    $("form#ci_datatable_form .submit-on-change").change(function () {
      if ($(this).hasClass("filter-select")) {
        var st = $(this).attr("data-target");
        if ($(this).attr("data-select-restore")) {
          var restore = $(this).attr("data-select-restore").split(",");
          $.each(restore, function (i, v) {
            var sv = $('form#ci_datatable_form select[name="' + v + '"]');
            sv.val("");
            sv.html(
              '<option value="" selected="selected">' +
                sv.attr("data-start-text") +
                "</option>"
            );
          });
        }
        $('form#ci_datatable_form select[name="' + st + '"]').val("");
        $.post(
          $(this).attr("data-ajax-url") + $(this).val(),
          [],
          function (data) {
            $('form#ci_datatable_form select[name="' + st + '"]').html(data);
          },
          "html"
        );
      }
      $("form#ci_datatable_form input#current_page").val(1);
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
    });

    $("form#ci_datatable_form #clear_term").click(function () {
      var input_field = $(this)
        .parents(".form-group")
        .find('input[type="text"]');
      //if($('form#ci_datatable_form input[name="term"]').val() == ''){
      if (input_field.val() == "") {
        return false;
      }
      $('form#ci_datatable_form input[name="term"]').val("");
      $('form#ci_datatable_form input[name="full_term"]').val("");
      $("form#ci_datatable_form input#current_page").val(1);
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
      return false;
    });

    $("form#ci_datatable_form #search_term").click(function () {
      var input_field = $(this)
        .parents(".input-group")
        .find('input[type="text"]');
      //if(!$('form#ci_datatable_form input[name="term"]').val()){
      if (!input_field.val()) {
        if (
          $('form#ci_datatable_form input[name="full_term"]').length &&
          $('form#ci_datatable_form input[name="full_term"]').val()
        ) {
        } else {
          swal("Attention", "Please fill the search term", "error");
          return false;
        }
      }
      $("form#ci_datatable_form input#current_page").val(1);
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
      return false;
    });

    $("form#ci_datatable_form #clear_matching_term").click(function () {
      var input_field = $(this)
        .parents(".form-group")
        .find('input[type="text"]');

      if (input_field.val() == "") {
        return false;
      }
      $('form#ci_datatable_form input[name="matching_term"]').val("");
      $('form#ci_datatable_form input[name="client_matching_term"]').val("");
      $("form#ci_datatable_form input#current_page").val(1);
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
      return false;
    });

    $("form#ci_datatable_form #search_matching_term").click(function () {
      var input_field = $(this)
        .parents(".input-group")
        .find('input[type="text"]');

      if (!input_field.val()) {
        swal("Attention", "Please fill the search term", "error");
        return false;
      }
      $("form#ci_datatable_form input#current_page").val(1);
      $("form#ci_datatable_form input#form_data_changed").val(1);
      $("form#ci_datatable_form").submit();
      return false;
    });

    if ($("#schedule_modal .pickadate-format").length) {
      $("#schedule_modal .pickadate-format").pickadate({
        format: "yyyy-mm-dd",
        formatSubmit: "yyyy-mm-dd",
        hiddenPrefix: "schedule_date",
        //hiddenName: true,
        hiddenSuffix: "",
      });

      $("#schedule_modal #mypickatime").AnyTime_picker({
        input: $("#schedule_modal .pickatime-format"),
        format: "%H:%i",
      });
    }

    $("#schedule_modal form#schedule_publish_form").submit(function (e) {
      e.preventDefault();
      var error = false;
      $("#schedule_modal form#schedule_publish_form input.required").each(
        function () {
          $(this).parents(".form-group").removeClass("has-error");
          if (!$(this).val()) {
            error = true;
            $(this).parents(".form-group").addClass("has-error");
          }
        }
      );
      if (error) {
        swal({
          title: "Προσοχή!",
          text: "Συμπληρώστε όλα τα υποχρεωτικά πεδία.",
          icon: "warning",
          timer: 3000,
        });
        return false;
      }
      $('#schedule_modal input[type="submit"]').attr("disabled","disabled");
      $('#schedule_modal .submit_loader').show();
      $.post(
        $(this).attr("data-ajax-url"),
        $(this).serializeArray(),
        function (data) {
          if (data.resp) {
            $('#schedule_modal .submit_loader').hide();
            $('#schedule_modal input[type="submit"]').removeAttr("disabled","disabled");
            $("#schedule_modal").modal("hide");
            swal({
              title: "Εντάξει",
              text: data.msg,
              icon: "success",
              timer: 3000,
            });
            $("form#ci_datatable_form").submit();
          } else {
            $('#schedule_modal .submit_loader').hide();
            $('#schedule_modal input[type="submit"]').removeAttr("disabled","disabled");
            swal({
              title: "Πρόβλημα",
              text: data.msg,
              icon: "warning",
              timer: 3000,
            });
          }
        },
        "json"
      );
    });

    $("form#ci_datatable_form input.input-tokeninput").each(function () {
      var prepopulated = [];
      if (
        $(this).attr("data-init-token-id") &&
        $(this).attr("data-init-token-name")
      ) {
        var prepopulated = [
          {
            id: $(this).attr("data-init-token-id"),
            name: $(this).attr("data-init-token-name"),
          },
        ];
      }
      var update_input = $(this).attr("data-hidden-input");
      var filter_select = $(this).hasClass("filter-tokeninput");
      if (filter_select) {
        var st = $(this).attr("data-target");
        var st_restore = $(this).attr("data-select-restore");
        var filter_url = $(this).attr("data-ajax-url");
      }
      $(this).tokenInput($(this).attr("data-ajax-function"), {
        prePopulate: prepopulated,
        tokenLimit: 1,
        onAdd: function (item) {
          if (
            update_input &&
            $('form#ci_datatable_form input[name="' + update_input + '"]')
              .length
          ) {
            $('form#ci_datatable_form input[name="' + update_input + '"]').val(
              item.name
            );
          }
          if (filter_select) {
            if (st_restore) {
              var restore = st_restore.split(",");
              $.each(restore, function (i, v) {
                var sv = $('form#ci_datatable_form select[name="' + v + '"]');
                sv.val("");
                sv.html(
                  '<option value="" selected="selected">' +
                    sv.attr("data-start-text") +
                    "</option>"
                );
              });
            }
            $('form#ci_datatable_form select[name="' + st + '"]').val("");
            $.post(
              filter_url + item.id,
              [],
              function (data) {
                $('form#ci_datatable_form select[name="' + st + '"]').html(
                  data
                );
              },
              "html"
            );
          }
          $("form#ci_datatable_form input#current_page").val(1);
          $("form#ci_datatable_form input#form_data_changed").val(1);
          $("form#ci_datatable_form").submit();
        },
        onDelete: function (item) {
          if (
            update_input &&
            $('form#ci_datatable_form input[name="' + update_input + '"]')
              .length
          ) {
            $('form#ci_datatable_form input[name="' + update_input + '"]').val(
              ""
            );
          }

          if (filter_select) {
            if (st_restore) {
              var restore = st_restore.split(",");
              $.each(restore, function (i, v) {
                var sv = $('form#ci_datatable_form select[name="' + v + '"]');
                sv.val("");
                sv.html(
                  '<option value="" selected="selected">' +
                    sv.attr("data-start-text") +
                    "</option>"
                );
              });
            }
            $('form#ci_datatable_form select[name="' + st + '"]').val("");
            $.post(
              filter_url,
              [],
              function (data) {
                $('form#ci_datatable_form select[name="' + st + '"]').html(
                  data
                );
              },
              "html"
            );
          }
          $("form#ci_datatable_form input#current_page").val(1);
          $("form#ci_datatable_form input#form_data_changed").val(1);
          $("form#ci_datatable_form").submit();
        },
      });
    });

    $("form#ci_datatable_form").submit(function (e) {
      e.preventDefault();
      $("#ci_datatable_loader").show();
      var url = $(this).attr("data-ajax-url");

      $.post(
        url,
        $(this).serializeArray(),
        function (data) {
          if (data.resp == false && data.redirect_to) {
            window.location.href = data.redirect_to;
          } else {
            $("form#ci_datatable_form input#form_data_changed").val(0);
            if (data.update_data) {
              $("#ci_datatable_container").html(data.table_data);
              setAjaxTableFunctions();
            }
            $("#ci_datatable_loader").hide();
          }
        },
        "json"
      );
      return false;
    });
    setAjaxTableFunctions();
  }
  exports.init = init;

  return exports;
};
var ciDatatablesFunctions = new CiDatatablesFunctions();

$(document).ready(function () {
  if ($("#ci_datatable_container").length) {
    ciDatatablesFunctions.init();
  }
});
