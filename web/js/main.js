$(function() {
    bindSearchOnPasteNav();
});

function bindSearchOnPasteNav(){
    $('#nav-search-product').on('paste', function(){
        $('#search-product-btn').click();
    });
}