<?php 
/*
 * This is the page users will see logged in. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<div class="lwa">
	<?php 
		global $current_user;
		get_currentuserinfo();
	?>
	<form class="lwa-form" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
        <div>
        	<br/>
        <input type="hidden" name="redirect_to" id="redirect-step2" value="" />
        <ul class="login_wid">
            <?php if (false) : // old logic, client wants to only show continue button can switch out as needed 9/17/2015 ?>
            <li><input type="button" id="post_login_close" value="Back to Personalize it" tabindex="100" onclick="back_to_Personalize();" /></li>
            <?php endif; ?> 
            <li><input type="button" id="post_login" value="Click to Continue" tabindex="100" onclick="gotoStep4_cont();" /></li>
        </ul>
        </div>
    </form>
    <script type="text/javascript">
        <?php 
        if ( isset($_SESSION["returnURL"]) ) :
            ?>
            jQuery('#redirect-step2').val('<?php echo $_SESSION["returnURL"]; ?>');
            <?php    
        endif;
        ?>
    	jQuery( ".close" ).hide(); 
        //window.alert(jQuery('#process_check').val());
        
        /* Old logic with back to personalize it can be placed back in 9/17/2015
        if (jQuery('#process_check').val() != "1") {
            jQuery('#post_login_close').hide();
        }
        */
    	function gotoStep4_cont(){
            if ( false && jQuery('#process_check').val() == "1") { /* Not auto submitting form any more - can be added back in 9/17/2015 */
    			jQuery('#gform_submit_button_34').trigger( 'click' );
            } else if (jQuery('#redirect-step2').val().length > 0) {
                nLocate = jQuery('#redirect-step2').val();
                //window.alert(nLocate);
                window.location.replace(nLocate);
            } else {
                jQuery( ".close" ).trigger('click');
                location.reload();
            }
		}
        function back_to_Personalize() {
            jQuery( "#auto_submit" ).val(1);
            //window.alert(jQuery('#auto_submit').val());
            jQuery( ".close" ).trigger('click');
        }
    </script>
</div>