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
    <select name="_product_type"> 
    <option value="Rare" selected>Rare</option>
    <option value="Frequent">Frequent</option>
    <option value="Unusual">Unusual</option>
    </select>
    
    <input type="submit" value="click" name="submit"> <!-- assign a name for the button -->
</form>

<?php
function create_product(){
    $post_title = $_POST['post_title'];
    $product_price = $_POST['regular_price'];
    $product_type = $_POST['_product_type'];
    $product_description = $_POST['post_description'];


   

    $post_args = array(
        'post_title' => $post_title,
        'post_type' => 'product',
        'post_status' => 'publish'
    );

    $post_id = wp_insert_post( $post_args );
   
    // If the post was created okay, let's try update the WooCommerce values.
    if ( ! empty( $post_id ) && function_exists( 'wc_get_product' ) ) {
        $product = wc_get_product( $post_id );
        $product->set_sku( $post_id ); // Generate a SKU with a prefix. (i.e. 'pre-123') 
        $product->set_regular_price( $product_price ); // Be sure to use the correct decimal price
        $product->set_category_ids( array( 16, 17 ) ); // Set multiple category ID's.
        $product->set_description( $product_description ); // Be sure to use the correct decimal price
        update_post_meta( $post_id, '_product_type', esc_attr( $product_type ) );
        $product->save(); // Save/update the WooCommerce order object.
    }
    
}
if(isset($_POST['submit']))
{
    create_product();
   
} 
?>
<?php


get_footer();