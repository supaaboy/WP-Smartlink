<?php
if (!defined('ABSPATH')) {
    exit;
}

class WP_Smartlink
{
    private $options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'page_init'));
        add_shortcode('custom_shortcode', array($this, 'render_shortcode'));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Custom Shortcode Settings',
            'Custom Shortcode',
            'manage_options',
            'custom-shortcode',
            array($this, 'create_admin_page'),
            'dashicons-admin-generic',
            81
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option('custom_shortcode_options');
?>
        <div class="wrap">
            <h1>Custom Shortcode Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('custom_shortcode_group');
                do_settings_sections('custom-shortcode');
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    public function page_init()
    {
        register_setting(
            'custom_shortcode_group',
            'custom_shortcode_options',
            array($this, 'sanitize')
        );

        add_settings_section(
            'setting_section_id',
            'Shortcode Settings',
            array($this, 'print_section_info'),
            'custom-shortcode'
        );

        add_settings_field(
            'title',
            'Title',
            array($this, 'title_callback'),
            'custom-shortcode',
            'setting_section_id'
        );

        add_settings_field(
            'description',
            'Description',
            array($this, 'description_callback'),
            'custom-shortcode',
            'setting_section_id'
        );

        add_settings_field(
            'logo',
            'Logo',
            array($this, 'logo_callback'),
            'custom-shortcode',
            'setting_section_id'
        );

        add_settings_field(
            'items',
            'Items',
            array($this, 'items_callback'),
            'custom-shortcode',
            'setting_section_id'
        );
    }

    public function sanitize($input)
    {
        $new_input = array();

        if (isset($input['title']))
            $new_input['title'] = sanitize_text_field($input['title']);

        if (isset($input['description']))
            $new_input['description'] = sanitize_text_field($input['description']);

        if (isset($input['logo']))
            $new_input['logo'] = esc_url_raw($input['logo']);

        if (isset($input['items']) && is_array($input['items'])) {
            foreach ($input['items'] as $key => $item) {
                $new_input['items'][$key]['text'] = sanitize_text_field($item['text']);
                $new_input['items'][$key]['link'] = esc_url_raw($item['link']);
                $new_input['items'][$key]['icon'] = sanitize_text_field($item['icon']);
            }
        }

        return $new_input;
    }

    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="custom_shortcode_options[title]" value="%s" />',
            isset($this->options['title']) ? esc_attr($this->options['title']) : ''
        );
    }

    public function description_callback()
    {
        printf(
            '<textarea id="description" name="custom_shortcode_options[description]" rows="5" cols="50">%s</textarea>',
            isset($this->options['description']) ? esc_textarea($this->options['description']) : ''
        );
    }

    public function logo_callback()
    {
        printf(
            '<input type="url" id="logo" name="custom_shortcode_options[logo]" value="%s" />',
            isset($this->options['logo']) ? esc_url($this->options['logo']) : ''
        );
    }

    public function items_callback()
    {
        $items = isset($this->options['items']) ? $this->options['items'] : array();
    ?>
        <div id="items-container">
            <?php foreach ($items as $index => $item) : ?>
                <div class="item">
                    <input type="text" name="custom_shortcode_options[items][<?php echo $index; ?>][text]" value="<?php echo esc_attr($item['text']); ?>" placeholder="Text" />
                    <input type="url" name="custom_shortcode_options[items][<?php echo $index; ?>][link]" value="<?php echo esc_url($item['link']); ?>" placeholder="Link" />
                    <input type="text" name="custom_shortcode_options[items][<?php echo $index; ?>][icon]" value="<?php echo esc_attr($item['icon']); ?>" placeholder="Icon" />
                    <button class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button id="add-item" class="button">Add Item</button>
        <script>
            (function($) {
                var index = <?php echo count($items); ?>;
                $('#add-item').on('click', function(e) {
                    e.preventDefault();
                    $('#items-container').append(
                        '<div class="item">' +
                        '<input type="text" name="custom_shortcode_options[items][' + index + '][text]" placeholder="Text" />' +
                        '<input type="url" name="custom_shortcode_options[items][' + index + '][link]" placeholder="Link" />' +
                        '<input type="text" name="custom_shortcode_options[items][' + index + '][icon]" placeholder="Icon" />' +
                        '<button class="remove-item button">Remove</button>' +
                        '</div>'
                    );
                    index++;
                });
                $(document).on('click', '.remove-item', function(e) {
                    e.preventDefault();
                    $(this).closest('.item').remove();
                });
            })(jQuery);
        </script>
    <?php
    }

    public function render_shortcode($atts)
    {
        $options = get_option('custom_shortcode_options');

        if (!$options) {
            return '';
        }

        ob_start();
    ?>
        <div class="custom-shortcode">
            <h2><?php echo esc_html($options['title']); ?></h2>
            <p><?php echo esc_html($options['description']); ?></p>
            <img src="<?php echo esc_url($options['logo']); ?>" alt="<?php echo esc_attr($options['title']); ?>" />
            <ul>
                <?php if (isset($options['items']) && is_array($options['items'])) : ?>
                    <?php foreach ($options['items'] as $item) : ?>
                        <li>
                            <a href="<?php echo esc_url($item['link']); ?>">
                                <i class="<?php echo esc_attr($item['icon']); ?>"></i>
                                <?php echo esc_html($item['text']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
<?php
        return ob_get_clean();
    }
}

if (class_exists('WP_Smartlink')) {
    new WP_Smartlink();
}
