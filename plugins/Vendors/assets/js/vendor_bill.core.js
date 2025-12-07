/* global $, window, document */
(function ($) {
  "use strict";

  $(function () {
    if (!$("#vendor-bill-form").length) return;

    // ---------- helpers ----------
    function optHtml(map, placeholder) {
      var html = '<option value="">' + (placeholder || "-") + "</option>";
      Object.entries(map || {}).forEach(function (kv) {
        html +=
          '<option value="' +
          String(kv[0]) +
          '">' +
          String(kv[1]) +
          "</option>";
      });
      return html;
    }
    function toNumber(v, fb) {
      var n = parseFloat(v);
      return Number.isFinite(n) ? n : fb || 0;
    }
    function formatNumber(num) {
      // Format number without unnecessary decimal places
      var n = parseFloat(num);
      if (!Number.isFinite(n)) return "0";
      // If it's a whole number, return without decimals
      if (n % 1 === 0) return String(Math.round(n));
      // Otherwise return with decimals but remove trailing zeros
      return String(n).replace(/\.?0+$/, "");
    }
    function $name($tr, n) {
      return $tr.find('[name="' + n + '"]');
    }
    function recalcTotals() {
      var sum = 0;
      $(".line-amount").each(function () {
        sum += toNumber($(this).text(), 0);
      });
      $("#subtotal_cell").text(formatNumber(sum));
      $("#total_cell").text(formatNumber(sum));
    }
    function recalcRow($tr) {
      var qty = toNumber($name($tr, "quantity").val(), 0);
      var price = toNumber($name($tr, "unit_price").val(), 0);
      $tr.find(".line-amount").text(formatNumber(qty * price));
      recalcTotals();
    }
    function clearInvalid($el) {
      var $g = $el.closest(".form-group");
      $g.removeClass("has-error")
        .find(".field-error, .invalid-feedback")
        .remove();

      // Handle Select2 elements
      if ($el.hasClass("select2") || $el.is("select.select2")) {
        var $select2Container = $el.next(".select2-container");
        if ($select2Container.length) {
          $select2Container
            .find(".select2-selection")
            .removeClass("is-invalid");
        }
        try {
          if ($el.data("select2")) {
            $el
              .data("select2")
              .$container.find(".select2-selection")
              .removeClass("is-invalid");
          }
        } catch (e) {}
      }

      $el.removeClass("is-invalid");

      // Remove error messages from table cells
      var $td = $el.closest("td");
      if ($td.length) {
        $td.find(".invalid-feedback, .field-error").remove();
      }
    }

    function markInvalid($el, msg) {
      clearInvalid($el);

      var $g = $el.closest(".form-group");
      var errorMsg =
        msg ||
        (window.VB && VB.i18n && VB.i18n.required) ||
        "This field is required";

      // Add error class and message
      $el.addClass("is-invalid");
      $g.addClass("has-error");

      // Add error message
      if (!$g.find(".field-error, .invalid-feedback").length) {
        $g.append(
          $('<div class="field-error invalid-feedback"/>').text(errorMsg)
        );
      } else {
        $g.find(".field-error, .invalid-feedback").text(errorMsg);
      }

      // Handle Select2 elements
      if ($el.hasClass("select2") || $el.is("select.select2")) {
        var $select2Container = $el.next(".select2-container");
        if ($select2Container.length) {
          $select2Container.find(".select2-selection").addClass("is-invalid");
        }
        try {
          if ($el.data("select2")) {
            $el
              .data("select2")
              .$container.find(".select2-selection")
              .addClass("is-invalid");
          }
        } catch (e) {}
      }

      // For table rows, add feedback in the cell
      var $td = $el.closest("td");
      if ($td.length && !$g.length) {
        if (!$td.find(".invalid-feedback").length) {
          $td.append($('<div class="invalid-feedback"/>').text(errorMsg));
        } else {
          $td.find(".invalid-feedback").text(errorMsg);
        }
      }
    }

    // ---------- Select2 base ----------
    // Initialize Select2 for all elements with .select2 class
    if (typeof $.fn.select2 !== "undefined") {
      $(".select2").each(function () {
        var $el = $(this);
        if ($el.length && !$el.data("select2")) {
          try {
            $el.select2({ dropdownParent: $("#page-content") });
          } catch (e) {
            console.warn("Select2 initialization failed for:", this, e);
          }
        }
      });
    }

    // ---------- phases (project-dependent) ----------
    // Projects and Phases removed - not needed in vendors plugin
    // function initPhaseSelect($input) { ... }
    // function setPhaseSelection($input, id, text) { ... }

    // ---------- rows ----------
    var rowIndex = 0;

    function handleItemChange($tr) {
      var id = $name($tr, "item_id").val();

      var token = Date.now() + ":" + Math.random().toString(36).slice(2);
      $tr.data("itemReqToken", token);

      if (!id) {
        // clear row
        $name($tr, "description").val("");
        $name($tr, "quantity").val(0);
        $name($tr, "unit_price").val("0");
        recalcRow($tr);
        return;
      }

      $.ajax({
        url: VB.urls.getItemInfo,
        type: "POST",
        data: { item_id: id },
        dataType: "json",
      }).done(function (res) {
        if ($tr.data("itemReqToken") !== token) return;
        if (!res || res.success === false) return;

        var d = res.data || {};
        $name($tr, "description").val(d.description || "");
        $name($tr, "unit_price").val(formatNumber(d.rate || 0));

        // keep quantity if already set
        var currentQty = toNumber($name($tr, "quantity").val(), 0);
        if (currentQty === 0) {
          $name($tr, "quantity").val(d.default_qty ? d.default_qty : 1);
        }

        recalcRow($tr);
      });
    }

    function attachRowListeners($tr) {
      // math (name-based)
      $tr.on("input", '[name="quantity"],[name="unit_price"]', function () {
        recalcRow($tr);
      });

      // item change — bind both native and Select2 events (name-based)
      $tr.on("change", '[name="item_id"]', function () {
        handleItemChange($tr);
      });
      $tr.on("select2:select", '[name="item_id"]', function () {
        handleItemChange($tr);
      });
      $tr.on("select2:clear", '[name="item_id"]', function () {
        handleItemChange($tr);
      });

      // delete
      $tr.on("click", ".del-line", function () {
        $tr.remove();
        var i = 1;
        $("#bill-lines tbody tr").each(function () {
          $(this).find("td:first").text(i++);
        });
        recalcTotals();
      });
    }

    function newRow(prefill) {
      rowIndex++;
      var $tr = $(
        [
          '<tr data-index="' + rowIndex + '">',
          '<td class="text-center">' + rowIndex + "</td>",
          '<td><select class="form-control select2" name="item_id">' +
            optHtml(window.VB_DATA.items, "- Item -") +
            "</select></td>",
          '<td><select class="form-control select2" name="account_id">' +
            optHtml(window.VB_DATA.accounts, "- Account -") +
            "</select></td>",
          '<td><input type="text" class="form-control" name="description" placeholder="Description"></td>',
          '<td><input type="number" step="0.001" min="0" class="form-control text-right" name="quantity" value="0"></td>',
          '<td><input type="number" step="0.01"  min="0" class="form-control text-right" name="unit_price" value="0"></td>',
          '<td class="text-right"><span class="line-amount">0</span></td>',
          // 👇 new hidden id so edits update instead of duplicating
          '<td class="text-center">',
          '  <input type="hidden" name="line_id" value="0">',
          '  <button type="button" class="btn btn-default btn-sm del-line" title="Delete"><i data-feather="trash" class="icon-16"></i></button>',
          "</td>",
          "</tr>",
        ].join("")
      );

      $("#bill-lines tbody").append($tr);

      // init selects
      if (typeof $.fn.select2 !== "undefined") {
        $tr.find('[name="item_id"],[name="account_id"]').each(function () {
          var $el = $(this);
          if (!$el.data("select2")) {
            try {
              $el.select2({ dropdownParent: $("#page-content") });
            } catch (e) {
              console.warn("Select2 init failed for new row:", e);
            }
          }
        });
      }
      if (window.feather) feather.replace();

      // prefill (order!)
      if (prefill && typeof prefill === "object") {
        // set line id first (so subsequent logic knows this is existing)
        if (prefill.id) {
          $name($tr, "line_id").val(String(prefill.id));
        }

        // Projects and Phases removed - not needed in vendors plugin
        // if (prefill.project_id) {
        //   $name($tr, "project_id")
        //     .val(String(prefill.project_id))
        //     .trigger("change");
        // }

        if (prefill.item_id) $name($tr, "item_id").val(String(prefill.item_id));
        if (prefill.account_id)
          $name($tr, "account_id").val(String(prefill.account_id));
        if (prefill.description)
          $name($tr, "description").val(prefill.description);
        if (typeof prefill.quantity !== "undefined")
          $name($tr, "quantity").val(prefill.quantity);
        if (typeof prefill.unit_price !== "undefined")
          $name($tr, "unit_price").val(formatNumber(prefill.unit_price));

        recalcRow($tr);
      }

      attachRowListeners($tr);
      enforceIntegerInputs($tr);
    }

    // ---------- Date pickers (if RISE helper present) ----------
    if (typeof setDatePicker === "function") {
      setDatePicker("#accounting_date");
      setDatePicker("#bill_date");
    }

    // ---------- Invoice total on change ----------
    $("#invoice_id").on("change", function () {
      var invoiceId = $(this).val();
      var $totalField = $("#invoice_total_display");

      if (!invoiceId) {
        $totalField.val("0.00");
        return;
      }

      // Fetch invoice total
      $.ajax({
        url: VB.urls.getInvoiceTotal,
        type: "POST",
        data: { invoice_id: invoiceId },
        dataType: "json",
        success: function (res) {
          if (res.success && res.data) {
            var total = parseFloat(res.data.invoice_total || 0);
            $totalField.val(formatNumber(total));
          } else {
            $totalField.val("0.00");
          }
        },
        error: function () {
          $totalField.val("0.00");
        },
      });
    });

    // ---------- Add row button ----------
    $("#add-line").on("click", function () {
      newRow();
    });

    // ---------- render existing or first empty ----------
    var existing =
      window.VB_DATA && Array.isArray(VB_DATA.lines) ? VB_DATA.lines : [];
    if (existing.length) {
      existing.forEach(function (ln) {
        newRow(ln);
      });
      recalcTotals();
    } else {
      newRow();
    }

    // ---------- validation ----------
    function validateHeader() {
      var ok = true;
      ["#vendor_id", "#accounting_date", "#bill_date"].forEach(function (sel) {
        clearInvalid($(sel));
      });
      if (!$("#vendor_id").val()) {
        markInvalid($("#vendor_id"), VB.i18n.required);
        ok = false;
      }
      if (!$("#accounting_date").val()) {
        markInvalid($("#accounting_date"), VB.i18n.required);
        ok = false;
      }
      if (!$("#bill_date").val()) {
        markInvalid($("#bill_date"), VB.i18n.required);
        ok = false;
      }
      return ok;
    }
    function validateLines() {
      var ok = true,
        firstBad = null;
      $("#bill-lines tbody tr").each(function () {
        var $tr = $(this),
          $item = $name($tr, "item_id"),
          $acc = $name($tr, "account_id"),
          $qty = $name($tr, "quantity"),
          $price = $name($tr, "unit_price");
        [$item, $acc, $qty, $price].forEach(clearInvalid);

        if (!$item.val()) {
          markInvalid($item, VB.i18n.required);
          firstBad = firstBad || $item;
          ok = false;
          return false;
        }
        if (!$acc.val()) {
          markInvalid($acc, VB.i18n.required);
          firstBad = firstBad || $acc;
          ok = false;
          return false;
        }
        var q = toNumber($qty.val(), 0),
          p = toNumber($price.val(), 0);
        if (q <= 0) {
          markInvalid($qty, VB.i18n.qty_gt_zero);
          firstBad = firstBad || $qty;
          ok = false;
          return false;
        }
        if (p < 0) {
          markInvalid($price, VB.i18n.price_negative);
          firstBad = firstBad || $price;
          ok = false;
          return false;
        }
      });
      if (!ok && firstBad) {
        try {
          firstBad.focus();
        } catch (e) {}
        var off = firstBad.offset();
        $("html,body").animate({ scrollTop: (off ? off.top : 0) - 120 }, 180);
      }
      return ok;
    }

    // --- Add after your existing helpers ---
    function enforceIntegerInputs($tr) {
      var sel = '[name="quantity"],[name="unit_price"]';
      $tr.find(sel).attr({ step: 1, inputmode: "numeric", pattern: "\\d*" });

      // strip non-digits as you type
      $tr.off("input.intOnly", sel).on("input.intOnly", sel, function () {
        var v = this.value.replace(/[^\d]/g, "");
        this.value = v;
      });

      // normalize on blur
      $tr.off("blur.intOnly", sel).on("blur.intOnly", sel, function () {
        var n = parseInt(this.value || "0", 10);
        this.value = isFinite(n) ? String(n) : "0";
        // refresh row totals
        var $row = $(this).closest("tr");
        if (typeof recalcRow === "function") recalcRow($row);
      });
    }

    // ---------- submit ----------
    $("#vendor-bill-form").appForm({
      isModal: false,
      onSubmit: function () {
        if (!validateHeader() || !validateLines()) return false;

        var rows = [];
        $("#bill-lines tbody tr").each(function () {
          var $tr = $(this);

          // include the hidden id so backend updates (not inserts)
          var lineId = parseInt($name($tr, "line_id").val() || "0", 10);

          rows.push({
            id: isFinite(lineId) ? lineId : 0,
            item_id: $name($tr, "item_id").val() || null,
            account_id: $name($tr, "account_id").val() || null,
            description: $name($tr, "description").val() || null,
            quantity: $name($tr, "quantity").val() || 0,
            unit_price: $name($tr, "unit_price").val() || 0,
          });
        });
        $("#items_json").val(JSON.stringify(rows));
        return true;
      },
      onSuccess: function (res) {
        appLoader.hide();
        if (res && res.success) {
          window.location = res.redirect_to || VB.urls.index || "/";
        } else {
          appAlert.error((res && res.message) || "Error");
        }
      },
      onError: function () {
        appLoader.hide();
      },
    });

    // dev helper
    window.VB_newRow = newRow;
  });
})(jQuery);
