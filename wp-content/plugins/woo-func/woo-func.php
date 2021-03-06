<?php
/* ====================================
 * Plugin Name: Woo-Func
 * Description: Plugin for adding third-party codes so as not to get into the original functions.php
 * Author: Roman Chajkovskyj
 * Version: 1.0
 * ==================================== */
function add_scripts_and_styles() {
	wp_enqueue_script('main', get_template_directory_uri() . '/assets/js/woo-func.js', array(), null, 'footer');
}
add_action( 'admin_enqueue_scripts', 'add_scripts_and_styles' );
add_action( 'wp_enqueue_scripts', 'add_scripts_and_styles' );

// Create new tap for custom fields
function art_added_tabs( array $tabs ): array {
	$tabs['special_panel'] = [
		'label'    => 'Custom Fields',
		'target'   => 'special_panel_product_data',
		'priority' => 5, 
	];
	return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'art_added_tabs', 10, 1 );

// Add icon for tab
function art_added_tabs_icon() {
	?>
	<style>
		#woocommerce-coupon-data ul.wc-tabs li.special_panel_options a::before,
		#woocommerce-product-data ul.wc-tabs li.special_panel_options a::before,
		.woocommerce ul.wc-tabs li.special_panel_options a::before {
			font-family: WooCommerce;
			content: "\e03d";
		}
	</style>
	<?php
}
add_action( 'admin_footer', 'art_added_tabs_icon' );

// Create Custom Fileds
function art_woo_add_custom_fields() {
	global $product, $post;
	echo '<div id="special_panel_product_data" class="panel woocommerce_options_panel">';
	?>
	<!-- Field date created -->
	<div class="options_group">
        <p class="form-field custom_field_type">
			<label for="custom_field_type">
				<?php echo 'Created' ?>
			</label>
                <?php echo wp_date( get_option( 'date_format' ), get_post_timestamp() );?> /
                <?php echo wp_date( get_option( 'time_format' ), get_post_timestamp() );?>
		</p>
		</div>
		<div class="options_group">
		<?php
		// Field type select
	woocommerce_wp_select(
		[
			'id'      => '_product_type',
			'label'   => 'Product type',
			'options' => [
				'Default'	=>__('None', 'woocommerce' ),
				'Rare'   => __( 'Rare', 'woocommerce' ),
				'Frequent'   => __( 'Frequent', 'woocommerce' ),
				'Unusual' => __( 'Unusual', 'woocommerce' ),
			],
		]
	);
	?>



<!-- Select product image -->
	<div class="options_group">
		<p class="form-field custom_field_type">
			<form method="post" enctype="multipart/form-data">
				<label>Select Image:</label>
					<input type="file" name ="file-upload" id="image_prew" required />
						<div id="preview_prod_img"></div>
							<p class="form-field custom_field_type">
								<label for="custom_field_type">
									<?php echo 'Save Product:' ?>
								</label>
									<input type="submit" name="submit" class="metabox_submit button-primary button-large" value="Save" >
							</p>
			</form>
<?php

// Upload on submit - Not Working yet
if(isset($_POST['submit'])){ 
    upload_image(); 
	update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
}
function upload_image(){
    $uploadTo = "uploads/"; 
    $allowedImageType = array('jpg','png','jpeg','gif','pdf','doc');
    $imageName = $_FILES['file']['name'];
    $tempPath=$_FILES["file"]["tmp_name"];
    $basename = basename($imageName);
    $originalPath = $uploadTo.$basename; 
    $imageType = pathinfo($originalPath, PATHINFO_EXTENSION); 
	$overrides = array( 'form' => false);
	$attachment_id = media_handle_upload('file-upload', $overrides);
}
?>
<!-- Fields Clear Button -->
	</p>
		</div>
				<div class="options_group">
					<p class="form-field custom_field_type">
						<p class="reply-submit-buttons">
							<button id="clear_button" type="button" class="cancel button">Clear Custom fields</button>
						</p>
					</p>
		</div>

</div>
<?php
	echo '</div>'; 
}
add_action( 'woocommerce_product_data_panels', 'art_woo_add_custom_fields' );

// Saving custom fields
function custom_fields_save( $post_id ) {
	$woocommerce_select = $_POST['_product_type'];
	if ( ! empty( $woocommerce_select ) ) {
		update_post_meta( $post_id, '_product_type', esc_attr( $woocommerce_select ) );
	}
}
add_action( 'woocommerce_process_product_meta', 'custom_fields_save', 10 );

// Add new tab to product list
add_filter( 'manage_edit-product_columns', 'custom_product_column',11);
function custom_product_column($columns)
{
   $columns['options'] = __( 'Product type','woocommerce');
   return $columns;
}
// Add content to new product list tab
add_action( 'manage_product_posts_custom_column' , 'custom_product_list_column_content', 10, 2 );
function custom_product_list_column_content( $column, $product_id )
{
    global $post;
    $product_type = get_post_meta( $product_id, '_product_type', true );
    switch ( $column )
    {
        case 'options' :
            echo $product_type; 
            break;
    }
}








