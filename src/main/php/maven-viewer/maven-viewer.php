<?php
/**
 * Plugin Name: Maven Viewer
 * Plugin URI: http://calclavia.com
 * Description: A viewer to generate downloads for Artifacts stored in maven repositories.
 * Version: 1.0.0
 * Author: Calclavia
 * Author URI: http://calclavia.com
 * License: LGPL3
 */

/**
 * Classes
 */
include("file-helper.php");
include("maven.php");
include("version.php");
include("download-builder.php");

include("admin-panel.php");

/**
 * WordPress Shortcode
 * Usage: [maven url='http://calclavia.com/maven' repo="var/www/maven" group='com.calclavia.universalelectricity' id="universal-electricity"]
 */
function maven_filter($atts)
{
    $maven = new Maven($atts['url'], $atts['local'], $atts['group'], $atts['id']);
    $maven->cache();
    $builder = new DownloadBuilder($maven->getBuilds());

    if ($maven->error)
    {
        return "Maven Viewer Error: " . $maven->error;
    }

    $content = $builder->build();

    //Add extra scripts
    $content .= "
    <script>
    $(document).ready(function(){
        $('.maven-build-table').DataTable({
             lengthChange: false
        });

        $('.build-popup').popover({html : true});

        $('.build-popup').click(function(e) {
            e.preventDefault();
        });
    });
    </script>
    ";

    return $content;
}

add_shortcode('maven', 'maven_filter');

function addScripts()
{
    wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
    wp_enqueue_style('datatable', '//cdn.datatables.net/1.10.3/css/jquery.dataTables.min.css');
    wp_enqueue_script('datatable', '//cdn.datatables.net/1.10.3/js/jquery.dataTables.min.js');
}

add_action('wp_enqueue_scripts', 'addScripts');

?>