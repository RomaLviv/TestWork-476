<h1>Add Product form</h1>

<form method="post" action="">
    <p>Title<input type="text" name="post_title"></p>
    <p>Price<input type="text" name="regular_price"></p>
    
    <input type="submit" value="click" name="submit"> <!-- assign a name for the button -->
</form>

<?php
function display(){
    $post_title = $_POST['post_title'];
    $post_price = $_POST['regular_price'];
    // echo "hi ".$_POST["studentname"];

    $post_args = array(
        'post_title' => $post_title, // The product's Title
        'post_type' => 'product',
        'post_status' => 'publish' // This could also be $data['status'];
    );

    $post_id = wp_insert_post( $post_args );
   
    // If the post was created okay, let's try update the WooCommerce values.
    if ( ! empty( $post_id ) && function_exists( 'wc_get_product' ) ) {
        $product = wc_get_product( $post_id );
        $product->set_sku( $post_id ); // Generate a SKU with a prefix. (i.e. 'pre-123') 
        $product->set_regular_price( $post_price ); // Be sure to use the correct decimal price
        $product->set_category_ids( array( 16, 17 ) ); // Set multiple category ID's.
        $product->save(); // Save/update the WooCommerce order object.
    }
    
}
if(isset($_POST['submit']))
{
   display();
   
} 
?>