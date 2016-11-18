
<h2><?php esc_html_e( $page_title ); ?></h2>
<p>Drag and Drop the post types to order the search results.</p>
<ul class="cos-sortable" id="cos-sortable">

	<li class="ui-state-default" data-name="post">Post</li>
	<li class="ui-state-default" data-name="page">Page</li>
	<?php foreach ( $post_types as $post_type ) : ?>
		<li class="ui-state-default"
		    data-name="<?php esc_attr_e( $post_type->name ) ?>"><?php esc_html_e( $post_type->labels->name ) ?></li>
	<?php endforeach; ?>
</ul>

<form action="options.php" id="cos-form" method="POST">
	<?php settings_fields( $section ); ?>
	<?php do_settings_sections( $page_slug ); ?>
	<?php submit_button(); ?>
</form>





