<?php 

/* Template Name: Flatterer Step 3 Account Creation and Invite */

get_header(); ?>

<?php

// Redirect if not allowed to show.
if ( get_field('can_share') ) : wp_redirect( home_url() ); exit; endif;


if(isset($_SESSION["sentimenttext"]) && !isset($_GET["fromlist"]) && !isset($_GET["fromPopup"]))
{

global $wpdb;

$wpdb->insert( 
	'sentiments', 
	array( 
		'FID' => $_SESSION["sentimentFID"], 
		'PID' => $_SESSION["sentimentPID"],
		'sentiment_text' => $_SESSION["sentimenttext"],
		'sentiment_name' => $_SESSION["sentimentname"],
		'private' => $_SESSION["sentimentprivacy"]
	), 
	array( 
		'%d', 
		'%d',
		'%s',
		'%s',
		'%d'
	) 
);

$wpdb->update( 'flatterers', array('responded' => '1'), array( 'FID' => $_SESSION["sentimentFID"] ), array('%d'), array('%d') ); 

}

foreach (get_the_terms($_SESSION["sentimentPID"], 'flatterbox_type') as $cat) : 
	$catid =  $cat->term_id;
endforeach;
?>		
<?php // $page_background_photo = getOccasionBGImg($catid); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main" class="flattererview">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1">Compose Sentiment</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/?listview=1">Review Sentiment</a></li>
			<li class="active"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite/">Invite Flatterers</a></li>
			<li>Send Invitations</li>
			<?php
				$laststep = 'Create Account';
				if ( is_user_logged_in() ) : $laststep = 'Complete'; endif;
			?>
			<li><?php echo $laststep ?></li>
		</ul>
		<div class="generic">
			<?php if (has_post_thumbnail()) : ?>
			<div class="leftcol">
				<?php the_post_thumbnail(); ?>
			</div>
			<div class="rightcol">
				<?php the_content(); ?>
			</div>
			<?php else : ?>
				<?php the_content(); ?>
			<?php endif; ?>
				<form action="<?php echo do_shortcode('[site_url]'); ?>/flatterer-invitation-review" method="post" id="inviteform">
				
				<div class="invite-actions">
				<!-- Any link with a class="cs_import" will start the import process -->
				<a class="cs_import" style="height: auto;overflow: hidden;display: block;width: 284px !important;margin-bottom: 20px !important;float:left;">Add from Address Book</a>
				<!--<a class="btn email-preview" style="height: auto;overflow: hidden;display: block;width: 284px !important;margin-bottom: 20px !important;float:right;margin-left:20px;" href="<?php echo do_shortcode('[site_url]'); ?>/preview-email/?PID=<?php echo $_SESSION["sentimentPID"] ?>">Preview E-mail</a>-->
				
				<!-- This textarea will be populated with the contacts returned by CloudSponge -->
				<input type="hidden" id="contact_list" />
				</div>
				<div class="invite-actions">
					<?php if (strlen(get_field('unique_url', $_SESSION["sentimentPID"])) > 0 ) : ?>
						<p>Share your Flatterbox with Flatterers using your unique link : <input type="text" readonly name="unique-url" id="unique-url" value="<?php echo home_url().'/?fb='.get_field('unique_url', $_GET["PID"]); ?>"></p>
					<?php endif; ?>
				</div>
				<div style="width:100%;clear:both;"></div>
				<?php 
				$flatterers ='';
				if(isset($_SESSION["flattereremails"])) :
					for($i = 0, $size = count($_SESSION["flattereremails"]); $i < $size; ++$i) :
						if ($i > 0) : $flatterers .= ',
