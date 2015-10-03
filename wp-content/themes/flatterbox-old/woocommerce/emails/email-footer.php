<?php
/**
 * Email Footer
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$base = get_option( 'woocommerce_email_base_color' );

$base_lighter_40 = wc_hex_lighter( $base, 40 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline.
$template_footer = "
	border-top:0;
	-webkit-border-radius:6px;
";

$credit = "
	border:0;
	color: $base_lighter_40;
	font-family: Arial;
	font-size:12px;
	line-height:125%;
	text-align:center;
";
?>
															</td>
	</tr>    
	<tr>
		<td width="650" height="28" colspan="3" align="center" valign="middle" style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in their Flatterbox! <em>- From the Flatterbox Team</em> | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
    </body>
</html>