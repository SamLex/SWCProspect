function popupChain(popupFromSelector,popupToSelector) {
     $(popupFromSelector).popup("close");
    window.setTimeout(function(){
        $(popupToSelector).popup("open");
    }, 150);
}