'; endif;
						$flatterers .= $_SESSION["flatterernames"][$i].' '.$_SESSION["flattereremails"][$i];
					endfor;
				endif;
				?>
				<div id="flattererfield-single">
					<p style="clear:both;">Add Flatterers, separated by a comma 'John Doe jdoe@emailaddress.com, Jane Doe jane_doe@otheremail.com'.</p>
					<textarea rows="20" cols="" name="flatterers" id="flatterers"><?php echo $flatterers; ?></textarea>
				
					<input type="hidden" id="redirectback" name="redirectback" value value="0" />
					<input type="submit" id="submitinvites" value="submit" style="display:none;" />
				</div>
				<div id="flatterer-preview">
					<h3>Your Flatterbox Invite – Preview</h3>
					<?php 
						//include ("preview-email.php"); // Changed to the Functions
						echo get_preview_email($_SESSION["sentimentPID"]);
					?>
				</div>
				<?php if (false) : ?>
				<div id="flaterrerfields">
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" required /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" required /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					
					<input type="submit" id="submitinvites" value="submit" style="display:none;" />
					
					<script>
					<?php
						if(isset($_SESSION["flattereremails"]))
						{
							
							for($i = 0, $size = count($_SESSION["flattereremails"]); $i < $size; ++$i) {
						    ?>
							if (<?php echo $i ?> > 20) {
								var code2append = '<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>';

								jQuery("#flaterrerfields").append(code2append);							
							}
							
							document.getElementsByName('flatterername[]')[<?php echo $i ?>].value='<?php echo $_SESSION["flatterernames"][$i]; ?>';
							document.getElementsByName('flattereremail[]')[<?php echo $i ?>].value='<?php echo $_SESSION["flattereremails"][$i]; ?>';
							
							<?php
							}							
												
						}
					?>
					</script>					
					
				</div>
				<?php endif; ?>
				
				<!-- Include the script anywhere on your page -->
				<script>
				
					// these values will hold the owner information
					var owner_email, owner_first_name, owner_last_name;
					var appendInTextarea = true;  // whether to append to existing contacts in the textarea
					var emailSep = ", ";  // email address separator to use in textarea
					function populateTextarea(contacts, source, owner) {
					  var contact, name, email, entry;
					  var emails = [];
					  var textarea = document.getElementById('contact_list');
					  
					  // preserve the original value in the textarea
					  if (appendInTextarea && textarea.value.strip().length > 0) {
						//emails = textarea.value.split(emailSep);
					  }
					  addFlattererRow();
					  // format each email address properly
					  for (var i = 0; i < contacts.length; i++) {
						
						//if (i > 20) {
							//var code2append = '<div class="invitegroup"><div class="invitegroup-left"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>';

							//jQuery("#flaterrerfields").append(code2append);							
						//}
						
						contact = contacts[i];
						
						// Removed based on using single text area
						//document.getElementsByName('flatterername[]')[i].value = contact.fullName();
						//document.getElementsByName('flattereremail[]')[i].value = contact.selectedEmail();

						name = contact.fullName();
						email = contact.selectedEmail();
						//entry = name + "<" + email +">";
						entry = email;
						if (emails.indexOf(entry) < 0) {
						  emails.push(entry);
						}
					  }
					  // dump everything into the textarea
					  textarea.value = emails.join(emailSep);
					  //jQuery('#flatterers').val( emails.join(emailSep) ); // Update to return if possible rather than space
					  //jQuery('#flatterers_tagsinput').val( emails.join(emailSep) ); // Update to return if possible rather than space
					  //jQuery('#flatterers_tag').val( emails.join(emailSep) ); // Update to return if possible rather than space
					  jQuery('#flatterers').importTags(emails.join(emailSep));
					  
					  // capture the owner information
					  owner_email = (owner && owner.email && owner.email[0] && owner.email[0].address) || "";
					  owner_first_name = (owner && owner.first_name) || "";
					  owner_last_name = (owner && owner.last_name) || "";
					}				
				
				// set any options here, for this example, we'll simply populate the contacts in the textarea above
				window.csPageOptions = {
				  textarea_id:"contact_list",
				  afterSubmitContacts:populateTextarea
				};
				// Asynchronously include the widget library.
				(function(u){
				  var d=document,s='script',a=d.createElement(s),m=d.getElementsByTagName(s)[0];
				  a.async=1;a.src=u;m.parentNode.insertBefore(a,m);
				})('//api.cloudsponge.com/widget/57e8390717e2cb12c10c80a180d3723ea89afdf7.js'); // 8d5db3607db5701cacdf01a3f344b7e1c2755ecf
				</script>					
				
				</form>
				<br/>
		</div>
				<div class="control-bar bottom">
					<?php if (false) : ?>
						<a href="javascript:;" onclick="addFlattererRow();" class="btn">Add A Flatterer</a>
					<?php endif; ?>
					<a href="javascript:;" class="btn save" onclick="jQuery('#submitinvites').trigger('click');">Save &amp Continue</a>
				</div>
	</div>
</main>
<?php endwhile; endif; ?>

<script type="text/javascript">
	jQuery(function() {
		jQuery('#flatterers').tagsInput({
      	delimiter: [',',';'],
			width:'auto',
			height: '300px',
		   'defaultText':'add an email',
		});
	});

	jQuery("#unique-url").on("click", function () {
	   jQuery(this).select();
	});
</script>
<?php get_footer(); ?>		

<script>
function addFlattererRow()
{
	var code2append = '<div class="invitegroup"><div class="invitegroup-left"><b>Email</b><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div><div class="invitegroup-right"><b>Name</b><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div></div>';

	jQuery("#flaterrerfields").append(code2append);
}
</script>		