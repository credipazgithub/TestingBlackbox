var _PHOTO = {
    onGetPicture: function (_this) {
        try {
            var file = document.getElementById('fileLibrary').files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                var _data = e.target.result;
                _PHOTO.onPhotoDataSuccess(_data);
            }
            reader.readAsDataURL(file); 
        } catch (err) {
            alert("No hemos podido tomar la imagen indicada");
        }
    },
    onPhotoDataSuccess: function (_imageData) {
        var _x = _imageData.split(",");
        var _json = {
            "carbon_copy": "0",
            "message": ("Imagen: " + _TOOLS.getNow()),
            "raw_data": ('{"mime":"' + _x[0] + '","base64":"' + _x[1] + '"}'),
            "id_charge_code": _NMF._id_charge_code,
            "id_type_item": "1",
            "id_type_direction": "1",
            "type_media": _x[0]
        };
        _API.UiSaveMessage(_json).then(function (data) {
            if (data.status == "OK") {
                alert("Se ha enviado la imagen en forma exitosa");
            } else {
                throw data;
            }
        }).catch(function (err) {
            alert("No se ha enviado ninguna imagen.  " + err.message);
        });
    },
    onPhotoFail: function (message) {
        alert("No se ha enviado ninguna imagen.  Reintente");
    },
}
