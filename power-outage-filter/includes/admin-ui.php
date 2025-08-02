<?php
// Add custom metabox with toggle
add_action('add_meta_boxes', function () {
    add_meta_box('poa_toggle_meta', 'وضعیت قطعی برق', 'poa_toggle_meta_callback', 'product', 'side');
});



function poa_toggle_meta_callback($post) {
    $has_term = has_term('yes', 'power_outage_availability', $post->ID);
    ?>
    <style>
        .poa-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .poa-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .poa-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .poa-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .poa-slider {
            background-color: #0c1555;
        }

        input:checked + .poa-slider:before {
            transform: translateX(26px);
        }
    </style>

    <label class="poa-switch">
        <input type="checkbox" name="poa_toggle" id="poa_toggle" value="1" <?php checked($has_term); ?>>
        <span class="poa-slider"></span>
    </label>
    <p>فعال‌سازی امکان سفارش این محصول در زمان قطعی برق</p>
    <?php
}


add_action('save_post_product', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['poa_toggle'])) {
        wp_set_object_terms($post_id, 'yes', 'power_outage_availability');
    } else {
        wp_remove_object_terms($post_id, 'yes', 'power_outage_availability');
    }
});



// Add checkbox to quick edit
add_action('quick_edit_custom_box', 'poa_quick_edit_custom_box', 10, 2);
function poa_quick_edit_custom_box($column, $post_type) {
    if ($column !== 'name') return;
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label class="alignleft">
                <input type="checkbox" name="poa_toggle" class="poa-toggle-quick-edit" value="1" />
                <span class="checkbox-title">فعال‌سازی در حالت قطعی برق</span>
            </label>
        </div>
    </fieldset>
    <?php
}


add_action('admin_footer', 'poa_quick_edit_script');
function poa_quick_edit_script() {
    global $post_type;
    if ($post_type !== 'product') return;
    ?>
    <script>
        jQuery(document).ready(function ($) {
            function set_quick_edit_value(post_id, checked) {
                let row = $('#edit-' + post_id);
                row.find('.poa-toggle-quick-edit').prop('checked', checked);
            }

            $('body').on('click', '.editinline', function () {
                let post_id = $(this).closest('tr').attr('id').replace("post-", "");
                let hasTerm = $('#poa_term_' + post_id).val() === 'yes';
                set_quick_edit_value(post_id, hasTerm);
            });
        });
    </script>
    <?php
}


add_filter('manage_product_posts_custom_column', 'poa_add_hidden_term_value', 10, 2);
function poa_add_hidden_term_value($column, $post_id) {
    if ($column == 'name') {
        $has_term = has_term('yes', 'power_outage_availability', $post_id) ? 'yes' : 'no';
        echo '<input type="hidden" id="poa_term_' . esc_attr($post_id) . '" value="' . esc_attr($has_term) . '" />';
    }
}

add_filter('manage_edit-product_columns', 'poa_remove_taxonomy_column');
function poa_remove_taxonomy_column($columns) {
    if (isset($columns['taxonomy-power_outage_availability'])) {
        unset($columns['taxonomy-power_outage_availability']);
    }
    return $columns;
}


add_action('manage_product_posts_custom_column', 'poa_remove_taxonomy_column_content', 10, 2);
function poa_remove_taxonomy_column_content($column, $post_id) {
    if ($column === 'taxonomy-power_outage_availability') {
        // Do nothing to leave the cell empty
        return;
    }
}

