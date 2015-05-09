<?php
/**
 * Created by IntelliJ IDEA.
 * User: Calclavia
 * Date: 7/15/2014
 * Time: 5:06 PM
 */

/**
 * Wordpress Admin Menu
 */
add_option("maven_viewer_download_file", $GLOBALS['base_url']."/download.php");
add_option("maven_viewer_build_properties", "version.dependency=%1%;");


function mv_settings_api_init()
{
    // Add the section to reading settings so we can add our
    // fields to it
    add_settings_section(
        'mv_setting_section',
        'Maven Viewer Settings',
        'mv_setting_section_callback_function',
        'general'
    );

    // Add the field with the names and function to use for our new
    // settings, put it in our new section
    add_settings_field(
        'mv_setting_download_file',
        'Maven Download Page',
        'mv_setting_download_file',
        'general',
        'mv_setting_section'
    );

    add_settings_field(
        'mv_setting_build_parse',
        'Maven Build Properties Parse',
        'mv_setting_build_parse',
        'general',
        'mv_setting_section'
    );

    // Register our setting so that $_POST handling is done for us and
    // our callback function just has to echo the <input>
    register_setting('general', 'maven_viewer_download_file');
    register_setting('general', 'maven_viewer_build_properties');
}

add_action( 'admin_init', 'mv_settings_api_init' );

/**
 * Call Back Functions
 */
function mv_setting_section_callback_function()
{
    echo '<p>This is the configuration page for the Maven Viewer plugin.</p>';
}

function mv_setting_download_file()
{
    echo '<input name="maven_viewer_download_file" type="text" value="' . get_option( 'maven_viewer_download_file' ) . '" /> (Optional) The url to send a artifact download link to. The file will be passed with an "r" get variable specifiying the URL of the download. Leave this for a direct link.';
}

function mv_setting_build_parse()
{
    echo '<input name="maven_viewer_build_properties" type="text" value="' . get_option( 'maven_viewer_build_properties' ) . '" /> Replaces and interpretes the build.properties file in the following manner.';
}

?>