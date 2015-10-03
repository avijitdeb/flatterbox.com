<?php
/* Template Name: Landing Page */
get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<div class="largebox whatisfb">
			<div class="boximage-holder colmatch">
				<?php 
				if (strlen(get_field('main_image')) > 0) : 
					$mainimage = get_field('main_image');
				else : 
					$mainimage = home_url().'/wp-content/uploads/2015/04/what-is-a-flatterbox-mothers-day.jpg';
				endif; 
				?>
				<div class="boximage" style="background-image: url('<?php echo $mainimage; ?>');"></div><div style="text-align:center;padding-top:10px;clear:both;">Also great for</div>
				<?php if (false) : ?>
				<div class="boxoccasions">
					<div class="posone">
						<div class="occasion  active" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2014/11/img12.jpg');"><div class="occasionname"><span>Birthday</span></div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2014/11/img12.jpg');"><div class="occasionname"><span>Military Gift</span></div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2014/11/img12.jpg');"><div class="occasionname">Birthday 2</div></div>
					</div>
					<div class="postwo">
						<div class="occasion active" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_Mothersday_Background.jpg');"><div class="occasionname">Mothersday</div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_Mothersday_Background.jpg');"><div class="occasionname">Mothersday 1</div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_Mothersday_Background.jpg');"><div class="occasionname">Mothersday 2</div></div>
					</div>
					<div class="posthree">
						<div class="occasion active" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_Just-Because_Background.jpg');"><div class="occasionname">Just Because...</div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_Just-Because_Background.jpg');"><div class="occasionname">Just Because... 1</div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_Just-Because_Background.jpg');"><div class="occasionname">Just Because... 2</div></div>
					</div>
					<div class="posfour">
						<div class="occasion active" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_newborn_Background.jpg');"><div class="occasionname">Newborn</div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_newborn_Background.jpg');"><div class="occasionname">Newborn 1</div></div>
						<div class="occasion" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/01/Flatterbox_newborn_Background.jpg');"><div class="occasionname">Newborn 2</div></div>
					</div>
				</div>
				<?php endif; ?>
				<?php
					if (strlen(get_field('secondary_image')) > 0) :
						echo '<div class="boximage" style="background-image: url(\''.get_field('secondary_image').'\');"></div>';
					else : 
						$args = array(
									'orderby' => 'none',
									'exclude' => 28,
									'hide_empty' => 0
								);
						$occasions = get_terms('flatterbox_type', $args);
						if ( ! empty( $occasions ) && ! is_wp_error( $occasions ) ) {
						    $count = count( $occasions );
						    $i = 1;
						    $y = ceil($count / 4);
						    $scount = "one";
						    $count = 0;
						    $term_list = '<div class="boxoccasions">';
						    foreach ( $occasions as $occ ) {
						    	if ($count == 0 ) { $term_list .= '<div class="pos'.$scount.'">'; }
						        $active = ''; if ($count == 0 ) : $active = ' active'; endif;
						        $n = 7 - $count;
						        $term_list .= '<div class="occasion'.$active.'" style="z-index: '.$n.'; background-image: url(\'' . z_taxonomy_image_url( $occ->term_id ) . '\');"><div class="occasionname"><span>' . $occ->name . '</span></div></div>';
						    	$count++;
						    	//$term_list .= '<a href="' . z_taxonomy_image_url( $occ ) . '" title="' . sprintf( __( 'View all post filed under %s', 'my_localization_domain' ), $occ->name ) . '">' . $occ->name . '</a>';
						    	if ( $count == $y ) {
						    		$i++; $count = 0;
						            $term_list .= '</div>';
						            switch ($i) {
						            	case 1:
						            		$scount = "one";
						            		break;
						            	case 2:
						            		$scount = "two";
						            		break;
						            	case 3:
						            		$scount = "three";
						            		break;
						            	case 4:
						            		$scount = "four";
						            		break;
						            }
						        }
						    }
						    if ($count < $y && $count != 0) { $term_list .="</div>"; }
						    $term_list .="</div>";
						}
						echo $term_list;
					endif;
				?>
			</div>
			<div class="boxinfo colmatch">
				<?php the_content(); ?>
			</div>
		</div>
        <div class="largebox subsection" style="background-color:#fff !important;">
        	<section class="intro-block" style="padding:0 !important;">

		
		<div class="three-columns" style="width:99% !important;">

									<a href="http://www.flatterbox.com/choose-card/" title="Get Started">			<div class="col" style="width:308px !important;">

				<div class="img-holder">

															<img src="http://www.flatterbox.com/wp-content/uploads/2014/10/fb_step1_cards.png" alt="Get Started">

				</div>

				<h2>Step 1</h2>

				<h3 class="h3-link">Get Started</h3>

				<p>Gather everyone to participate in this awesome gift. Pick the occasion and box design and then you're off and running.</p>
			</div>

			</a>									<div class="col" style="width:308px !important;">

				<div class="img-holder">

															<img src="http://www.flatterbox.com/wp-content/uploads/2014/10/fb_step2_write.png" alt="Get It Rolling">

				</div>

				<h2>Step 2</h2>

				<h3>Get It Rolling</h3>

				<p>Write the first sentiment card.  Sit back while your friends and family do the rest. (This includes reminders from us)</p>
			</div>

												<div class="col" style="width:308px !important;">

				<div class="img-holder">

															<img src="http://www.flatterbox.com/wp-content/uploads/2014/10/fb_step3_delivery.png" alt="Wrap it up">

				</div>

				<h2>Step 3</h2>

				<h3>Wrap it up</h3>

				<p>Once the sentiments are collected, just review them and check out.  We'll take it from there.</p>
			</div>

											</div>



			</section>
