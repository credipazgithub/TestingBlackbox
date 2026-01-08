var _TOOLS = {
    UUID: function () {
        var s = [];
        var hexDigits = "0123456789abcdef";
        for (var i = 0; i < 36; i++) { s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1); }
        s[14] = "4";
        s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
        s[8] = s[13] = s[18] = s[23] = "-";
        var uuid = s.join("");
        return uuid;
    },
    validate: function (_selector) {
        var _ret = true;
        $(_selector).each(function () { _ret = _TOOLS.formatValidation($(this)) && _ret; });
        return _ret;
    },
    formatValidation: function (_obj) {
        var _ret = true;
        var _color = "transparent";
        var _color_alert = "rgba(251, 4, 11,0.25)";
        switch (_obj.prop("tagName")) {
            case "TEXTAREA":
            case "INPUT":
                switch (_obj.attr("type")) {
                    case "email":
                        if (!_TOOLS.isValidEmail(_obj.val())) {
                            _color = _color_alert;
                            _ret = false;
                        }
                        break;
                    case "radio":
                        _ret = ($("input[name='" + property + "']:checked").val() != undefined);
                        if (!_ret) {
                            _color = _color_alert;
                            _obj.parent().css({ "background-color": _color, "border": "solid 1px silver", "border-radius": "0.25rem" });
                        } else {
                            _obj.parent().css({ "background-color": "transparent", "border": "solid 1px transparent" });
                        }
                        break;
                    case "checkbox":
                        var _checked = _obj.is(":checked");
                        if (!_checked) {
                            _color = _color_alert;
                            _ret = false;
                            _obj.parent().css("color", _color);
                        } else {
                            _obj.parent().css("color", "transparent");
                        }
                        break;
                    default:
                        if (_obj.hasClass("data-list")) {
                            if (_obj.attr("data-selected-id") == "" || _obj.attr("data-selected-id") == undefined) { _color = _color_alert; _ret = false; }
                        } else {
                            if (_obj.val() == "") { _color = _color_alert; _ret = false; }
                        }
                        break;
                }
                break;
            case "SELECT":
                if (_obj.val() == "-1" || _obj.val() == undefined || _obj.val() == null) { _color = _color_alert; _ret = false; }
                break;
        }
        _obj.css("background-color", _color);
        return _ret;
    },
    getFormValues: function (_selector, _this) {
        var _jsonSave = {};
        $(_selector).each(function () {
            var property = $(this).attr('name');
            var value = "";
            switch (true) {
                case $(this).hasClass("combo"):
                    if ($(this).length == 0) { value = ""; } else { value = $(this).val(); }
                    if (value == null || value == "-1" || value == "0" || value == "") { value = "-1"; }
                    break;
                case $(this).hasClass("check"):
                    if ($(this).prop("checked")) { value = $(this).val(); } else { value = ""; }
                    break;
                default:
                    value = $(this).val();
                    break;
            }
            _jsonSave[property] = value;
        });
        if (_this != undefined) {
            _jsonSave["id"] = _this.attr("data-id");
            _jsonSave["module"] = _this.attr("data-module");
            _jsonSave["model"] = _this.attr("data-model");
            _jsonSave["table"] = _this.attr("data-table");
            _jsonSave["action"] = _this.attr("data-action");
            _jsonSave["function"] = _this.attr("data-action");
            if (_this.attr("data-page") == undefined) { _this.attr("data-page", 1); };
            _jsonSave["page"] = _this.attr("data-page");
        }
        return _jsonSave;
    },
};