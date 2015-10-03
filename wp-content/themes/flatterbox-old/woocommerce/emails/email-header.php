<?php
/**
 * Email Header
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Load colours
$bg 		= get_option( 'woocommerce_email_background_color' );
$body		= get_option( 'woocommerce_email_body_background_color' );
$base 		= get_option( 'woocommerce_email_base_color' );
$base_text 	= wc_light_or_dark( $base, '#202020', '#ffffff' );
$text 		= get_option( 'woocommerce_email_text_color' );

$bg_darker_10 = wc_hex_darker( $bg, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );

// For gmail compatibility, including CSS styles in head/body are stripped out therefore styles need to be inline. These variables contain rules which are added to the template inline. !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
$wrapper = "
	background-color: " . esc_attr( $bg ) . ";
	width:100%;
	-webkit-text-size-adjust:none !important;
	margin:0;
	padding: 70px 0 70px 0;
";
$template_container = "
	box-shadow:0 0 0 3px rgba(0,0,0,0.025) !important;
	border-radius:6px !important;
	background-color: " . esc_attr( $body ) . ";
	border: 1px solid $bg_darker_10;
	border-radius:6px !important;
";
$template_header = "
	background-color: " . esc_attr( $base ) .";
	color: $base_text;
	border-top-left-radius:6px !important;
	border-top-right-radius:6px !important;
	border-bottom: 0;
	font-family:Arial;
	font-weight:bold;
	line-height:100%;
	vertical-align:middle;
";
$body_content = "
	background-color: " . esc_attr( $body ) . ";
	border-radius:6px !important;
";
$body_content_inner = "
	color: $text_lighter_20;
	font-family:Arial;
	font-size:14px;
	line-height:150%;
	text-align:left;
";
$header_content_h1 = "
	color: #0e2240;
	display:block;
	font-family:Arial;
	font-size:30px;
	font-weight:bold;
	text-align:center;
	line-height: 150%;
";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo get_bloginfo( 'name' ); ?></title>
	</head>
    <body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table id="Table_01" width="650" border="0" cellpadding="0" cellspacing="0">
	<tr><?php $bloginfo2 = "http://www.sbdcstage.com/flatterbox"; ?>
		<td width="650" height="107" colspan="3" align="center" valign="top"><img src="<?php echo $bloginfo2; ?>/emails/sales/images/fb_order_header_logo.png" width="390" height="80" alt="Your Flatterbox® Order"></td>
	</tr>
	<tr>
	  <td width="650" colspan="3" align="left" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240;">
   		<h1 style="<?php echo $header_content_h1; ?>"><?php echo $email_heading; ?></h1>

