	</div>
	<footer id="footer">
		<div class="extended">
			<div class="holder">

			</div>
		</div>
		<div class="holder" id="mc_embed_signup">

		</div>
	</footer>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.main.js"></script>


	
	
	<?php wp_footer(); ?>

	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.matchHeight.js"></script>
	<script>
	jQuery(function() {
    	jQuery('#footer .extended .holder .col').matchHeight();
    	<?php if (is_page_template('page-giftbox-selection.php')) : ?>jQuery('.boxheight').matchHeight();<?php endif; ?>
	});
	</script>
	




	<?php if ( !is_user_logged_in() ) { ?>
	<script>
		jQuery(document).ready(function(){
			jQuery( ".loginout a" ).click(function(event) {
				event.preventDefault();
			  	jQuery( ".lightbox.login" ).fadeToggle("fast");
			});
		});
		</script>
	<?php } ?>

	<?php if (is_front_page()) : ?>
	<script>
		jQuery(document).ready(function(){
  			jQuery('.slickshow').slick({
	    		autoplay: true,
  				autoplaySpeed: 2000,
	    		arrows: false,
	    		prevArrow: '<a class="btn-prev slick-prev" href="#">Previous</a>',
	    		nextArrow: '<a class="btn-next slick-next" href="#">Next</a>',
	    		slidesToShow: 7
  			});
			
		
			
		});
	</script>
	<?php endif; ?>
	<script>
	
		<?php if (is_page_template('page-step2-choose-card-box_new.php')) { ?>
			if(jQuery(".error_wid_login")[0]) 
			{
				
				//jQuery("#redirect-step2").val('<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/?boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage +'');
				
				jQuery("#redirect-step2").val('/choose-card/');			
				jQuery( ".lightbox-step2.login-step2" ).fadeToggle("fast");
				jQuery( ".login_wid" ).prepend("<span style='color:red;'>Invalid login.</span><br/><br/>");
					
			}			
		<?php } ?>	
			
		jQuery(document).ready(function(){	

		
			if(jQuery(".error_wid_login")[0]) 
			{
				event.preventDefault();

				jQuery( ".lightbox.login" ).fadeToggle("fast");
					
			}	
			
			
		});
	</script>

	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-60980861-1', 'auto');
		ga('send', 'pageview');

	</script>

	<div style="display:none;">

	<!-- Google Code for Remarketing Tag -->

	<script type="text/javascript">
		/* <![CDATA[ */
			var google_conversion_id = 954166237;
			var google_custom_params = window.google_tag_params;
			var google_remarketing_only = true;
		/* ]]> */
	</script>
	<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>
	<noscript>
		<div style="display:inline;">
			<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/954166237/?value=0&amp;guid=ON&amp;script=0"/>
		</div>
	</noscript>
	</div>

<!-- begin olark code -->
<script data-cfasync="false" type='text/javascript'>/*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
f[z]=function(){
(a.s=a.s||[]).push(arguments)};var a=f[z]._={
},q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
0:+new Date};a.P=function(u){
a.p[u]=new Date-a.p[0]};function s(){
a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
b.contentWindow[g].open()}catch(w){
c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
/* custom configuration goes here (www.olark.com/documentation) */
olark.identify('6510-593-10-3890');/*]]>*/</script><noscript><a href="https://www.olark.com/site/6510-593-10-3890/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a></noscript>
<!-- end olark code -->


</body>
</html> 
