<?php

if (isset($FSREPconfig['HideNews']) && $FSREPconfig['HideNews'] == 'Yes') {
	// Do Nothing
} else {
	echo '<td valign="top" style="width: 250px; padding-left: 10px;">';
	echo '<table class="widefat page fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" class="manage-column" width="50">'.fsrep_text_translator('FireStorm Real Estate Plugin', 'FireStorm News Label', 'FireStorm News').'</th>
	</thead>
	<tbody>';
	echo '<tr><td>';
	include_once(ABSPATH.WPINC.'/feed.php');
	$rss = fetch_feed('http://www.firestormplugins.com/category/real-estate-plugin/feed/');
	if ( ! is_wp_error( $rss ) ) : // Checks that the object is created correctly
	$maxitems = $rss->get_item_quantity( 5 ); 
	$rss_items = $rss->get_items( 0, $maxitems );
	endif;
	?>
	<ul>
	<?php if ( $maxitems == 0 ) : ?>
	<li><?php _e( 'No items', 'my-text-domain' ); ?></li>
	<?php else : ?>
	<?php // Loop through each feed item and display each item as a hyperlink. ?>
	<?php foreach ( $rss_items as $item ) : ?>
	<li>
	<a href="<?php echo esc_url( $item->get_permalink() ); ?>"
	title="<?php printf( __( 'Posted %s', 'my-text-domain' ), $item->get_date('j F Y | g:i a') ); ?>" target="_blank">
	<?php echo esc_html( $item->get_title() ); ?>
	</a><br /><em><?php echo $item->get_date('j F Y'); ?></em>
	</li>
	<?php endforeach; ?>
	<?php endif; ?>
	</ul>		
	<?php
	echo '</td>';
	echo '</tr>';
	echo '</tbody></table><br />';
	?>
	<a class="twitter-timeline" href="https://twitter.com/fsplugins" data-widget-id="397913788432658432">Tweets by @fsplugins</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="fb-like-box" style="margin-top: 13px;" data-href="https://www.facebook.com/pages/Firestorm-Plugins/219724038190945" data-width="250" data-height="300" data-colorscheme="light" data-show-faces="true" data-header="true" data-stream="false" data-show-border="true"></div>
	<?php 
	echo '</td>';
}
?>