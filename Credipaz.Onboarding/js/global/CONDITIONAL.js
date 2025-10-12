var _CONDITIONAL = {
    onPrevalidationSplit: function (data) {
        switch (parseInt(data.data.id_type_request)) {
            case 1: //Credito Credipaz
                _NMF._preValidationSelected = "btnPermiteCreditoCredipaz";
                break;
            case 5: //Credito Credipaz Vivienda
                _NMF._preValidationSelected = "btnPermiteCreditoCredipazVivienda";
                break;
            case 6: //Credito Credipaz Hogar
                _NMF._preValidationSelected = "btnPermiteCreditoCredipazHogar";
                break;
            case 7: //Credito Credipaz Consumo
                _NMF._preValidationSelected = "btnPermiteCreditoCredipazConsumo";
                break;
            case 2: //Renovación credito Credipaz
                _NMF._preValidationSelected = "btnPermiteRenovacionCreditoCredipaz";
                break;
            case 3: //Credito Amutra
                _NMF._preValidationSelected = "btnPermiteCreditoAmutra";
                break;
            case 8: //Credito Amutra Vivienda
                _NMF._preValidationSelected = "btnPermiteCreditoAmutraVivienda";
                break;
            case 9: //Credito Amutra Hogar
                _NMF._preValidationSelected = "btnPermiteCreditoAmutraHogar";
                break;
            case 10: //Credito Amutra Consumo
                _NMF._preValidationSelected = "btnPermiteCreditoAmutraConsumo";
                break;
            case 4: //Renovación Credito Amutra
                _NMF._preValidationSelected = "btnPermiteRenovacionCreditoAmutra";
                break;
            case 17: //Venta MIL
                _NMF._preValidationSelected = "btnPermiteVentaMIL";
                break;
            case 19: //Alta cambio limite credito
                _NMF._preValidationSelected = "btnPermiteCambioLimiteCredito";
                break;
            case 351: //Alta de tarjeta
                _NMF._preValidationSelected = "btnPermiteAltaTarjeta";
                break;
        }
    },
};
