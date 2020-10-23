<?php
?>


<div class="wrap">
    <h1><?php _e('ٔNewsletter', 'dwnlite'); ?></h1>

    <div id="poststuff" style="display: flex; flex-flow: row wrap;">
        <div id="post-body" class="metabox-holder" style="flex: 1; min-width: 0;">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <?php
                        \DW_NLITE\Admin\Admin::$newsletter_object->prepare_items();
                        \DW_NLITE\Admin\Admin::$newsletter_object->display(); ?>
                    </form>
                </div>
            </div>
        </div>

        <div class="export-wrap" style="margin-right: 20px; min-width: 200px; padding: 18px 0;">
            <h2><?php _e('Export Data', 'dwnlite'); ?></h2>
            
            <form method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <select name="fields" style="display: block; width: 100%; margin-bottom: 10px;">
                    <option value="email"><?php _e('Only emails', 'dwnlite'); ?></option>
                    <option value="phone_number"><?php _e('Only phone numbers', 'dwnlite'); ?></option>
                    <option value="all"><?php _e('All data', 'dwnlite'); ?></option>
                </select>

                <input type="hidden" name="action" value="dw_nlite_export_data">
                <?php wp_nonce_field('dwnlite_export_data', 'ex_nonce'); ?>
                <button style="display: block; width: 100%" type="submit" class="button primary"><?php _e('Download CSV', 'dwnlite'); ?></button>
            </form>
        </div><!-- .export-wrap -->
        <br class="clear">
    </div>
</div>
