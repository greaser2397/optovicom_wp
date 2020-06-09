<?php
namespace LiteSpeed ;
defined( 'WPINC' ) || exit ;

$home_url = home_url( '/' ) ;
$parsed = parse_url( $home_url ) ;
$home_url = str_replace( $parsed[ 'scheme' ] . ':', '', $home_url ) ;
$cdn_url = 'https://cdn.' . substr( $home_url, 2 ) ;

$cdn_mapping = Conf::val( Base::O_CDN_MAPPING );
// Special handler: Append one row if somehow the DB default preset value got deleted
if ( ! $cdn_mapping ) {
	$this->load_default_vals();
	$cdn_mapping = self::$_default_options[ Base::O_CDN_MAPPING ];
}

$this->form_action() ;
?>

<h3 class="litespeed-title-short">
	<?php echo __( 'CDN Settings', 'litespeed-cache' ) ; ?>
	<?php $this->learn_more( 'https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:configuration:cdn', false, 'litespeed-learn-more' ) ; ?>
</h3>

<table class="wp-list-table striped litespeed-table"><tbody>

	<tr>
		<th>
			<?php $id = Base::O_CDN_QUIC ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo sprintf( __( 'Enable %s CDN API functionality.', 'litespeed-cache' ), 'QUIC.cloud' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_CDN ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo __( 'Enable Content Delivery Network use.', 'litespeed-cache' ) ; ?>
				<?php echo sprintf( __( 'Leave this setting %1$s if you have QUIC.cloud CDN or Cloudflare API turned %2$s.', 'litespeed-cache' ), '<code>' . __( 'OFF', 'litespeed-cache' ) . '</code>', '<code>' . __( 'ON', 'litespeed-cache' ) . '</code>' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th class="litespeed-padding-left"></th>
		<td>
		<?php foreach ( $cdn_mapping as $v ) : ?>

			<div class="litespeed-block" data-litespeed-cdn-mapping="1">
				<div class='litespeed-cdn-mapping-col1'>
					<label class="litespeed-form-label">
						<?php $id = Base::CDN_MAPPING_URL ; ?>
						<?php $this->title( $id ) ; ?>
						<button type="button" class="button button-link litespeed-collection-button litespeed-danger" data-litespeed-cdn-mapping-del="1" data-action="remove">
							<span class="dashicons dashicons-dismiss"></span>
							<span class="screen-reader-text"><?php echo __( 'Remove CDN url', 'litespeed-cache' ) ; ?></span>
						</button>
					</label>

					<?php
						$this->build_input( Base::O_CDN_MAPPING . "[$id][]", 'litespeed-input-long', $v[ $id ] ) ;
					?>
					<div class="litespeed-desc">
						<?php echo sprintf( __( 'CDN URL to be used. For example, %s', 'litespeed-cache' ), '<code>' . $cdn_url . '</code>' ) ; ?>
					</div>
				</div>

				<div class="litespeed-col-auto litespeed-cdn-mapping-col2">
					<div class="litespeed-row litespeed-toggle-wrapper">
						<div class="litespeed-cdn-mapping-inc litespeed-form-label litespeed-form-label--toggle">
							<?php $id = Base::CDN_MAPPING_INC_IMG ; ?>
							<?php $this->title( $id ) ; ?>
						</div>
					<?php
						$this->build_toggle( Base::O_CDN_MAPPING . "[$id][]", ! empty( $v[ $id ] ) ? true : false ) ;
					?>
					</div>
					<div class="litespeed-row litespeed-toggle-wrapper">
						<div class="litespeed-cdn-mapping-inc litespeed-form-label litespeed-form-label--toggle">
							<?php $id = Base::CDN_MAPPING_INC_CSS ; ?>
							<?php $this->title( $id ) ; ?>
						</div>
					<?php
						$this->build_toggle( Base::O_CDN_MAPPING . "[$id][]", ! empty( $v[ $id ] ) ? true : false ) ;
					?>
					</div>
					<div class="litespeed-row litespeed-toggle-wrapper">
						<div class="litespeed-cdn-mapping-inc litespeed-form-label litespeed-form-label--toggle">
							<?php $id = Base::CDN_MAPPING_INC_JS ; ?>
							<?php $this->title( $id ) ; ?>
						</div>
					<?php
						$this->build_toggle( Base::O_CDN_MAPPING . "[$id][]", ! empty( $v[ $id ] ) ? true : false ) ;
					?>
					</div>
				</div>

				<div class="litespeed-col-auto">
					<label class="litespeed-form-label">
						<?php $id = Base::CDN_MAPPING_FILETYPE ; ?>
						<?php $this->title( $id ) ; ?>
					</label>
					<?php $this->build_textarea( Base::O_CDN_MAPPING . "[$id][]", 18, is_array( $v[ $id ] ) ? implode( "\n", $v[ $id ] ) : $v[ $id ] ) ; ?>
				</div>
			</div>

		<?php endforeach ; ?>

			<p><button type="button" class="button button-link litespeed-form-action litespeed-link-with-icon" id="litespeed-cdn-mapping-add" data-action="add">
				<span class="dashicons dashicons-plus-alt"></span><?php echo __( 'Add new CDN url', 'litespeed-cache' );?>
			</button></p>

			<div class="litespeed-warning">
				<?php echo __('NOTE', 'litespeed-cache'); ?>:
				<?php echo __( 'To randomize CDN hostname, define multiple hostnames for the same resources.', 'litespeed-cache' ) ; ?>
			</div>

			<div class="litespeed-desc">
				<b><?php $this->title( Base::CDN_MAPPING_INC_IMG ) ; ?></b>:
				<?php echo sprintf( __( 'Serve all image files through the CDN. This will affect all attachments, HTML %s tags, and CSS %s attributes.', 'litespeed-cache' ), '<code>&lt;img</code>', '<code>url()</code>' ) ; ?>

				<br />
				<b><?php $this->title( Base::CDN_MAPPING_INC_CSS ) ; ?></b>:
				<?php echo __( 'Serve all CSS files through the CDN. This will affect all enqueued WP CSS files.', 'litespeed-cache' ) ; ?>

				<br />
				<b><?php $this->title( Base::CDN_MAPPING_INC_JS ) ; ?></b>:
				<?php echo __( 'Serve all JavaScript files through the CDN. This will affect all enqueued WP JavaScript files.', 'litespeed-cache' ) ; ?>

				<br />
				<b><?php $this->title( Base::CDN_MAPPING_FILETYPE ) ; ?></b>:
				<?php echo __( 'Static file type links to be replaced by CDN links.', 'litespeed-cache' ) ; ?>
				<?php Doc::one_per_line() ; ?>
				<?php echo sprintf( __( 'This will affect all tags containing attributes: %s %s %s.', 'litespeed-cache' ), '<code>src=""</code>', '<code>data-src=""</code>', '<code>href=""</code>' ) ; ?>
				<?php $this->learn_more( 'https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:configuration:cdn#include_file_types', __( 'Default value', 'litespeed-cache' ) ) ; ?>

				<br />
				<?php echo sprintf( __( 'If you turn any of the above settings OFF, please remove the related file types from the %s box.' ), '<b>' . __( 'Include File Types', 'litespeed-cache' ) . '</b>' ) ; ?>
				<?php $this->learn_more( 'https://www.litespeedtech.com/support/wiki/doku.php/litespeed_wiki:cache:lscwp:configuration:cdn#include_file_types' ) ; ?>
			</div>

		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_CDN_ATTR; ?>
			<?php $this->title( $id ); ?>
		</th>
		<td>

			<div class="litespeed-textarea-recommended">
				<div>
					<?php $this->build_textarea( $id, 40 ); ?>
				</div>
				<div>
					<?php $this->recommended( $id, true ); ?>
				</div>
			</div>

			<div class="litespeed-desc">
				<?php echo __( 'Specify which HTML element attributes will be replaced with CDN Mapping.', 'litespeed-cache' ); ?>
				<?php echo __( 'Only attributes listed here will be replaced.', 'litespeed-cache' ); ?>
				<br /><?php echo sprintf( __( 'Use the format %1$s or %2$s (element is optional).', 'litespeed-cache' ), '<code>element.attribute</code>', '<code>.attribute</code>' ); ?>
				<?php Doc::one_per_line(); ?>
			</div>
		</td>
	</tr>

	<tr>
		<th class="litespeed-padding-left">
			<?php $id = Base::O_CDN_ORI ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_textarea( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo sprintf( __( 'Site URL to be served through the CDN. Beginning with %1$s. For example, %2$s.', 'litespeed-cache' ), '<code>//</code>', '<code>' . $home_url . '</code>' ) ; ?>
				<br /><?php echo sprintf( __( 'Wildcard %1$s supported (match zero or more characters). For example, to match %2$s and %3$s, use %4$s.', 'litespeed-cache' ), '<code>*</code>', '<code>//www.aa.com</code>', '<code>//aa.com</code>', '<code>//*aa.com</code>' ) ; ?>
				<?php Doc::one_per_line() ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th class="litespeed-padding-left">
			<?php $id = Base::O_CDN_ORI_DIR ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<div class="litespeed-textarea-recommended">
				<div>
					<?php $this->build_textarea( $id, 40 ) ; ?>
				</div>
				<div>
					<?php $this->recommended( $id, true ) ; ?>
				</div>
			</div>

			<div class="litespeed-desc">
				<?php echo __( 'Only files within these directories will be pointed to the CDN.', 'litespeed-cache' ) ; ?>
				<?php Doc::one_per_line() ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th class="litespeed-padding-left">
			<?php $id = Base::O_CDN_EXC ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_textarea( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo __( 'Paths containing these strings will not be served from the CDN.', 'litespeed-cache' ) ; ?>
				<?php Doc::one_per_line() ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_CDN_REMOTE_JQ ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id, array( __( 'OFF', 'litespeed-cache' ), 'Google', 'CDNJS' ) ); ?>
			<div class="litespeed-desc">
				<?php echo __( 'Improve page load time by loading jQuery from a remote CDN service instead of locally.', 'litespeed-cache' ) ; ?>
			</div>
		</td>
	</tr>

	<tr>
		<th>
			<?php $id = Base::O_CDN_CLOUDFLARE ; ?>
			<?php $this->title( $id ) ; ?>
		</th>
		<td>
			<?php $this->build_switch( $id ) ; ?>
			<div class="litespeed-desc">
				<?php echo sprintf( __( 'Use %s API functionality.', 'litespeed-cache' ), 'Cloudflare' ) ; ?>
				<?php echo sprintf( __( 'This can be managed from <a %2$s>%1$s</a>.', 'litespeed-cache' ), '<b>' . __( 'CDN', 'litespeed-cache' ) . '</b> -&gt; <b>' . __( 'Manage', 'litespeed-cache' ) . '</b>', 'href="admin.php?page=litespeed-cdn#manage"' ) ; ?>
			</div>
			<div class="litespeed-block">
				<div class='litespeed-col'>
					<label class="litespeed-form-label"><?php echo __( 'Email Address', 'litespeed-cache' ) ; ?></label>

					<?php $this->build_input( Base::O_CDN_CLOUDFLARE_EMAIL ) ; ?>
					<div class="litespeed-desc">
						<?php echo sprintf( __( 'Your Email address on %s.', 'litespeed-cache' ), 'Cloudflare' ) ; ?>
					</div>
				</div>

				<div class='litespeed-col'>
				<label class="litespeed-form-label"><?php echo __( 'Global API Key', 'litespeed-cache' ) ; ?></label>

					<?php $this->build_input( Base::O_CDN_CLOUDFLARE_KEY ) ; ?>
					<div class="litespeed-desc">
						<?php echo sprintf( __( 'Your API key is used to access %s APIs.', 'litespeed-cache' ), 'Cloudflare' ) ; ?>
						<?php echo sprintf( __( 'Get it from <a %1$s>%2$s</a>.', 'litespeed-cache' ), 'href="https://www.cloudflare.com/a/profile" target="_blank"', 'Cloudflare' ) ; ?>
					</div>
				</div>

				<div class='litespeed-col'>
					<label class="litespeed-form-label"><?php echo __( 'Domain', 'litespeed-cache' ) ; ?></label>

				<?php
					$cf_zone = Conf::val( Base::O_CDN_CLOUDFLARE_ZONE ) ;
					$cls = 	$cf_zone ? ' litespeed-input-success' : ' litespeed-input-warning' ;
					$this->build_input( Base::O_CDN_CLOUDFLARE_NAME, $cls ) ;
				?>
					<div class="litespeed-desc">
						<?php echo __( 'You can just type part of the domain.', 'litespeed-cache' ) ; ?>
						<?php echo __( 'Once saved, it will be matched with the current list and completed automatically.', 'litespeed-cache' ) ; ?>
					</div>
				</div>
			</div>
		</td>
	</tr>

</tbody></table>

<?php
$this->form_end() ;