</div>



		<div class="largebox whatisfb subsection">
			<div class="boxinfo">
				<h2><?php the_field('subsection_title'); ?></h2>
				<?php the_field('subsection_content'); ?>
			</div>
		</div>
        
		<div class="largebox whatisfb subsection colorcards">
			<div class="boxinfo">
				<?php 
				if(have_rows('subsection_colors')) : 
					$boxes = '';
					$count = 0;
					while ( have_rows('subsection_colors') ) : the_row();
					
					$boxes .= '<div class="boxcolor">
						<a href="'.home_url().'/choose-card/?cardcolor='.strtolower(get_sub_field('color_name')).'&occasion='.get_field('occasion')->name.'"><div class="boxy" style="background-color:'.get_sub_field('color_value').';">'.get_sub_field('color_name').'</div></a>
					</div>';
					$count++;
					
					endwhile; 
					?>
					<div class="colors wide-<?php echo $count; ?>">
						<?php echo $boxes; ?>
					</div>
					<?php
				endif; 
				?>
			</div>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php if (strlen(get_field('secondary_image')) == 0) : ?>
<script type="text/javascript">
function cycleImages(count){
      var $active = jQuery('.boxoccasions .pos'+count+' .active');
      var $next = ($active.next().length > 0) ? $active.next() : jQuery('.boxoccasions .pos'+count+' div:first');
      $next.css('z-index',6);//move the next image up the pile
      $active.fadeOut(1500,function(){//fade out the top image
	  $active.css('z-index',1).show().removeClass('active');//reset the z-index and unhide the image
          $next.css('z-index',7).addClass('active');//make the next image the top one
      }); // Fade then ove rest
      $next.next().css('z-index',5);//move the next image up the pile
      $next.next().next().css('z-index',4);//move the next image up the pile
      if ($next.next().next().next().length > 0) { $next.next().next().next().css('z-index',3); }//move the next image up the pile
      if ($next.next().next().next().next().length > 0) { $next.next().next().next().next().css('z-index',2); }//move the next image up the pile
      if ($next.next().next().next().next().next().length > 0) { $next.next().next().next().next().next().css('z-index',2); }//move the next image up the pile

    }
jQuery(document).ready(function($){
// run every 7s
var time = 7000;
//var one = Math.floor((Math.random() * 6) + 1);
//var two = Math.floor((Math.random() * 6) + 1);
//var three = Math.floor((Math.random() * 6) + 1);
//var four = Math.floor((Math.random() * 6) + 1);
setInterval('cycleImages("one")', time);
setInterval('cycleImages("two")', time);
setInterval('cycleImages("three")', time);
setInterval('cycleImages("four")', time);
})
</script>
<?php endif; ?>

<script type="text/javascript" src="http://www.flatterbox.com/wp-content/themes/flatterbox/js/jquery.matchHeight.js" /></script>
<script>
jQuery('.colmatch').matchHeight();
</script>
<?php get_footer(); ?>		