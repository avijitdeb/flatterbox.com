<?php 

/* Template Name: Invite More Flatterers */

get_header('steps'); ?>
<script>
	function addFlattererRow()
	{
		console.log('Fired!');
		var code2append = '<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>';

		jQuery("#flaterrerfields").append(code2append);
	}


</script>	
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<div class="lightbox recipients">
		<?php include('includes/lightbox-recipients.php'); ?>
	</div>
	<div class="lightbox message">
		<?php include('includes/lightbox-message.php'); ?>
	</div>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>

		<div class="box">
			<div class="box-padding">
			
				<h2>Invite Flatterers to Contribute</h2>
				<form action="<?php echo do_shortcode('[site_url]'); ?>/invite-more-confirm/?PID=<?php echo $_GET["PID"] ?>" method="post" id="inviteform">
				<div class="invite-actions">
					<p>Flatterers who have not responded to their email invitation will automatically receive a reminder both <?php echo intval(get_field('first_reminder_days', 'option')); ?> days and <?php echo intval(get_field('second_reminder_days', 'option')); ?> days prior to the sentiment due date.</p>
					<!-- Any link with a class="cs_import" will start the import process -->
					<a class="cs_import">Add from Address Book</a>
					<!--<a class="btn email-preview" href="<?php echo do_shortcode('[site_url]'); ?>/preview-email/?PID=<?php echo $_GET["PID"] ?>">Preview E-mail</a>-->
					<a href="#" class="btn" id="viewinvites">View Invites</a>
				</div>
				<div class="invite-actions">
					<?php if (strlen(get_field('unique_url', $_GET["PID"])) > 0 ) : ?>
						<?php if ( check_for_mobile() ) : // Is Mobile ?>
							<p class="pre_padding">Share your Flatterbox with Flatterers using your unique link : </p>
							<div><pre><?php echo home_url().'/?fb='.get_field('unique_url', $_GET["PID"]); ?></pre></div>
						<?php else : ?>
							<p>Share your Flatterbox with Flatterers using your unique link : 
								<input type="text" readonly='readonly' name="unique-url" id="unique-url" value="<?php echo home_url().'/?fb='.get_field('unique_url', $_GET["PID"]); ?>">
							</p>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<div style="clear:both;"></div>
				<!-- This textarea will be populated with the contacts returned by CloudSponge -->
				<input type="hidden" id="contact_list" />
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
					<br/><br/>
					<p>Add Flatterers, separated by a comma 'John Doe jdoe@emailaddress.com, Jane Doe jane_doe@otheremail.com'.</p>
					<textarea rows="20" cols="" name="flatterers" id="flatterers"><?php echo $flatterers; ?></textarea>
				
					<input type="hidden" id="redirectback" name="redirectback" value value="0" />
					<input type="submit" id="submitinvites" value="submit" style="display:none;" />
					<div id="flattereremails"></div>
				</div>
				<div id="flatterer-preview">
					<h3>Your Flatterbox Invite – Preview</h3>
					<?php 
						//include ("preview-email.php"); // Changed to the Functions
						echo get_preview_email($_GET["PID"]);
					?>
				</div>
				<?php if (false) : ?>

				<br/><br/>
				
				<div id="flaterrerfields">
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" required /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" required /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>
					<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>															
					
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
				</form>
				
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
						
						if (i > 20) {
							var code2append = '<div class="invitegroup"><div class="invitegroup-left"><strong>Name</strong><br/><input type="text" name="flatterername[]" id="flaterrername[]" /></div><div class="invitegroup-right"><strong>Email</strong><br/><input type="text" name="flattereremail[]" id="flaterreremail[]" /></div></div>';

							jQuery("#flaterrerfields").append(code2append);							
						}
						
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
					  //document.getElementById('flatterers').value = emails.join(emailSep); // Update to return if possible rather than space
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
				
				<br/>
			</div>

			<?php if (false) : ?>

			<div class="styles previewstyles">
				<?php if (false) : ?>
				<div class="hold">
					<h2>Card Color</h2>
					<div class="choose">
						<div class="selected"><img src="<?php echo $_SESSION["cardcolorimg"]; ?>" /></div>
					</div>
				</div>
				<!--
				<div class="hold add">
					<h2>Paper Style</h2>
					<div class="choose">
						<div class="selected"><span>LINEN</span></div>
					</div>
				</div>
				-->
			<?php endif; ?>
				<div class="hold add">
					<h2>Your Flatterbox</h2>
					<div class="choose">
						<div class="selected"><img src="<?php echo $_SESSION["boxtypeimg"]; ?>" style="width:100%;" /></div>
					</div>
				</div>
			</div>

			<?php endif; ?>

		</div>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox" class="btn back">Back to My Flatterbox</a>
			<a href="javascript:;" onclick="addFlattererRow();" class="btn" style="display: none;">Add A Flatterer</a>
			<a href="javascript:;" class="btn save" onclick="jQuery('#submitinvites').trigger('click');">Next</a>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<script type="text/javascript">
	jQuery( "#viewinvites" ).click(function(event) {
		event.preventDefault();
	  	jQuery( ".lightbox.recipients" ).fadeToggle("fast");
	});
	jQuery(function() {
		jQuery('#flatterers').tagsInput({
      	delimiter: [',',';'],
			width:'auto',
			height: '300px',
		   'defaultText':'add an email',
		});
	});

	<?php if ( check_for_mobile() ) : // Is Mobile ?>
	/* Removed to place Text ont he page.
	jQuery("#unique-url").on("click", function () {
		doMsg('', 'To copy the URL, press and and HOLD the URL then select Copy.','<div><pre><?php echo home_url().'/?fb='.get_field('unique_url', $_GET["PID"]); ?></pre></div>', function close()
						{
							jQuery( '.close' ).trigger('click');
						});
	});
	*/
	<?php else : ?>
	jQuery("#unique-url").on("click", function () {
		jQuery(this).select();
	});
	<?php endif; ?>

	function doMsg(title, subtitle, msg, clsFn)
	{
	  	jQuery( ".lightbox.message" ).fadeToggle("fast");
	    var confirmBox = jQuery("#msgBox");
	    confirmBox.find(".title").html(title);
	    confirmBox.find(".subtitle").html(subtitle);
	    confirmBox.find(".message").html(msg);
	    confirmBox.find("#msg_close").unbind().click(function()
	    {
	        confirmBox.hide();
	    });
	    confirmBox.find("#msg_close").click(clsFn);
	    confirmBox.show();
	}
</script>
<?php get_footer(); ?>

	