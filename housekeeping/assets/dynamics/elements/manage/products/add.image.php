<?php

    require_once "../../../../../../mysql/_.session.php";

    if(isset($_REQUEST['url'])) {
  
        $isid = false;
        if(isset($_REQUEST['id'])) {
            $isid = true;
            $id = htmlspecialchars($_REQUEST['id']);
        }
        
        $url = htmlspecialchars($_REQUEST['url']);
        
?>

       <div class="item lt" data-json='[{"id":"<?php if($isid === true) { echo $id; } else { echo $url; } ?>"}]'>
           <div class="actual-image mshd-1 tran-all-cubic">
               <img onload="fadeIn(this)" class="vishid opa0" src="<?php echo $imgurl . '/products/' . $url; ?>">
           </div>
       </div>
  
<?php
        
    } else {
        exit('fuck you');
    }

?>