<?php
/**
 * Useful functions
 *
 */
/**
 * Var_dump pre-ed!
 * For debugging purposes
 *
 * @param mixed $val desired variable to var_dump
 * @uses var_dump
 *
 * @return string
*/
if( !function_exists('dumpit') ) {
    function dumpit( $val ) {
        echo '<pre style="direction:ltr;text-align:left;">';
        var_dump( $val );
        echo '</pre>';
    }
}

if (! function_exists('dwnlite_subscribe_user')) {
    function dwnlite_subscribe_user($name = '', $email = '', $phone_number = '', $update = false) {
        $subscriber = new \DW_NLITE\Subscriber();

        if (! $name && ! $email && ! $phone_number) {
            return false;
        }

        $maybe_subscriber_exists = ($email && $subscriber->find_by_email($email)->exists()) || ($phone_number && $subscriber->find_by_phone_number($phone_number)->exists());

        if (! $update && $maybe_subscriber_exists) {
            return false;
        }

        // Set data and save
        $subscriber->set_name($name)->set_email($email)->set_phone_number($phone_number)->save();

        return true;
    }
}

if (! function_exists('dwnlite_remove_subscribed_user')) {
    function dwnlite_remove_subscribed_user($id = 0) {
        $subscriber = new \DW_NLITE\Subscriber($id);
        return $subscriber->remove();
    }
}

if (! function_exists('dwnlite_get_subscribed_users')) {
    function dwnlite_get_subscribed_users($fields = 'all', $limit = -1, $offset = -1) {
        global $wpdb;

        if ($fields === 'all' || ! is_array($fields)) {
            $fields = 'email,phone_number,name';

        } elseif (array_intersect(['name', 'email', 'phone_number'], $fields)) {
            $fields = implode(',', $fields);
        }

        $sql = "SELECT $fields FROM {$wpdb->prefix}dw_nlite";
        
        if ($limit > -1) {
            $sql .= $wpdb->prepare(" LIMIT %d", $limit);
        }
        
        if ($offset > -1) {
            $sql .= $wpdb->prepare(" OFFSET %d", $offset);
        }

        return $wpdb->get_results( $sql, 'ARRAY_A' );
    }
}
