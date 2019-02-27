$(function() {
    bindSearchOnPasteNav();
});

function bindSearchOnPasteNav(){
    $('#nav-search-product').on('paste', function(){
        $('#search-product-btn').click();
    });
}

function bindConfirm(message){
    $(document).on('click', '.need-confirm', function(e){
        var f = confirm(message);
        if(!f){
            e.preventDefault();
        }
    });
}