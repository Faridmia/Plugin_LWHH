;(function($) {
    
    $(document).ready(function(){
       
        $("body").on('click',"#noticeninja .notice-dismiss",function(){
            console.log("clicked");
           // alert("test");

           setCookie("nn-close","1",3*60);
           
        });
    });
    
})(jQuery);

function setCookie(cookieName,cookieValue,expiryInSecond){

    var expiry = new Date();

    expiry.setTime(expiry.getTime() + 1000 * expiryInSecond);

    document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" + expiry.toGMTString() + "; path=/";
}