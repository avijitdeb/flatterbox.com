<?php 

/* Template Name: FB Step 4 Invite */

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
<div class="lightbox confirm">
	<?php include('includes/lightbox-confirm.php'); ?>
</div>
<main id="main" role="main">
	<div class="container">
		<div class="heading steps">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/choose-card/?preservesession=1">Create It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/">Personalize It</a></li>
			<li class="active">Invite Flatterers</li>
			<li>Title Card</li>
			<li class="clearpadding">Write the First One!</li>
			<li>Complete</li>
		</ul>
		<div class="box">
			<div class="box-padding">
			
				<h2>Invite Flatterers to Contribute</h2>
				<form action="<?php echo do_shortcode('[site_url]'); ?>/confirmation" method="post" id="inviteform">
				<div class="invite-actions">
					<p>Flatterers who have not responded to their email invitation will automatically receive a reminder both <?php echo intval(get_field('first_reminder_days', 'option')); ?> days and <?php echo intval(get_field('second_reminder_days', 'option')); ?> days prior to the sentiment due date.</p>
					<!-- Any link with a class="cs_import" will start the import process -->
					<a class="cs_import">Add from Address Book</a>
					<!--<a class="btn email-preview" href="<?php echo do_shortcode('[site_url]'); ?>/preview-email/?PID=<?php echo $_SESSION["new_flatterbox_id"] ?>">Preview E-mail</a>-->
				</div>
				<div class="invite-actions">
					<?php if (strlen(get_field('unique_url', $_SESSION["new_flatterbox_id"])) > 0 ) : ?>						
						<?php if ( check_for_mobile() ) : // Is Mobile ?>
							<p class="pre_padding">Share your Flatterbox with Flatterers using your unique link : </p>
							<div><pre><?php echo home_url().'/?fb='.get_field('unique_url', $_SESSION["new_flatterbox_id"]); ?></pre></div>
						<?php else : ?>
							<p>Share your Flatterbox with Flatterers using your unique link : 
								<input type="text" readonly='readonly' name="unique-url" id="unique-url" value="<?php echo home_url().'/?fb='.get_field('unique_url', $_SESSION["new_flatterbox_id"]); ?>">
							</p>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<!-- This textarea will be populated with the contacts returned by CloudSponge -->
				<input type="hidden" id="contact_list" />
				<div style="clear:both;"></div>
				<br/><br/>
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
					<p>Add Flatterers, separated by a comma 'John Doe jdoe@emailaddress.com, Jane Doe jane_doe@otheremail.com'.</p>
					<textarea rows="20" cols="" name="flatterers" id="flatterers"><?php echo $flatterers; ?></textarea>
				
					<input type="hidden" id="redirectback" name="redirectback" value value="0" />
					<input type="submit" id="submitinvites" value="submit" style="display:none;" />
				</div>
				<div id="flatterer-preview">
					<h3>Your Flatterbox Invite – Preview</h3>
					<?php 
						//include ("preview-email.php"); // Changed to the Functions
						echo get_preview_email($_SESSION["new_flatterbox_id"]);
					?>
				</div>
				<?php if (false) : ?>
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
					
					<input type="hidden" id="redirectback" name="redirectback" value value="0" />
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
				
				
				<script>
					jQuery( document ).ready(function($) {
						
						 jQuery("#flatterers_tag").blur( function() {
							sessionSet("inviteform");
					   })
						
					});
				</script>
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
						emails = textarea.value.split(emailSep);
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
						entry = name + "<" + email +">";
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
				<?php if (false) : ?>
					<br/>
					<a href="javascript:;" onclick="addFlattererRow();" class="btn">Add A Flatterer</a>
				<?php endif; ?>
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
			<?php if (false) : // This is the old link with check ?><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/" class="btn back" onclick="return confirm('Stop, you\'re just about to navigate away from this page without saving, and will lose recently added email addresses. Please confirm flatterers before proceeding. Would you like to continue back to Personalize It?');">Back to Personalize It</a> <?php endif; ?>
			<a href="javascript:;" class="btn back" style="padding-left:10px;" onclick="gotoStep3();">Back to Personalize It</a>
			<a href="javascript:;" class="btn save" onclick="jQuery('#submitinvites').trigger('click');">Confirm Invitations</a>
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

	function gotoStep3() {
			jQuery('#redirectback').val('1'); 
			jQuery('#submitinvites').trigger('click');
	}

	jQuery(document).ready(function($) {

		if (window.history && window.history.pushState) {

			$(window).on('popstate', function() {
				var hashLocation = location.hash;
				var hashSplit = hashLocation.split("#!/");
				var hashName = hashSplit[1];

				if (hashName !== '') {
					var hash = window.location.hash;
					if (hash === '') {
						// Call Back...
						doConfirm("Clicking the Browser Back Button may cause you to lose information. Please use the Back button link at the bottom of the page instead. Are you sure you want to leave this page?", function yes()
						{
							gotoStep3();
						}, function no() {
						    // do nothing
						});
					}
				}
			});

			window.history.pushState('forward', null, './#forward');
		}

	});
	
	function doConfirm(msg, yesFn, noFn)
	{
	  	jQuery( ".lightbox.confirm" ).fadeToggle("fast");
	    var confirmBox = jQuery("#confirmBox");
	    confirmBox.find(".message").text(msg);
	    confirmBox.find(".yes,.no").unbind().click(function()
	    {
	        confirmBox.hide();
	    });
	    confirmBox.find(".yes").click(yesFn);
	    confirmBox.find(".no").click(noFn);
	    confirmBox.show();
	}
</script>
<?php get_footer(); ?>

	