<?php
///----Blog widgets---
//Popular Post 
class Immigro_latest_Post extends WP_Widget
{
	/** constructor */
	function __construct()
	{
		parent::__construct( /* Base ID */'Immigro_Latest_Post', /* Name */esc_html__('Immigro Latest Post','immigro'), array( 'description' => esc_html__('Show the Latest Post', 'immigro' )) );
	}
 

	/** @see WP_Widget::widget */
	function widget($args, $instance)
	{
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo wp_kses_post($before_widget); ?>
		
		<div class="post-widget">
			<?php echo wp_kses_post($before_title.$title.$after_title); ?>
			<div class="post-inner">
			
				<?php $query_string = 'posts_per_page='.$instance['number'];
					if( $instance['cat'] ) $query_string .= '&cat='.$instance['cat'];
					$this->posts($query_string);
				?>
				
			</div>
		</div>

                
		<?php echo wp_kses_post($after_widget);
	}
 
 
	/** @see WP_Widget::update */
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['cat'] = $new_instance['cat'];
		
		return $instance;
	}

	/** @see WP_Widget::form */
	function form($instance)
	{
		$title = ( $instance ) ? esc_attr($instance['title']) : esc_html__('Latest Post', 'immigro');
		$number = ( $instance ) ? esc_attr($instance['number']) : 3;
		$cat = ( $instance ) ? esc_attr($instance['cat']) : '';?>
			
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title: ', 'immigro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('No. of Posts:', 'immigro'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
        </p>
       
    	<p>
            <label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php esc_html_e('Category', 'immigro'); ?></label>
            <?php wp_dropdown_categories( array('show_option_all'=>esc_html__('All Categories', 'immigro'), 'selected'=>$cat, 'class'=>'widefat', 'name'=>$this->get_field_name('categories')) ); ?>
        </p>
            
		<?php 
	}
	
	function posts($query_string)
	{
		
		$query = new WP_Query($query_string);
		if( $query->have_posts() ):?>
        
           	<!-- Title -->
			<?php 
				global $post;
				while( $query->have_posts() ): $query->the_post(); 
				$post_thumbnail_id = get_post_thumbnail_id($post->ID);
				$post_thumbnail_url = wp_get_attachment_url($post_thumbnail_id);
			?>
			
			<div class="post">
				<figure class="post-thumb">
				<a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>">
					<?php the_post_thumbnail(); ?>
				</a>
				</figure>
				<h6><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h6>
				<span class="post-date"><a href="<?php echo get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ); ?>"><?php echo get_the_date(); ?></a></span>
			</div>
			
			
            <?php endwhile; ?>
            
        <?php endif;
		wp_reset_postdata();
    }
}