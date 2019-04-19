$(function() {
    bindSearchOnPasteNav();
    beforeSearchButtonClick();
    bindAutocompleteSearchNav();
    bindSomeEffect();
//    bindNotification();
    bindCategoryAutocomplete();
});

function bindSomeEffect(){
    searchEffect();
}

function searchEffect(){
    $('#nav-search-product').on('focus', function(){
        $('#search-product-btn').css('border-radius','0 4px 4px 0');
    });
    $('#nav-search-product').on('focusout', function(){
        $('#search-product-btn').css('border-radius','0 33px 33px 0');
    });
}

function bindSearchOnPasteNav(){
    $(document).on('paste', '#nav-search-product', function(){
        bindSearchNav();
    });
}

function bindSearchNav(){
    $('#nav-search-form').submit();
}

function beforeSearchButtonClick(){
    $(document).on('click', '#search-product-btn', function(event){
        if( $('#nav-search-product').val() == '' ){
            event.preventDefault();
        }
    });
}

function bindAutocompleteSearchNav(){
    $("#nav-search-product").bind('focus', function(){ 
        $(this).autocomplete("search"); 
    });
}

function bindCategoryAutocomplete(){
    $.widget("custom.autocomplete", $.ui.autocomplete, {
        _create: function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function (ul, items) {
            var that = this,
                    currentCategory = "";
            $.each(items, function (index, item) {
                var li;
                if (item.category != currentCategory && typeof item.category !== "undefined") {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
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

function bindNotification(){
    if (!Notification) {
        console.log('Desktop notifications not available in your browser. Try Chromium.'); 
        return;
    }
    if (Notification.permission !== "granted"){
        Notification.requestPermission();
    } else {
//        notifyMe();
    }
}

function notifyMe() {
    // Let's check if the browser supports notifications
    if (!("Notification" in window)) {
        console.log("This browser does not support system notifications");
    }

    // Let's check whether notification permissions have already been granted
    else if (Notification.permission === "granted") {
        // If it's okay let's create a notification
        var notification = new Notification('Notification title', {
            icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
            body: "Hey there! You've been notified!",
        });
    }
    
    notification.onclick = function () {
        window.open("http://stackoverflow.com/a/13328397/1269037");      
    };
    
    // Otherwise, we need to ask the user for permission
//    else if (Notification.permission !== 'denied') {
//        Notification.requestPermission(function (permission) {
//            // If the user accepts, let's create a notification
//            if (permission === "granted") {
//                var notification = new Notification("Hi there!");
//            }
//        });
//    }
}