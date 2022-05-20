<?php
/*
Template Name: Create Product Page
*/
get_header(); ?>
<h1>Add Product form</h1>
<form method="post" action="">
    <p>Title<input type="text" name="post_title"></p>
    <p>Description<textarea name="post_description"></textarea></p>
    <p>Price<input type="text" name="regular_price"></p>
    <p>Product Type
    <select name="_product_type"> 
    <option value="Rare" selected>Rare</option>
    <option value="Frequent">Frequent</option>
    <option value="Unusual">Unusual</option>
    </select>
    </p>
    <div class='image-preview-wrapper'>
            <img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'media_selector_attachment_id' ) ); ?>'>
        </div>
        <p>Product image: 
        <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Select' ); ?>" /></p>
        <input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
        <input type="submit" value="Create Product" name="submit">
        </form>
    <?php

// Image attempt

    wp_enqueue_media();
$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
    ?>
    <script type='text/javascript'>
        jQuery( document ).ready( function( $ ) {
            var file_frame;
            var wp_media_post_id = wp.media.model.settings.post.id;
            var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>;
            jQuery('#upload_image_button').on('click', function( event ){
                event.preventDefault();
                if ( file_frame ) {
                    file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                    file_frame.open();
                    return;
                } else {
                    wp.media.model.settings.post.id = set_to_post_id;
                }
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select a image to upload',
                    button: {
                        text: 'Use this image',
                    },
                    multiple: false
                });
                file_frame.on( 'select', function() {
                    attachment = file_frame.state().get('selection').first().toJSON();
                    $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                    $( '#image_attachment_id' ).val( attachment.id );
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
                    file_frame.open();
            });
            jQuery( 'a.add_media' ).on( 'click', function() {
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        });
    </script>

    
<?php
function create_product(){
    $post_title = $_POST['post_title'];
    $product_price = $_POST['regular_price'];
    $product_type = $_POST['_product_type'];
    $product_description = $_POST['post_description'];
    $product_image = $_POST['media_selector_attachment_id'];
    $post_args = array(
        'post_title' => $post_title,
        'post_type' => 'product',
        'post_status' => 'publish'
    );
    $post_id = wp_insert_post( $post_args );
    if ( ! empty( $post_id ) && function_exists( 'wc_get_product' ) ) {
        $product = wc_get_product( $post_id );
        $product->set_sku( $post_id );
        $product->set_regular_price( $product_price );
        $product->set_category_ids( array( 16, 17 ) );
        $product->set_description( $product_description );
        update_post_meta( $post_id, '_featured', esc_attr( $product_image ) );
        update_post_meta( $post_id, '_product_type', esc_attr( $product_type ) );
        $product->save();
    }
}
if(isset($_POST['submit']))
{
    create_product();
} 
get_footer();