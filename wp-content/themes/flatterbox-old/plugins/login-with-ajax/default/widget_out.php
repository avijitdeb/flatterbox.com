<?php 
/*
 * This is the page users will see logged out. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
    <div class="lwa lwa-default"><?php //class must be here, and if this is a template, class name should be that of template directory ?>
        <form class="lwa-form" action="<?php echo esc_attr(LoginWithAjax::$url_login); ?>" method="post">
            <div>
            <span class="lwa-status"></span>
            <input type="hidden" name="login-with-ajax" value="login" />
            <input type="hidden" name="redirect" id="redirect-step2-old" value="<?php echo site_url(); ?>">
            <input type="hidden" name="redirect_to" id="redirect-step2-tmp" value="<?php echo esc_url($lwa_data['redirect']); ?>" />
            <ul class="login_wid">
                <li>Email Address</li>
                <li><input type="text" name="log" required="required"></li>
                <li>Password</li>
                <li><input type="password" name="pwd" required="required"></li>
                <li><input type="submit" name="wp-submit" id="lwa_wp-submit" value="<?php esc_attr_e('Log In', 'login-with-ajax'); ?>" tabindex="100" /></li>
                <li class="extra-links"><a href="<?php echo home_url('/forgot-password/'); ?>">Lost Password?</a></li>          
            </ul>
<?php do_action('login_form'); ?>
            </div>
        </form>
        <?php echo do_shortcode('[divider]'); ?>
        <h2>First Time?</h2>
        <a id="createaccountlink" href="#" class="btn save" onclick="mCreate();">Create Your Account</a>
        <input type="hidden" name="createaccountlink" id="createaccountlink-step2" value="<?php echo site_url(); ?>/create-an-account/">
    </div>

    <script type="text/javascript">
        function mCreate() {
            nLocate = jQuery('#createaccountlink-step2').val();
            direct = false;
            //window.alert(direct);
            //window.location.href = nLocate;
            window.location.replace(nLocate);
            return false;
        }
    </script>