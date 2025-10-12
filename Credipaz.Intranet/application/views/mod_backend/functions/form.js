$("body").off("change", ".cboPivotFunctions").on("change", ".cboPivotFunctions", function () {
	$(".id_user_map").val("");
	$(".id_group").val("");
	$(".div-functions").html("");
	var _id_function = $(this).val();
	var _json = {
		"module": "mod_backend",
		"table": "users",
		"model": "users",
		"where": "id IN (SELECT id_user FROM mod_backend_rel_users_groups WHERE id_group IN (SELECT id_group FROM mod_backend_rel_groups_functions WHERE id_function=" + _id_function + "))",
		"order": "username ASC",
		"page": -1,
		"pagesize": -1
	};
	_AJAX.UiGet(_json).then(function (data) {
		var _html = "<ul>";
		$.each(data.data, function (i, item) { _html += ("<li>" + item.username + "</li>"); });
		_html += "</ul>";
		$(".div-users").html(_html);
	});
	var _json2 = {
		"module": "mod_backend",
		"table": "groups",
		"model": "groups",
		"where": "id IN (SELECT id_user FROM mod_backend_rel_users_groups WHERE id_group IN (SELECT id_group FROM mod_backend_rel_groups_functions WHERE id_function=" + _id_function + "))",
		"order": "description ASC",
		"page": -1,
		"pagesize": -1
	};
	_AJAX.UiGet(_json2).then(function (data) {
		var _html = "<ul>";
		$.each(data.data, function (i, item) { _html += ("<li>" + item.description + "</li>"); });
		_html += "</ul>";
		$(".div-groups").html(_html);
	});
});

$("body").off("change", ".cboPivotGroups").on("change", ".cboPivotGroups", function () {
	$(".id_user_map").val("");
	$(".id_function").val("");
	$(".div-groups").html("");
	var _id_group= $(this).val();
	var _json = {
		"module": "mod_backend",
		"table": "users",
		"model": "users",
		"where": "id IN (SELECT id_user FROM mod_backend_rel_users_groups WHERE id_group=" + _id_group + ")",
		"order": "username ASC",
		"page": -1,
		"pagesize": -1
	};
	_AJAX.UiGet(_json).then(function (data) {
		var _html = "<ul>";
		$.each(data.data, function (i, item) { _html += ("<li>" + item.username + "</li>"); });
		_html += "</ul>";
		$(".div-users").html(_html);
	});
	var _json2 = {
		"module": "mod_backend",
		"table": "functions",
		"model": "functions",
		"where": "id IN (SELECT id_function FROM mod_backend_rel_groups_functions WHERE id_group=" + _id_group + ")",
		"order": "id_parent ASC, description ASC",
		"page": -1,
		"pagesize": -1
	};
	_AJAX.UiGet(_json2).then(function (data) {
		var _html = "<ul>";
		$.each(data.data, function (i, item) { _html += ("<li>" + item.description + "</li>"); });
		_html += "</ul>";
		$(".div-functions").html(_html);
	});
});
$("body").off("change", ".cboPivotUsers").on("change", ".cboPivotUsers", function () {
	$(".id_function").val("");
	$(".id_group").val("");
	$(".div-users").html("");
	var _id_user = $(this).val();
	var _json = {
		"module": "mod_backend",
		"table": "functions",
		"model": "functions",
		"where": "id IN (SELECT id_function FROM mod_backend_rel_groups_functions WHERE id_group IN (SELECT id_group FROM mod_backend_rel_users_groups WHERE id_user=" + _id_user + "))",
		"order": "id_parent ASC, description ASC",
		"page": -1,
		"pagesize": -1
	};
	_AJAX.UiGet(_json).then(function (data) {
		var _html = "<ul>";
		$.each(data.data, function (i, item) { _html += ("<li>" + item.description + "</li>"); });
		_html += "</ul>";
		$(".div-functions").html(_html);
	});
	var _json2 = {
		"module": "mod_backend",
		"table": "groups",
		"model": "groups",
		"where": "id IN (SELECT id_group FROM mod_backend_rel_users_groups WHERE id_user=" + _id_user + ")",
		"order": "description ASC",
		"page": -1,
		"pagesize": -1
	};
	_AJAX.UiGet(_json2).then(function (data) {
		var _html = "<ul>";
		$.each(data.data, function (i, item) { _html += ("<li>" + item.description + "</li>"); });
		_html += "</ul>";
		$(".div-groups").html(_html);
	});
});







