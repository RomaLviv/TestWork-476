<?php
/*
Template Name: Woo page
*/
get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			while ( have_posts() ) :
				the_post();
				do_action( 'storefront_page_before' );
				get_template_part( 'content', 'page' );
				?>
			<?php
						$params = array('posts_per_page' => 10, 'post_type' => 'product');
						$wc_query = new WP_Query($params);
						?>
				<div class="product_list">
					<div class="product_list_view">
						<ul>
     						<?php if ($wc_query->have_posts()) : ?>
     						<?php while ($wc_query->have_posts()) :
                				$wc_query->the_post(); ?>
     					<li>
          					<h3>
               					<a href="<?php the_permalink(); ?>">
               						<?php the_title(); ?>
               					</a>
          					</h3>
          							<?php the_post_thumbnail(); ?>
          							<?php the_excerpt(); ?>
						  		<p>Price: <?php echo $product->get_price_html(); ?></p>
						  		<p>Product Type: <?= get_post_meta( $post->ID, '_product_type', true); ?></p>
						  			<?php if($product->has_child()) : ?>
										<a href="<?php the_permalink(); ?>">
											<?php _e('See the available warranty', 'storefront'); ?>
										</a>
											<?php elseif($product->is_in_stock()) : ?>
												<form class="cart" action="<?php the_permalink() ?>" method="post" enctype="multipart/form-data">
     												<div class="quantity">
     												</div>
     												<input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->id); ?>">
     												<button type="submit"><?php echo $product->single_add_to_cart_text(); ?></button>
												</form>
							<?php endif; ?>
     					</li>
     						<?php endwhile; ?>
     							<?php wp_reset_postdata(); ?>
     						<?php else:  ?>
     					<li>
          					<?php _e( 'No Products' ); ?>
     					</li>
     						<?php endif; ?>
						</ul>
					</div>
				</div>
				<?php
				do_action( 'storefront_page_after' );
			endwhile; 
			?>
		</main>
	</div>
<?php
get_footer();
