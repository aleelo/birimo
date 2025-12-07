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
    function recalcTotals() {
      var sum = 0;
      $(".line-amount").each(function () {
        sum += toNumber($(this).text(), 0);
      });
      $("#subtotal_cell").text(sum.toFixed(2));
      $("#total_cell").text(sum.toFixed(2));
    }
    function recalcRow($tr) {
      var qty = toNumber($tr.find(".line-qty").val(), 0);
      var price = toNumber($tr.find(".line-price").val(), 0);
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

    // ---------- init select2 ----------
    $(".select2").select2({ dropdownParent: $("#page-content") });

    // ---------- phases (select2 ajax v3 shape) ----------
    function initPhaseSelectV3($input) {
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
            var pid = $tr.find('[name="project_id"]').val() || "";
            return { expense_project_id: pid, q: term || "" };
          },
          results: function (data) {
            return { results: Array.isArray(data) ? data : [] };
          },
        },
        initSelection: function (element, callback) {
          var id = $(element).val();
          if (!id) return callback(null);
          var preTxt = $(element).data("prefillText");
          if (preTxt) {
            callback({ id: id, text: preTxt });
            return;
          }
          var pid = $tr.find('[name="project_id"]').val() || "";
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

      // reset phase if project changes
      $tr.off("change.phase").on("change.phase", ".line-project", function () {
        $input.select2("val", "");
      });
    }

    // ---------- rows ----------
    var rowIndex = 0;

    function newRow(prefill) {
      rowIndex++;

      var idVal = prefill && prefill.id ? String(prefill.id) : "";
      var itemVal =
        prefill && prefill.item_id != null ? String(prefill.item_id) : "";
      var accountVal =
        prefill && prefill.account_id != null ? String(prefill.account_id) : "";
      var projVal =
        prefill && prefill.project_id != null ? String(prefill.project_id) : "";
      var phaseVal =
        prefill && prefill.phase_id != null ? String(prefill.phase_id) : "";
      var phaseTxt =
        prefill && prefill.phase_title ? String(prefill.phase_title) : "";
      var qtyVal =
        prefill && typeof prefill.quantity !== "undefined"
          ? prefill.quantity
          : 0;
      var priceVal =
        prefill && typeof prefill.unit_price !== "undefined"
          ? Number(prefill.unit_price).toFixed(2)
          : "0.00";
      var descVal = prefill && prefill.description ? prefill.description : "";

      var $tr = $(
        [
          '<tr data-index="' + rowIndex + '">',
          '  <td class="text-center">' + rowIndex + "</td>",
          '  <td><select class="form-control select2 line-item" name="item_id">' +
            optHtml(window.VB_DATA.items, "- Item -") +
            "</select></td>",
          '  <td><select class="form-control select2 line-account" name="account_id">' +
            optHtml(window.VB_DATA.accounts, "- Account -") +
            "</select></td>",
          '  <td><input type="text" class="form-control line-desc" name="description" placeholder="Description"></td>',
          '  <td><input type="number" step="0.001" min="0" class="form-control text-right line-qty" name="quantity" value="0"></td>',
          '  <td><input type="number" step="0.01"  min="0" class="form-control text-right line-price" name="unit_price" value="0.00"></td>',
          '  <td class="text-right"><span class="line-amount">0.00</span></td>',
          '  <td><select class="form-control select2 line-project" name="project_id">' +
            optHtml(window.VB_DATA.projects, "- Project -") +
            "</select></td>",
          '  <td><input type="hidden" class="form-control line-phase" name="phase_id" value=""></td>',
          '  <td class="text-center"><button type="button" class="btn btn-default btn-sm del-line" title="Delete"><i data-feather="trash" class="icon-16"></i></button></td>',
          // carry line id for updates
          '  <input type="hidden" class="line-id" name="line_id" value="' +
            idVal +
            '">',
          "</tr>",
        ].join("")
      );

      $("#bill-lines tbody").append($tr);

      // select2 binds
      $tr
        .find(".line-item, .line-account, .line-project")
        .select2({ dropdownParent: $("#page-content") });

      // set initial values AFTER select2
      if (itemVal) $tr.find('[name="item_id"]').val(itemVal).trigger("change");
      if (accountVal)
        $tr.find('[name="account_id"]').val(accountVal).trigger("change");
      if (projVal)
        $tr.find('[name="project_id"]').val(projVal).trigger("change");
      if (descVal) $tr.find(".line-desc").val(descVal);
      $tr.find(".line-qty").val(qtyVal);
      $tr.find(".line-price").val(priceVal);

      // phase select2 (ajax)
      initPhaseSelectV3($tr.find(".line-phase"));
      if (phaseVal) {
        $tr.find('[name="phase_id"]').val(phaseVal);
        if (phaseTxt) $tr.find(".line-phase").data("prefillText", phaseTxt);
      }

      if (window.feather) feather.replace();
      recalcRow($tr);

      // listeners
      $tr.on("input", ".line-qty,.line-price", function () {
        recalcRow($tr);
      });
      $tr.on("click", ".del-line", function () {
        $tr.remove();
        var i = 1;
        $("#bill-lines tbody tr").each(function () {
          $(this).find("td:first").text(i++);
        });
        recalcTotals();
      });
    }

    // ---------- item change autofill ----------
    $("#bill-lines").on("change", ".line-item", function () {
      var $tr = $(this).closest("tr");
      var id = $(this).val();

      if (!id) {
        // only clear if user cleared the item
        $tr.find(".line-desc").val("");
        $tr.find(".line-qty").val(0);
        $tr.find(".line-price").val("0.00");
        recalcRow($tr);
        return;
      }

      // on manual change, fetch defaults (won't run on initial prefill)
      $.post(VB.urls.getItemInfo, { item_id: id }, function (resp) {
        try {
          var res = typeof resp === "string" ? JSON.parse(resp) : resp;
          var d = res.data || {};
          // Only overwrite empty fields to avoid nuking edit values
          if (!$tr.find(".line-desc").val()) {
            $tr.find(".line-desc").val(d.description || "");
          }
          if (toNumber($tr.find(".line-price").val(), NaN) === 0) {
            $tr.find(".line-price").val(Number(d.rate || 0).toFixed(2));
          }
          if (toNumber($tr.find(".line-qty").val(), NaN) === 0) {
            $tr.find(".line-qty").val(d.default_qty ? d.default_qty : 1);
          }
          recalcRow($tr);
        } catch (e) {
          recalcRow($tr);
        }
      });
    });

    // ---------- add line ----------
    $("#add-line").on("click", function () {
      newRow();
    });

    // ---------- render existing lines or one empty ----------
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
          $item = $tr.find('[name="item_id"]'),
          $acc = $tr.find('[name="account_id"]'),
          $qty = $tr.find(".line-qty"),
          $price = $tr.find(".line-price");
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
            phaseId = $tr.find('[name="phase_id"]').val() || null,
            phaseText = "";
          try {
            var d = $tr.find(".line-phase").select2("data");
            if (d && typeof d === "object") phaseText = d.text || "";
          } catch (e) {}

          rows.push({
            id: $tr.find(".line-id").val() || null, // carry line id back
            item_id: $tr.find('[name="item_id"]').val() || null,
            account_id: $tr.find('[name="account_id"]').val() || null,
            description: $tr.find('[name="description"]').val() || null,
            quantity: $tr.find('[name="quantity"]').val() || 0,
            unit_price: $tr.find('[name="unit_price"]').val() || 0,
            project_id: $tr.find('[name="project_id"]').val() || null,
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
  });
})(jQuery);
