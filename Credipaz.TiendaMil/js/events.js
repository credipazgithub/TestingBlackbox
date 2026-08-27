$("body").off("click", ".btn-close-received").on("click", ".btn-close-received", function () {
    _F.onDestroyModal("#initReceived");
});
$("body").off("click", ".btn-addToCart").on("click", ".btn-addToCart", function () {
    _F.onAddToCart($(this));
});
$("body").off("click", ".btn-removeItem").on("click", ".btn-removeItem", function () {
    _F.onRemoveItem($(this));
});
$("body").off("click", ".btn-cancel-alert").on("click", ".btn-cancel-alert", function () {
    _F.onDestroyModal("#alterModal");
});
$("body").off("input", ".onlyNumbers").on("input", ".onlyNumbers", function () {
    _T.onlyNumbers($(this));
});
$("body").off("click", ".item-received").on("click", ".item-received", function () {
    _F.onModalItemReceived($(this));
});
$("body").off("click", ".btn-shoppingcart").on("click", ".btn-shoppingcart", function () {
    _F.onModalShoppingCart($(this));
});
$("body").off("click", ".btn-accept-alert").on("click", ".btn-accept-alert", function () {
    _F.onCreateNewVideoRoom($(this));
});
$("body").off("click", ".btn-join-live").on("click", ".btn-join-live", function () {
    _F.onJoinOpenSession();
});
$("body").off("click", ".btn-close-cart").on("click", ".btn-close-cart", function () {
    _F.onDestroyModal("#initShoppingCart");
});
$("body").off("click", ".btn-delete-item-cart").on("click", ".btn-delete-item-cart", function () {
    _F.onDeleteItemCart($(this));
});
$("body").off("click", ".btn-payCart").on("click", ".btn-payCart", function () {
    var _items = [];
    $(".itemPrecio").each(function () {
        _items.push({ "description": $(this).attr("data-item"), "amount": parseFloat($(this).val()) });
    });
    _F.onSelectPaymentPlatform(_items);
});
