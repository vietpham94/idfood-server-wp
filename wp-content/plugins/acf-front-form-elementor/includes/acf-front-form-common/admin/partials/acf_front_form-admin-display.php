<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/armediacgcom/portfolio
 * @since      1.0.0
 *
 * @package    Acf_Front_Form
 * @subpackage Acf_Front_Form/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <div id="poststuff">
        
        <div id="post-body" class="metabox-holder columns-2">

            <!-- main content -->
			<div id="post-body-content">

            <p class="description"></p>
            
            <form method="post" id="<?php echo $this->plugin_name;?>_options" name="<?php echo $this->plugin_name;?>_options" action="options.php">

                <?php
                //Grab all options
                $options = get_option( $this->plugin_name );
                //
                $form_head = ( isset($options['form_head']) ) ? $options['form_head'] : 1;
                $enque_uploader = $options['enque_uploader'];
                $enque_js = $options['enque_js'];
                
                ?>
                <!-- This line will add a nonce, option_page, action, and a http_referer field as hidden inputs. -->
                <?php
                    settings_fields( $this->plugin_name );
                    do_settings_sections( $this->plugin_name );
                ?>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scop="row">
                                <label for="<?php echo $this->plugin_name; ?>-form_head"><?php _e('Use <code>acf_form_head()</code> function', $this->plugin_name) ;?></label>
                            </th>
                            <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e( 'Use <code>acf_form_head()</code> function', $this->plugin_name ); ?></span></legend>
                                <label for="<?php echo $this->plugin_name; ?>-form_head">
                                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[form_head]" id="<?php echo $this->plugin_name; ?>-form_head" value="1" <?php checked( $form_head, 1 ); ?> ><?php _e("Yes please", $this->plugin_name); ?></label>
                            </fieldset>
                            <p class="description"><?php _e('This function is used to process, validate and save the submitted form data created by the <code>acf_form()</code> function. It will also enqueue all ACF related scripts and styles for the acf form to render correctly. <a href="https://www.advancedcustomfields.com/resources/acf_form_head/"  target="_blank">Read the documentation</a>', $this->plugin_name); ?></p>
                            <p class="description"><?php _e('Disable this function if you have added <code>acf_form_head()</code> manually or using another plugin', $this->plugin_name); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scop="row">
                                <label for="<?php echo $this->plugin_name; ?>-enque_uploader"><?php _e('Use <code>acf_enqueue_uploader()</code> function', $this->plugin_name) ;?></label>
                            </th>
                            <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e( 'Use <code>acf_enqueue_uploader()</code> function', $this->plugin_name ); ?></span></legend>
                                <label for="<?php echo $this->plugin_name; ?>-enque_uploader">
                                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[enque_uploader]" id="<?php echo $this->plugin_name; ?>-enque_uploader" value="1" <?php checked( $enque_uploader, 1 ); ?> ><?php _e("Yes please", $this->plugin_name); ?></label>
                            </fieldset>
                            <p class="description"><?php _e('This will create a hidden WYSIWYG field and enqueue the required JS templates for the WP media popups', $this->plugin_name); ?></p>
                            <p class="description"><?php _e('Disable this function if you have added <code>acf_enqueue_uploader()</code> manually or using another plugin', $this->plugin_name); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scop="row">
                                <label for="<?php echo $this->plugin_name; ?>-enque_js"><?php _e( 'Place inline JS in the appended HTML', $this->plugin_name ); ?></label>
                            </th>
                            <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e( 'Place inline JS in the appended HTML', $this->plugin_name ); ?></span></legend>
                                <label for="<?php echo $this->plugin_name; ?>-enque_js">
                                    <input type="checkbox" name="<?php echo $this->plugin_name; ?>[enque_js]" id="<?php echo $this->plugin_name; ?>-enque_js" value="1" <?php checked( $enque_js, 1 ); ?> ><?php _e("Yes please", $this->plugin_name); ?>
                                </label>
                            </fieldset>
                            <p class="description"><?php _e('This will allow ACF to initialize the fields within the newly added HTML. <a href="https://www.advancedcustomfields.com/resources/acf_form/#ajax" target="_blank">Read the documentation</a>', $this->plugin_name); ?></p>
                            <p class="description"><?php _e('Disable this function if you have added the inline JS manually or using another plugin', $this->plugin_name); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button(__( 'Save Changes' ), 'primary','submit', TRUE); ?>
            </form>
            </div>
            
            <!-- sidebar -->
			<div id="postbox-container-1" class="postbox-container">

                <div class="meta-box-sortables">

                    <div class="postbox">

                        <h2><span><?php _e('About', $this->plugin_name); ?></span></h2>

                        <div class="inside">
                            <p><?php _e( $this->settings['display'] . ' : make front end forms without coding with Advanced Custom Fields', $this->plugin_name ); ?></p>
                            <p><?php _e('© Copyright ', $this->plugin_name ); echo date('Y'); ?></p></p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables -->

                <div class="meta-box-sortables">

                    <div class="postbox">

                        <h2><span><?php _e('Documentation', $this->plugin_name); ?></span></h2>

                        <div class="inside">
                            <p><?php _e('Get the up to date documentation at <a href="'. $this->doc_url .'" target="_blank">Read the docs</a>', $this->plugin_name ); ?></p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables -->

                <div class="meta-box-sortables">

                    <div class="postbox">

                        <h2><span><?php _e('Note', $this->plugin_name); ?></span></h2>

                        <div class="inside">
                            <p><?php _e(
                                    'This plugin does not change the way ACF works, but just apply what is described on the official ACF website, please <a href="https://www.advancedcustomfields.com/resources/acf_form/" target="_blank">read the documentation about <code>acf_form()</code></a>',
                                    $this->plugin_name ); ?></p>
                        </div>
                        <!-- .inside -->

                    </div>
                    <!-- .postbox -->

                </div>
                <!-- .meta-box-sortables -->

            </div>
            <!-- #postbox-container-1 .postbox-container -->
        </div>

    </div>

</div>