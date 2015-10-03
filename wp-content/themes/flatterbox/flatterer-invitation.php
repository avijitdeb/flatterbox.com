<?php

		$sendinvite=false;

		//If this is the first time the box is being configured, get the initial flatterer list and insert into DB.
		if($_GET["initialinvite"])
		{
			$invitePID = $_SESSION["new_flatterbox_id"];
			$sendinvite = true;
		}
		
		//If this page was reached by a flatterer inviting other flatterers, get the new flatterer list and insert into DB.
		if($_GET["flattererinvite"])
		{
			$invitePID = $_SESSION["sentimentPID"];
			$sendinvite = true;
		}
		
		if($_GET["invitePID"])	
		{
			$invitePID = $_GET["invitePID"];
			$sendinvite = true;
		}			
		
		
		if($sendinvite) {
			global $wpdb;
	
			for ($i = 0; $i < count($_SESSION['flattereremails']); $i++)
			{
				if($_SESSION['flattereremails'][$i] != "")
				{
					$duplicatecheck = $wpdb->get_var("SELECT COUNT(PID) FROM flatterers WHERE invalid = 0 AND PID = " . $invitePID . " AND flatterer_email = '" . $_SESSION['flattereremails'][$i] . "'");
					
					if($duplicatecheck == 0)
					{
		
						$wpdb->insert( 
							'flatterers', 
							array( 
								'PID' => $invitePID, 
								'flatterer_email' => $_SESSION["flattereremails"][$i],
								'flatterer_name' => $_SESSION["flatterernames"][$i],
								'responded' => 0
							), 
							array( 
								'%d', 
								'%s',
								'%s',
								'%d'
							) 
						);
						
						$flatterer_id = $wpdb->insert_id;
						
						
						// check if flatterbox is private and assign a passcode if so.
						
						$flatterbox_post = get_post($invitePID); 
						$PID = $flatterbox_post->ID;
						$private = get_field("private",$PID); // For Passcode
						$can_invite = get_field("can_invite",$PID); // For Flatterer able to Invite
						
						if($private) :
							$passcode = getRandomCode();
						
							$wpdb->update( 'flatterers', array('passcode' => $passcode), array( 'FID' => $flatterer_id ), array('%s'), array('%d') ); 
						endif;

						//send invitation e-mail
						$bloginfo = get_bloginfo( 'url' ); 
						$bloginfo2 = home_url();

						//$sentimentneeded = date_create(get_field("date_sentiments_complete",$invitePID));
						$date = DateTime::createFromFormat('d/m/Y', get_field('date_sentiments_complete', $invitePID));
						if( $date ) :
							$sentimentneeded = $date->format('m/d/Y');
						endif;
					
						$post_author_id = get_post_field( 'post_author', $invitePID );
						
						if (strpos(get_field("box_theme",$invitePID), '(name)') > 0) :
							$box_theme = str_replace('(name)', get_field("who_is_this_for",$invitePID), get_field("box_theme",$invitePID));
						else :
							$box_theme = get_field("box_theme",$invitePID).' '.get_field("who_is_this_for",$invitePID);
						endif;

						$instuctions = nl2br(get_field("special_instructions_to_flatterers",$PID));

						$message = '<center>
						<table id="Table_01" width="650" height="848" border="0" cellpadding="0" cellspacing="0">
							<tr>
							  <td width="650" height="307" colspan="5" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240;"><p style="font-weight: bold;">You have been invited by ' . get_field("who_is_this_from",$invitePID) . ' to participate in a gift for </p>
								  <p style="font-size: 40px; font-weight: bold; margin:25px 0; line-height: 40px;">' . get_field("who_is_this_for",$invitePID) . '</p>
								  <p style="font-size: 18px;">The occasion: <strong>' . get_field("occasion",$invitePID) . '</strong></p>
								  <p>The gift is called a Flatterbox and we need just<br>
								  one minute of your time to make it happen.</p>
								<div style="padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold;"><em><a href="' . $bloginfo . '/sentiment/?PID='.$invitePID.'&FID='.$flatterer_id.'" target="_blank" style="color: #fff;">Click here</a></em> to share<br>'.$box_theme.'</div>
								';
								if ( false ) : // Hidding 7/13/2015 
									$message .= '<p style="font-size: 14px; color: #797979; font-weight: bold;">SENTIMENT NEEDED BY: ' . $sentimentneeded .  '</p>';
								endif;
								$message .= '<p style="margin:20px 55px;"><strong>'.$instuctions.'</strong></p>';
								
								if($private) :
									$message .= '<p style="font-size: 18px;">Your Passcode: <strong>' . $passcode . '</strong></p>';
								endif; 						
								
						$message .= '
							<tr>
								<td colspan="5">
									<img src="' . $bloginfo2 . '/emails/sentiment_invite/images/Flatterbox_Eblast_final_08.jpg" width="650" height="300" alt="Your sentiment will appear on a card like this. Click on the link share '.$box_theme.' - Flatterbox"></td>
							</tr>
							<tr>
								<td width="650" height="28" colspan="5" align="center" valign="middle" style="font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in this special gift! | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
							</tr>
						</table>
						</center>';



						add_filter( 'wp_mail_content_type', 'set_html_content_type' );
						$mailheaders = 'From: '.get_field("who_is_this_from",$invitePID).' <flatterbox@flatterbox.com>' . "\r\n" .
										'Reply-To: '.get_field("who_is_this_from",$invitePID).' <'.get_the_author_meta('email',$post_author_id).'>' . "\r\n" ;
						$subject = 'You have been invited to contribute to a gift for '.get_field("who_is_this_for",$invitePID).'!';
						wp_mail( $_SESSION["flattereremails"][$i], $subject, $message, $mailheaders );
						
						// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
						remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
					
					}
					
				}
			}
		
		}
		
		// Remove Session flatterers
		unset($_SESSION['flatterernames']); unset($_SESSION['flattereremails']);
		$_SESSION['flatterernames'] = ''; $_SESSION['flattereremails'] = '';

		/* Placed into functions
		function set_html_content_type() {

			return 'text/html';
		}				
		*/
		function getRandomCode(){
		$an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$su = strlen($an) - 1;
		return substr($an, rand(0, $su), 1) .
				substr($an, rand(0, $su), 1) .
				substr($an, rand(0, $su), 1) .
				substr($an, rand(0, $su), 1) .
				substr($an, rand(0, $su), 1) .
				substr($an, rand(0, $su), 1);
		}		
?>