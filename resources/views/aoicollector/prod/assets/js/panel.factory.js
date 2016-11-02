app.factory('Panel',function() {
    var panel_barcode_length = 10;
    var panelsony_barcode_length = 19;

    var interfaz = {};

    interfaz.valid = function(panelbarcode) {
        if (panelbarcode) {
            if (
                $.isNumeric(panelbarcode)
                &&
                (
                    panelbarcode.length == panel_barcode_length ||
                    panelbarcode.length == panelsony_barcode_length
                )
            ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    };

    return interfaz;
});
