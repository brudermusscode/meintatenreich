class Overlay {

    static add(append, floatingLoader = true, card = false) {

        let $overlay, $loader, array, hw, position;

        array = {
            overlay : null,
            loader  : null
        };
    
        // set body's overflow to hidden
        $('body').addClass('ovhid');
    
        // check if append content is card or body
        if(!card) {
            hw = "height:100vh;width:100vw;";
            position = "posfix";
        } else {
            hw = "height:100%;width:100%;";
            position = "posabs";
        }

        // append the page overlay to passed param append
        $overlay = append.append('<page-overlay class="tran-all opa0 ' + position + '" style="' + hw + 'background:rgba(255,255,255,.92);"></page-overlay>');

        // store added page overlay
        $overlay = append.find("page-overlay");

        // add overlay to array
        array.overlay = $overlay;

        if(!card) {

            // append closing area to the overlay
            $overlay.append('<close data-action="close-overlay"><div class="closer"><p>Klicke hier, um das Overlay zu schließen</p></div></close>');
        }
    
        // set timeout function to get full fade in transition
        setTimeout(function(){
    
            // make overlay visible
            $overlay.css({ "opacity" : "1" });
        }, 10);

        // at the end, add the loader
        $loader = this.addLoader($overlay, true);
        
        // add loader to array
        array.loader = $loader;

        // return the overlay as array
        return array;
    }
    
    static addLoader(append, floatingLoader) {
        
        let float, $loader;

        if(floatingLoader) {
            float = "almid";
        } else {
            float = "almid-h mt24 mb42";
        }

        append.append('<color-loader class="' + float + '"><inr><circl3 class="color-loader1"></circl3><circl3 class="color-loader2"></circl3></inr></color-loader>');
        return $loader = append.find("color-loader");
    }

    static close(append) {
        
        let $overlay = append.find("page-overlay");

        // let overlay fade out
        $overlay.css('opacity', '0');
    
        // set timeout for smooth fade out
        setTimeout(function(){

            // remove overlay
            $overlay.remove();
        }, 400);

        // reove overflow hidden of body
        append.removeClass('ovhid');
    }
}