<?php
/*
Template Name: Create Product Page
*/
require_once('wp-admin/includes/image.php' );
require_once('wp-admin/includes/file.php' );
require_once('wp-admin/includes/media.php' );
get_header(); ?>
<h1>Add Product form</h1>
    <form method="post" action="" id="create_product_form" enctype="multipart/form-data">
        <p>Title:
            <p><input type="text" name="post_title"></p>
        </p>
        <p>Description<textarea name="post_description"></textarea></p>
            <p>Price:
                <p><input type="text" name="regular_price"></p>
            </p>
        <p>Product Type:
            <p>
                <select name="_product_type"> 
                    <option value="Rare" selected>Rare</option>
                    <option value="Frequent">Frequent</option>
                    <option value="Unusual">Unusual</option>
                </select>
            </p>
        </p>
        <p>
            <label>Select Image:</label>
                <p>
                    <input type="file" name ="file-upload" class="wp-block-button" id="image_prew" required />
                    <div id="preview_prod_img"></div>
                </p>
        </p>
            <p>
                <input type="submit" id="submityes" name="submit_create_front_prod" value="Create Product">
            </p>
    </form>
    
<?php
function create_product(){
    $post_title = $_POST['post_title'];
    $product_price = $_POST['regular_price'];
    $product_type = $_POST['_product_type'];
    $product_description = $_POST['post_description'];
    $overrides = array( 'form' => false);
    $attachment_id = media_handle_upload('file-upload', $overrides);
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
        update_post_meta( $post_id, '_product_type', esc_attr( $product_type ) );
        update_post_meta( $post_id, '_thumbnail_id', $attachment_id );
        $product->save();
    }
}
if(isset($_POST['submit_create_front_prod']))
{
    create_product();
}
get_footer();