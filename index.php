<?php
/**
 * Plugin Name:       Retrieve Github Repos Block
 * Author:            Sarah Siqueira
 * Author URI:        sarahjobs.com
 * Description:       Gutenberg block to retrieve your public github repositories and show them as a portfolio
 * Version:           0.1.0
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
           /* 'render_callback' => function( $attributes, $content ) {
                $github_user = esc_html( $attributes['githubuser'] );
                return "$github_user";
            },*/

        'render_callback' => 'render_retrieved_github_repos',
        ]
    );
}

add_action( 'init', 'retrieve_github_block' );


// require('.env');

//Render dynamic block //
function render_retrieved_github_repos( $attributes, $content ) {

    //$github_user = esc_html( $attributes['githubuser'] );
    // $api_key = getenv('GITHUBTOKEN');

    $headers = [
        "User-Agent: Github Gutenberg Block",
        //"Authorization: token $api_key"
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

    // Decode the JSON data into a PHP array.
    $repositories = json_decode( $response, true );

    ob_start();
    
?>

<h3><?php esc_html_e('Code Samples') ?></h3>
    <p><?php esc_html_e('') ?></p>

    <?php foreach ( $repositories as $repo ): ?>
        <p><?php echo $repo["name"]; ?></p>
        <?php $github_user = esc_html( $attributes['githubuser'] );
                //echo "$github_user";?>
        <article class="article">
            <a href="<?php echo $repo["svn_url"]?>"
                class="fc-accent fw-bold text-center">
                <?php echo $repo["name"] ?>
            </a>
            <p class='fc-medium fw-light m-tb-x05 text-center'>
                <?php echo $repo["description"] ?>
                <a href="<?php $repo["svn_url"] ?>"
                   class='fc-accent-bright fs-medium center'> 
                   icon
                </a>
            </p>
                <ul>
                  <li class='p-x05 fs-smaller fc-medium text-center'>
                    <?php $date = strtotime($repo["created_at"])?>
                    <?php echo date('Y/M/d', $date);?>
                  </li>
                </ul>
                <ul>
                    <li class='p-x05 fs-smaller fc-medium text-center'>
                        <?php foreach ($repo["topics"] as $topic) {
                                    echo $topic;}?>       
                    </li>

                </ul>
                <section class='section-flex-row-center'>
                  <p class='p-x05 fs-smaller fc-medium text-center'>
                  <?php echo $repo["stargazers_count"]?>
                  </p>
                  <p class='p-x05 fs-smaller fc-medium text-center'>
                  <?php echo $repo["forks_count"]?>
                  </p>
                </section>
            </article>

         <?php endforeach; ?> 
<?php

return ob_get_clean();
}
        
// Enqueues //
function retrieve_github_enqueues() {
    wp_enqueue_script( 
        'retrieve-github' ,
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