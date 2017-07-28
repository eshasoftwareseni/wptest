</div><!-- /.row -->

</div><!-- /.container -->

<footer class="blog-footer">
    <?php if ( is_active_sidebar( 'footer-copyright-text' ) ) { dynamic_sidebar( 'footer-copyright-text' ); } ?>
    <div class="footer-copyright-text">
    	<?php echo of_get_option( 'example_editor', 'no entry'); ?>
    </div>
</footer>

<div class="blog-masthead">
    <div class="container">
        <?php wp_nav_menu( array( 'theme_location' => 'footer-menu', 'menu_class' => 'blog-nav list-inline'  ) ); ?>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
