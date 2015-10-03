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
        <input type="hidden" name="redirect_to" id="redirect-step2" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
        <ul class="login_wid">
            <li><input type="button" id="post_login_close" value="Back to Personalize it" tabindex="100" onclick="back_to_Personalize();" /></li>  
            <li><input type="button" id="post_login" value="Click to Continue" tabindex="100" onclick="gotoStep4_cont();" /></li>         
        </ul>
        </div>
    </form>
    <script type="text/javascript">
    	jQuery( ".close" ).hide();
        if (jQuery('#process_check').val() != "1") {
            jQuery('#post_login_close').hide();
        }
    	function gotoStep4_cont(){
            if (jQuery('#process_check').val() == "1") {
    			jQuery('#gform_submit_button_34').trigger( 'click' );
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