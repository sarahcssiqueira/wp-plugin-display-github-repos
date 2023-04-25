<?php
/**
 * Plugin Name:       Retrieve Github Repos Block
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

// Register //
function retrieve_github_block() {
    register_block_type(
        __DIR__ ,
        [
            'attributes'      => [
              'githubuser' => [
                'type'    => 'string',
              ],
            ],

        'render_callback' => 'render_retrieved_github_repos',
        ]
    );
}

add_action( 'init', 'retrieve_github_block' );


//Render dynamic block //
function render_retrieved_github_repos( $attributes, $content ) {

    $headers = [
        "User-Agent: Github Gutenberg Block",
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true
    ]);
    
    $username = $attributes['githubuser'];

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

    ob_start();
    
?>
    <h3> <?php esc_html_e('Code Samples') ?> </h3>

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

                <ul>
                    <li class="">
                        <?php $date = strtotime($repo["created_at"])?>
                        <?php echo date('Y/M/d', $date);?>
                    </li>
                </ul>
                <ul>
                    <li class="">
                        <?php foreach ($repo["topics"] as $topic) {
                        echo $topic;}
                        ?>       
                    </li>

                </ul>

                <section class="">
                    <p class="">
                        <?php echo $repo["stargazers_count"]?> stars
                    </p>

                    <p class="">
                        <?php echo $repo["forks_count"]?> forks
                    </p>

                </section>

            </article>

            <?php endforeach; ?> 
    <?php

    return ob_get_clean();
}
       

// Enqueues //
function retrieve_github_enqueues() {

    wp_enqueue_script( 'retrieve-github' ,
        plugin_dir_url(__FILE__). '.build/index.js', 
        array('wp-blocks', 'wp-i18n', 'wp-editor')
    );

    wp_enqueue_style(
        'retrieve-github',
        plugin_dir_url(__FILE__) . '.src/style/style.css',
        array(),
    );
}

add_action( 'enqueue_block_editor_assets', 'retrieve_github_enqueues' );
?>