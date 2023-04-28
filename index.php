<?php
/**
 * Plugin Name:       Retrieve Github Repositories Block
 * Author:            Sarah Siqueira
 * Author URI:        sarahjobs.com
 * Description:       Gutenberg block to retrieve your public github repositories and show them as a portfolio. Used for millions of developers around the globe, Github does not require a presentation. Easily display your public Github repositories, just set up your github username below.
 * Version:           0.2.0
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * License:           ''
 * License URI:       ''
 * Text Domain:       github portfolio
 * 
 */

/** Prevent public user to directly access your .php files through URL **/
if( ! defined( 'ABSPATH' ) ) exit;

class RetrieveGithubBlock {

    function __construct() {
      add_action('init', array($this, 'startBlock'));
    }
  
    function startBlock() {

        wp_register_script( 'retrieve-github-script' ,
            plugin_dir_url(__FILE__). '.build/index.js', 
            array('wp-blocks', 'wp-i18n', 'wp-editor')
        );

        wp_register_style( 'retrieve-github-style',
            plugin_dir_url(__FILE__) . '.src/style/style.css',
            array(),
        );
      
        register_block_type(__DIR__,
            [
                'attributes'      => [
                    'githubuser' => [
                        'type'    => 'string',
                        ],
                ],
                'render_callback' => array($this, 'render_retrieved_github_repos'),
                //'render_callback' => 'render_retrieved_github_repos',
                'editor_script' => 'retrieve-github-script',
                'editor_style' => 'retrieve-github-style'
            ]
        );
      
    }
    
    /**
	 * Retrieve and display data from API
	 *
	 */
    function render_retrieved_github_repos( $attributes, $content ) {
        
        $headers = [
            "User-Agent: Github Gutenberg Block",
            ];

        $username = $attributes['githubuser'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true
            ]
        );
        
        curl_setopt($curl, CURLOPT_URL, ("https://api.github.com/users/" . $username . "/repos"));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);
    
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response;
        }

        $repositories = json_decode( $response, true );

        ob_start(); ?>

        
        <?php foreach ( $repositories as $repo ): ?>
        
            <article class="">
                
                <a 
                    href="<?php echo $repo["svn_url"]?>"
                    class="">
                    <?php echo $repo["name"] ?>
                </a>

                <p class="">
                    <?php echo $repo["description"] ?>
                    <a href="<?php $repo["svn_url"] ?>"
                    class=""> 
                    Go to repository
                    </a>
                </p>
                <p class="">
                        <?php $date = strtotime($repo["created_at"])?>
                        <?php echo date('Y/M/d', $date);?>
                </p>
                
                <p class="">
                    <?php foreach ($repo["topics"] as $topic) {
                    echo $topic;}
                    ?>       
                </p>
                <p class="">
                    <?php echo $repo["stargazers_count"]?> stars
                </p>

                <p class="">
                    <?php echo $repo["forks_count"]?> forks
                </p>

            </article>
            
        <?php endforeach; ?>

    <?php return ob_get_clean();

    }

}

$retrieveGithubBlock = new RetrieveGithubBlock();

?>