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
    function $name($tr, n) {
      return $tr.find('[name="' + n + '"]');
    }
    function recalcTotals() {
      var sum = 0;
      $(".line-amount").each(function () {
        sum += toNumber($(this).text(), 0);
      });
      $("#subtotal_cell").text(sum.toFixed(2));
      $("#total_cell").text(sum.toFixed(2));
    }
    function recalcRow($tr) {
      var qty = toNumber($name($tr, "quantity").val(), 0);
      var price = toNumber($name($tr, "unit_price").val(), 0);
      $tr.find(".line-amount").text((qty * price).toFixed(2));
      recalcTotals();
    }
    function clearInvalid($el) {
      var $g = $el.closest(".form-group");
      $g.removeClass("has-error").find(".field-error").remove();
      if ($el.hasClass("select2")) {
        if ($el.select2 && $el.select2("container"))
          $el.select2("container").removeClass("is-invalid"); // v3
        $el
          .next(".select2")
          .find(".select2-selection")
          .removeClass("is-invalid"); // v4
      } else {
        $el.removeClass("is-invalid");
      }
    }
    function markInvalid($el, msg) {
      clearInvalid($el);
      var $g = $el.closest(".form-group");
      $g.addClass("has-error").append(
        $('<div class="field-error"/>').text(
          msg ||
            (window.VB && VB.i18n && VB.i18n.required) ||
            "This field is required"
        )
      );
      if ($el.hasClass("select2")) {
        if ($el.select2 && $el.select2("container"))
          $el.select2("container").addClass("is-invalid"); // v3
        $el.next(".select2").find(".select2-selection").addClass("is-invalid"); // v4
      } else {
        $el.addClass("is-invalid");
      }
    }

    // ---------- Select2 base ----------
    $(".select2").select2({ dropdownParent: $("#page-content") });

    // ---------- phases (project-dependent) ----------
    function initPhaseSelect($input) {
      var $tr = $input.closest("tr");
      if ($input.data("select2")) $input.select2("destroy");

      $input.select2({
        dropdownParent: $("#page-content"),
        placeholder: (window.VB && VB.i18n && VB.i18n.phase_ph) || "- Phase -",
        allowClear: true,
        minimumInputLength: 0,
        ajax: {
          url: VB.urls.getPhases,
          type: "POST",
          dataType: "json",
          quietMillis: 200,
          data: function (term) {
            var pid = $name($tr, "project_id").val() || "";
            return { expense_project_id: pid, q: term || "" };
          },
          results: function (data) {
            return { results: Array.isArray(data) ? data : [] }; // Select2 v3 format
          },
        },
        initSelection: function (element, callback) {
          var id = $(element).val();
          if (!id) return callback(null);

          var preTxt = $(element).data("prefillText");
          if (preTxt) return callback({ id: id, text: preTxt });

          var pid = $name($tr, "project_id").val() || "";
          $.post(
            VB.urls.getPhases,
            { expense_project_id: pid, q: "" },
            function (resp) {
              try {
                var arr = typeof resp === "string" ? JSON.parse(resp) : resp;
                var f = (arr || []).find(function (o) {
                  return String(o.id) === String(id);
                });
                callback(f || { id: id, text: "#" + id });
              } catch (e) {
                callback({ id: id, text: "#" + id });
              }
            }
          );
        },
      });

      // When project changes, clear phase
      $tr
        .off("change.phase")
        .on("change.phase", '[name="project_id"]', function () {
          if ($input.data("select2")) {
            try {
              $input.select2("val", "");
            } catch (e) {}
          }
          $input.val("");
        });
    }

    // Robust setter for phase (works for Select2 v3; degrades for v4)
    function setPhaseSelection($input, id, text) {
      if (!id) {
        if ($input.data("select2")) {
          try {
            $input.select2("val", "");
          } catch (e) {}
        }
        $input.val("");
        return;
      }
      $input.val(String(id));
      if ($input.select2 && typeof $input.select2 === "function") {
        try {
          $input.select2("data", { id: String(id), text: text || "#" + id });
          return;
        } catch (e) {}
      }
      $input.trigger("change");
    }

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
        $name($tr, "unit_price").val("0.00");
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
        $name($tr, "unit_price").val(Number(d.rate || 0).toFixed(2));

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
          '<td><input type="number" step="0.01"  min="0" class="form-control text-right" name="unit_price" value="0.00"></td>',
          '<td class="text-right"><span class="line-amount">0.00</span></td>',
          '<td><select class="form-control select2" name="project_id">' +
            optHtml(window.VB_DATA.projects, "- Project -") +
            "</select></td>",
          '<td><input type="hidden" class="form-control" name="phase_id" value=""></td>',
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
      $tr
        .find('[name="item_id"],[name="account_id"],[name="project_id"]')
        .select2({ dropdownParent: $("#page-content") });
      if (window.feather) feather.replace();

      // prefill (order!)
      if (prefill && typeof prefill === "object") {
        // set line id first (so subsequent logic knows this is existing)
        if (prefill.id) {
          $name($tr, "line_id").val(String(prefill.id));
        }

        if (prefill.project_id) {
          $name($tr, "project_id")
            .val(String(prefill.project_id))
            .trigger("change");
        }

        var $phase = $name($tr, "phase_id");
        if (prefill.phase_title)
          $phase.data("prefillText", prefill.phase_title);
        initPhaseSelect($phase);

        if (prefill.phase_id) {
          setPhaseSelection(
            $phase,
            prefill.phase_id,
            prefill.phase_title || null
          );
        }

        if (prefill.item_id)
          $name($tr, "item_id").val(String(prefill.item_id)).trigger("change");
        if (prefill.account_id)
          $name($tr, "account_id")
            .val(String(prefill.account_id))
            .trigger("change");
        if (prefill.description)
          $name($tr, "description").val(prefill.description);
        if (typeof prefill.quantity !== "undefined")
          $name($tr, "quantity").val(prefill.quantity);
        if (typeof prefill.unit_price !== "undefined")
          $name($tr, "unit_price").val(Number(prefill.unit_price).toFixed(2));

        recalcRow($tr);
      } else {
        initPhaseSelect($name($tr, "phase_id"));
      }

      attachRowListeners($tr);
    }

    // ---------- Date pickers (if RISE helper present) ----------
    if (typeof setDatePicker === "function") {
      setDatePicker("#accounting_date");
      setDatePicker("#bill_date");
    }

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

    // ---------- submit ----------
    $("#vendor-bill-form").appForm({
      isModal: false,
      onSubmit: function () {
        if (!validateHeader() || !validateLines()) return false;

        var rows = [];
        $("#bill-lines tbody tr").each(function () {
          var $tr = $(this),
            phaseId = $name($tr, "phase_id").val() || null,
            phaseText = "";
          try {
            var d = $name($tr, "phase_id").select2("data");
            if (d && typeof d === "object") phaseText = d.text || "";
          } catch (e) {}

          // include the hidden id so backend updates (not inserts)
          var lineId = parseInt($name($tr, "line_id").val() || "0", 10);

          rows.push({
            id: isFinite(lineId) ? lineId : 0,
            item_id: $name($tr, "item_id").val() || null,
            account_id: $name($tr, "account_id").val() || null,
            description: $name($tr, "description").val() || null,
            quantity: $name($tr, "quantity").val() || 0,
            unit_price: $name($tr, "unit_price").val() || 0,
            project_id: $name($tr, "project_id").val() || null,
            phase_id: phaseId,
            phase_title: phaseText,
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
