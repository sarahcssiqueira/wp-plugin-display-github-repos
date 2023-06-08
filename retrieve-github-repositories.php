<?php
/**
 * Plugin Name:       Retrieve Github Repositories Block
 * Author:            Sarah Siqueira
 * Author URI:        sarahjobs.com
 * Description:       Gutenberg block to retrieve your public github repositories and show them as a portfolio. Used for millions of developers around the globe, Github does not require a presentation. Easily display your public Github repositories, just set up your github username below.
 * Version:           1.0.0
 * Requires at least: 5.4
 * Requires PHP:      7.0
 * License:           ''
 * License URI:       ''
 * Text Domain:       github portfolio
 */

/**
 * Exit if accessed directly 
 **/
if(! defined('ABSPATH') ) { exit;
}

new RetrieveGithubBlock(); // Initialize

class RetrieveGithubBlock
{

    function __construct()
    {
        add_action('init', array($this, 'block_register'));
        add_action('enqueue_block_editor_assets', array($this, 'block_enqueues'));
    }
    
    function block_register()
    {
        register_block_type(
            __DIR__,
            [
                'attributes'      => [
                    'githubuser' => [
                        'type'    => 'string',
                        ],
                ],
                'render_callback' => array($this, 'render_retrieved_github_repos'),
                ]
        );
    }


    function block_enqueues()
    {
        wp_enqueue_script( 
            'retrieve-github',
            plugin_dir_url(__FILE__). '.build/index.js', 
            array('wp-blocks', 'wp-i18n', 'wp-editor')
        );

        wp_enqueue_style( 
            'retrieve-github',
            plugin_dir_url(__FILE__) . '.src/style/style.css',
            array(),
        );
    }
        
    /**
     * Retrieve and display data from API
     */
    function render_retrieved_github_repos( $attributes, $content )
    {
            
        // Get the username input 
        $username = $attributes['githubuser'];
        
        $response = wp_remote_retrieve_body( wp_remote_get("https://api.github.com/users/" . $username . "/repos") );

        /* Decode the response */
        $repositories = json_decode($response, true);

        ob_start(); ?>
            
            <?php foreach ( $repositories as $repo ): ?>
            
                <div class="">

                    <a href="<?php echo $repo["svn_url"]?>"class=""> <?php echo $repo["name"] ?></a>

                    <p class=""><?php echo $repo["description"] ?><a href="<?php $repo["svn_url"] ?>"class="">Go to repository</a></p>

                    <p class=""><?php $date = strtotime($repo["created_at"])?><?php echo date('Y/M/d', $date);?></p>
                    
                    <p class=""><?php foreach ($repo["topics"] as $topic) {echo $topic;
                                }?></p>

                    <p class=""><?php echo $repo["stargazers_count"]?> stars</p>

                    <p class=""><?php echo $repo["forks_count"]?> forks </p>

                </div>
                
            <?php endforeach; ?> 

        <?php return ob_get_clean();

    }

}

?>