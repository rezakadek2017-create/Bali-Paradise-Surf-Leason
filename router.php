<?php
/**
 * Local Development Server & WordPress Simulator for Bali Paradise Surf Lesson
 * Allows previewing and running the WordPress theme without a database or full WP installation.
 */

// 1. Static asset handling for PHP built-in server
$request_uri = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
$theme_dir = __DIR__ . '/bali-paradise-surf';

// Normalize URI to check file existence
$clean_path = ltrim($request_uri, '/');
if (strpos($clean_path, 'bali-paradise-surf/') === 0) {
    $clean_path = substr($clean_path, strlen('bali-paradise-surf/'));
}

$file_path = $theme_dir . '/' . $clean_path;
if ($clean_path !== '' && file_exists($file_path) && !is_dir($file_path)) {
    $ext = pathinfo($file_path, PATHINFO_EXTENSION);
    $mime_types = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'webp' => 'image/webp',
        'ico'  => 'image/x-icon',
        'woff2'=> 'font/woff2',
        'woff' => 'font/woff',
        'ttf'  => 'font/ttf',
    ];
    if (isset($mime_types[strtolower($ext)])) {
        header('Content-Type: ' . $mime_types[strtolower($ext)]);
        header('Cache-Control: public, max-age=3600');
        readfile($file_path);
        exit;
    }
}

// 2. Mock WordPress Environment
if (!defined('ABSPATH')) {
    define('ABSPATH', $theme_dir . '/');
}

// Action & Filter Hook System
$wp_actions = [];
$wp_filters = [];

function add_action($tag, $callback, $priority = 10, $accepted_args = 1) {
    global $wp_actions;
    $wp_actions[$tag][$priority][] = $callback;
}

function do_action($tag, ...$args) {
    global $wp_actions;
    if (!isset($wp_actions[$tag])) return;
    ksort($wp_actions[$tag]);
    foreach ($wp_actions[$tag] as $priority_callbacks) {
        foreach ($priority_callbacks as $cb) {
            call_user_func_array($cb, $args);
        }
    }
}

function remove_action($tag, $callback, $priority = 10) {
    global $wp_actions;
    if (isset($wp_actions[$tag][$priority])) {
        // Simple removal
    }
}

function add_filter($tag, $callback, $priority = 10, $accepted_args = 1) {
    global $wp_filters;
    $wp_filters[$tag][$priority][] = $callback;
}

function apply_filters($tag, $value, ...$args) {
    global $wp_filters;
    if (!isset($wp_filters[$tag])) return $value;
    ksort($wp_filters[$tag]);
    foreach ($wp_filters[$tag] as $priority_callbacks) {
        foreach ($priority_callbacks as $cb) {
            $value = call_user_func_array($cb, array_merge([$value], $args));
        }
    }
    return $value;
}

// WordPress Core Helpers
function get_template_directory() {
    return __DIR__ . '/bali-paradise-surf';
}

function get_template_directory_uri() {
    return '/bali-paradise-surf';
}

function home_url($path = '') {
    return '/' . ltrim($path, '/');
}

function admin_url($path = '') {
    return '#admin-' . urlencode($path);
}

function is_front_page() { return true; }
function is_singular($type = '') { return false; }
function language_attributes() { echo 'lang="en"'; }
function bloginfo($show = '') {
    if ($show === 'charset') {
        echo 'UTF-8';
    } else {
        echo 'Bali Paradise Surf Lesson';
    }
}
function get_bloginfo($show = '') {
    if ($show === 'name') return 'Bali Paradise Surf Lesson';
    if ($show === 'description') return '100% Balinese Owned & Operated Surf School';
    return 'Bali Paradise Surf Lesson';
}

function body_class($class = '') {
    $classes = 'home front-page ' . (is_string($class) ? $class : implode(' ', (array)$class));
    echo 'class="' . trim($classes) . '"';
}

function wp_body_open() { do_action('wp_body_open'); }
function has_custom_logo() { return false; }
function the_custom_logo() {}
function current_user_can($cap) { return false; }

function esc_html($text) { return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); }
function esc_attr($text) { return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8'); }
function esc_url($url) { return htmlspecialchars((string)$url, ENT_QUOTES, 'UTF-8'); }
function esc_url_raw($url) { return (string)$url; }
function wp_kses_post($text) { return (string)$text; }
function wp_strip_all_tags($text) { return strip_tags((string)$text); }
function wp_json_encode($data, $options = 0) { return json_encode($data, $options); }
function antispambot($email) { return (string)$email; }
function sanitize_text_field($str) { return trim(strip_tags((string)$str)); }
function sanitize_textarea_field($str) { return trim(strip_tags((string)$str)); }
function sanitize_email($email) { return filter_var((string)$email, FILTER_SANITIZE_EMAIL); }
function absint($maybeint) { return abs((int)$maybeint); }
function _e($text, $domain = 'default') { echo $text; }
function __($text, $domain = 'default') { return $text; }
function _x($text, $context, $domain = 'default') { return $text; }

function wpautop($pee, $br = true) {
    if (trim($pee) === '') return '';
    $pee = $pee . "\n";
    $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);
    $pee = preg_replace('!(<(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|p|h[1-6]|hr)[^>]*>)!', "\n$1", $pee);
    $pee = preg_replace('!(</(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|p|h[1-6]|hr)>)!', "$1\n\n", $pee);
    $pee = str_replace(["\r\n", "\r"], "\n", $pee);
    $peess = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
    $pee = '';
    foreach ($peess as $tpee) {
        $pee .= '<p>' . trim($tpee, "\n") . "</p>\n";
    }
    $pee = preg_replace('|<p>\s*</p>|', '', $pee);
    return $pee;
}

// Scripts & Styles Enqueue Simulation
$wp_styles = [];
$wp_scripts = [];

function wp_enqueue_style($handle, $src = '', $deps = [], $ver = false) {
    global $wp_styles;
    $wp_styles[$handle] = $src;
}
function wp_dequeue_style($handle) {
    global $wp_styles;
    unset($wp_styles[$handle]);
}
function wp_enqueue_script($handle, $src = '', $deps = [], $ver = false, $args = []) {
    global $wp_scripts;
    $wp_scripts[$handle] = $src;
}
function add_theme_support($feature, ...$args) {}
function add_image_size($name, $width = 0, $height = 0, $crop = false) {}
function register_post_type($post_type, $args = []) {}
function get_current_screen() { return null; }

function wp_head() {
    global $wp_styles;
    do_action('wp_head');
    foreach ($wp_styles as $handle => $src) {
        echo '<link rel="stylesheet" id="' . esc_attr($handle) . '" href="' . esc_url($src) . '" media="all">' . "\n";
    }
}

function wp_footer() {
    global $wp_scripts;
    foreach ($wp_scripts as $handle => $src) {
        echo '<script id="' . esc_attr($handle) . '" src="' . esc_url($src) . '" defer></script>' . "\n";
    }
    do_action('wp_footer');
}

function wp_get_document_title() {
    return apply_filters('pre_get_document_title', 'Bali Paradise Surf Lesson | Surf Lessons in Seminyak, Bali');
}

// Template Include Functions
function get_header($name = null) {
    require __DIR__ . '/bali-paradise-surf/header.php';
}
function get_footer($name = null) {
    require __DIR__ . '/bali-paradise-surf/footer.php';
}
function get_template_part($slug, $name = null, $args = []) {
    $file = __DIR__ . '/bali-paradise-surf/' . $slug . '.php';
    if (file_exists($file)) {
        require $file;
    }
}

// Mock Options & Customizer
$theme_mods = [
    'bpsl_whatsapp' => '6281394265915',
    'bpsl_email'    => '',
    'bpsl_instagram'=> 'https://www.instagram.com/gede_rusmawan?igsh=MWI2M3BkM3V0dTg0aw==',
    'bpsl_tripadvisor' => '',
    'bpsl_google_reviews' => '',
    'bpsl_maps_url' => 'https://www.google.com/maps/dir/?api=1&destination=Bali%20Paradise%20Surf%20Lesson%2C%20Jl.%20Arjuna%2C%20Seminyak%2C%20Kec.%20Kuta%2C%20Kabupaten%20Badung%2C%20Bali',
    'bpsl_hero_image' => 1,
    'bpsl_hero_title' => 'Catch Your First Wave with True Balinese Locals',
    'bpsl_hero_text'  => 'Friendly, authentic surf lessons in Bali for all ages and skill levels. By surfing with us, you directly support local families and village communities.',
    'bpsl_meta_description' => 'Friendly, authentic surf lessons in Bali for all ages and skill levels. By surfing with us, you directly support local families and village communities.',
    'bpsl_included' => "Premium soft-top surfboard & safety leash\nUV rash guard provided\nLocal Balinese instructor for your whole lesson\nBeach safety briefing & ocean awareness\nPop-up technique drills on the sand\n15-minute hydration & chill break",
    'bpsl_bring'    => "Swimwear\nTowel\nSunscreen\nSmile — we handle the rest!",
];

function get_theme_mod($name, $default = false) {
    global $theme_mods;
    return isset($theme_mods[$name]) ? $theme_mods[$name] : $default;
}

// Mock Post System & Database Objects
class MockPost {
    public $ID;
    public $post_title;
    public $post_content;
    public $post_type;
    public $meta = [];

    public function __construct($id, $title, $content, $type, $meta = []) {
        $this->ID = $id;
        $this->post_title = $title;
        $this->post_content = $content;
        $this->post_type = $type;
        $this->meta = $meta;
    }
}

$mock_posts = [
    // Packages (real pricing from live site)
    new MockPost(101, 'Private', "One teacher, one student — full focus.\n\nFastest way to progress, every wave is yours.", 'bpsl_package', [
        'price'      => 500000,
        'unit'       => 'person',
        'instructor' => '1 Teacher',
        'students'   => '1 Student',
        'duration'   => '2 Hours + 15 Min Break',
        'badge'      => '',
    ]),
    new MockPost(102, 'Semi-Private', "One teacher shared between two students.\n\nPerfect for couples or a parent & child.", 'bpsl_package', [
        'price'      => 450000,
        'unit'       => 'person',
        'instructor' => '1 Teacher',
        'students'   => '2 Students',
        'duration'   => '2 Hours + 15 Min Break',
        'badge'      => '',
    ]),
    new MockPost(103, 'Small Group', "Two teachers for four students — a low 1:2 ratio.\n\nGreat for friends & families.", 'bpsl_package', [
        'price'      => 400000,
        'unit'       => 'person',
        'instructor' => '2 Teachers',
        'students'   => '4 Students',
        'duration'   => '2 Hours + 15 Min Break',
        'badge'      => '',
    ]),
    new MockPost(104, 'Big Group', "3–4 teachers for 8–9 students — no one left waiting.\n\nOur most affordable way to ride. Perfect for groups, classes & celebrations.", 'bpsl_package', [
        'price'      => 300000,
        'unit'       => 'person',
        'instructor' => '3–4 Teachers',
        'students'   => '8–9 Students',
        'duration'   => '2 Hours + 15 Min Break',
        'badge'      => 'Best Value',
    ]),

    // Locations
    new MockPost(201, 'Seminyak Beach', "Our home base on Jl. Arjuna. Sandy bottom with forgiving waves when the swell is small.", 'bpsl_location', [
        'level' => 'Beginners and improvers',
        'img'   => '/bali-paradise-surf/assets/img/img_1782418083920_ea123930.webp',
    ]),
    new MockPost(202, 'Kuta Beach', "A long, sandy beach with gentle white-water waves. A classic first-lesson spot.", 'bpsl_location', [
        'level' => 'Beginners',
        'img'   => '/bali-paradise-surf/assets/img/img_1782418083765_bfe16419.webp',
    ]),
    new MockPost(203, 'Canggu', "Slow, rolling waves that suit longboards. We choose the best break for the day.", 'bpsl_location', [
        'level' => 'Improvers',
        'img'   => '/bali-paradise-surf/assets/img/img_1782418083849_5c252b61.webp',
    ]),

    // Testimonials (real reviews from live site)
    new MockPost(301, 'Sophie M.', "Stood up on my very first wave! Our instructor was so patient and encouraging. Honestly the best morning of our whole Bali trip — I can't recommend them enough.", 'bpsl_testimonial', [
        'origin' => 'Australia',
        'rating' => 5,
    ]),
    new MockPost(302, 'Lukas & Anna', "Booked a semi-private lesson for our honeymoon. Safe, fun and genuinely warm people who made us feel like friends. We left with huge smiles and amazing photos.", 'bpsl_testimonial', [
        'origin' => 'Germany',
        'rating' => 5,
    ]),
    new MockPost(303, 'Priya R.', "Took my two kids (8 and 11) and they LOVED every second. The team made them feel completely safe in the water. We're already planning to come back next holiday.", 'bpsl_testimonial', [
        'origin' => 'Singapore',
        'rating' => 5,
    ]),

    // FAQs (real from live site)
    new MockPost(401, 'Do I need any experience to join?', "Not at all! Most of our guests have never surfed before. We start on the sand with the basics, then head into gentle, beginner-friendly white water. Complete first-timers are our specialty.", 'bpsl_faq'),
    new MockPost(402, 'Is it safe for kids and non-swimmers?', "Yes. We teach in shallow, waist-deep water with a low student-to-instructor ratio and provide a buoyant soft-top board and rash guard. Every lesson is adapted to your age, comfort and confidence.", 'bpsl_faq'),
    new MockPost(403, 'What should I bring?', "Just swimwear, a towel, sunscreen and a smile. We provide the surfboard, rash guard and all the coaching. Leave valuables at your accommodation — the beach is casual and relaxed.", 'bpsl_faq'),
    new MockPost(404, 'What if the waves or weather are bad?', "The ocean changes daily, so we adjust lesson times to the safest tides. Message us on WhatsApp and we'll tell you the best time to surf today — or reschedule for free if conditions aren't right.", 'bpsl_faq'),
    new MockPost(405, 'How do I book a lesson?', "Simply tap any WhatsApp button on this page. Tell us your date, group size and experience level — we usually reply within minutes and confirm today's best tide for you.", 'bpsl_faq'),

    // Gallery (real images from live site)
    new MockPost(501, 'Surf lesson action', "Real surf lesson moments.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083920_ea123930.webp',
        'thumb_id' => 1001,
    ]),
    new MockPost(502, 'Beach smiles', "Happy surfers on Bali beach.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083765_bfe16419.webp',
        'thumb_id' => 1002,
    ]),
    new MockPost(503, 'Wave riding', "Catching waves in Seminyak.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083849_5c252b61.webp',
        'thumb_id' => 1003,
    ]),
    new MockPost(504, 'Group surf fun', "Friends surfing together.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418084140_24da6cc3.webp',
        'thumb_id' => 1004,
    ]),
    new MockPost(505, 'Instructor guiding', "Local Balinese instructor guiding.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083659_2b0af617.webp',
        'thumb_id' => 1005,
    ]),
    new MockPost(506, 'Sunset session', "Golden hour surf session.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083709_d0e16f99.webp',
        'thumb_id' => 1006,
    ]),
    new MockPost(507, 'First wave joy', "Standing up on the first wave.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418084010_63078912.webp',
        'thumb_id' => 1007,
    ]),
    new MockPost(508, 'Beach lifestyle', "Bali surf lifestyle.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418084233_77530d6c.webp',
        'thumb_id' => 1008,
    ]),
    new MockPost(509, 'Ocean vibes', "Crystal clear Bali ocean.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418084298_0ac5750d.webp',
        'thumb_id' => 1009,
    ]),
    new MockPost(510, 'Surf stoke', "Pure surf stoke moments.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418084373_a3579dbe.webp',
        'thumb_id' => 1010,
    ]),
    new MockPost(511, 'Sand practice', "Pop-up practice on the sand.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083958_48aa7394.webp',
        'thumb_id' => 1011,
    ]),
    new MockPost(512, 'Team photo', "Team celebration photo.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418083587_fa412684.webp',
        'thumb_id' => 1012,
    ]),
    new MockPost(513, 'Paradise waves', "Bali Paradise surf waves.", 'bpsl_gallery', [
        'img' => '/bali-paradise-surf/assets/img/img_1782418084454_213ebc94.webp',
        'thumb_id' => 1013,
    ]),
];

function get_posts($args = []) {
    global $mock_posts;
    $type = isset($args['post_type']) ? $args['post_type'] : 'post';
    $results = [];
    foreach ($mock_posts as $p) {
        if ($p->post_type === $type) {
            $results[] = $p;
        }
    }
    return $results;
}

function get_the_title($post = 0) {
    if (is_object($post)) return $post->post_title;
    global $mock_posts;
    foreach ($mock_posts as $p) {
        if ($p->ID == $post) return $p->post_title;
    }
    return '';
}

function get_post_meta($post_id, $key, $single = false) {
    global $mock_posts;
    $clean_key = preg_replace('/^_bpsl_/', '', $key);
    $clean_key = preg_replace('/^_wp_attachment_/', '', $clean_key);
    foreach ($mock_posts as $p) {
        if ($p->ID == $post_id) {
            if (isset($p->meta[$clean_key])) return $p->meta[$clean_key];
            if (isset($p->meta[$key])) return $p->meta[$key];
        }
    }
    return '';
}

function has_post_thumbnail($post = null) {
    if (!is_object($post)) return false;
    return isset($post->meta['img']) || isset($post->meta['thumb_id']);
}

function get_the_post_thumbnail($post = null, $size = 'post-thumbnail', $attr = '') {
    if (!is_object($post) || empty($post->meta['img'])) return '';
    $img = $post->meta['img'];
    $alt = esc_attr($post->post_title);
    return '<img src="' . esc_url($img) . '" alt="' . $alt . '" class="bpsl-thumb" loading="lazy" decoding="async">';
}

function get_post_thumbnail_id($post = null) {
    if (is_object($post) && isset($post->meta['thumb_id'])) {
        return $post->meta['thumb_id'];
    }
    return is_object($post) ? $post->ID : 0;
}

function wp_get_attachment_image($attachment_id, $size = 'thumbnail', $icon = false, $attr = []) {
    $class = isset($attr['class']) ? $attr['class'] : '';
    $loading = isset($attr['loading']) ? $attr['loading'] : 'lazy';
    $fetchpriority = isset($attr['fetchpriority']) ? $attr['fetchpriority'] : 'auto';

    if ($attachment_id == 1) { // Hero
        return '<img src="/bali-paradise-surf/assets/img/img_1782418084454_213ebc94.webp" class="' . esc_attr($class) . '" alt="Bali Paradise Surf Lesson Seminyak" loading="' . esc_attr($loading) . '" fetchpriority="' . esc_attr($fetchpriority) . '">';
    }

    // Gallery / location attachments
    $src = wp_get_attachment_image_url($attachment_id, $size);
    $alt = isset($attr['alt']) ? $attr['alt'] : 'Bali Surf';
    return '<img src="' . esc_url($src) . '" class="' . esc_attr($class) . '" alt="' . esc_attr($alt) . '" loading="' . esc_attr($loading) . '">';
}

function wp_get_attachment_image_url($attachment_id, $size = 'thumbnail') {
    $map = [
        1    => '/bali-paradise-surf/assets/img/img_1782418084454_213ebc94.webp',
        1001 => '/bali-paradise-surf/assets/img/img_1782418083920_ea123930.webp',
        1002 => '/bali-paradise-surf/assets/img/img_1782418083765_bfe16419.webp',
        1003 => '/bali-paradise-surf/assets/img/img_1782418083849_5c252b61.webp',
        1004 => '/bali-paradise-surf/assets/img/img_1782418084140_24da6cc3.webp',
        1005 => '/bali-paradise-surf/assets/img/img_1782418083659_2b0af617.webp',
        1006 => '/bali-paradise-surf/assets/img/img_1782418083709_d0e16f99.webp',
        1007 => '/bali-paradise-surf/assets/img/img_1782418084010_63078912.webp',
        1008 => '/bali-paradise-surf/assets/img/img_1782418084233_77530d6c.webp',
        1009 => '/bali-paradise-surf/assets/img/img_1782418084298_0ac5750d.webp',
        1010 => '/bali-paradise-surf/assets/img/img_1782418084373_a3579dbe.webp',
        1011 => '/bali-paradise-surf/assets/img/img_1782418083958_48aa7394.webp',
        1012 => '/bali-paradise-surf/assets/img/img_1782418083587_fa412684.webp',
        1013 => '/bali-paradise-surf/assets/img/img_1782418084454_213ebc94.webp',
    ];
    return isset($map[$attachment_id]) ? $map[$attachment_id] : '/bali-paradise-surf/assets/img/img_1782418084454_213ebc94.webp';
}

function get_option($option, $default = false) {
    if ($option === 'bpsl_seeded') return 1;
    return $default;
}
function update_option($option, $value, $autoload = null) { return true; }
function wp_insert_post($postarr, $wp_error = false) { return 999; }
function update_post_meta($post_id, $meta_key, $meta_value, $prev_value = '') { return true; }
function is_wp_error($thing) { return false; }

// 3. Load Theme Bootstrap
require_once $theme_dir . '/functions.php';

// Trigger WP hooks that populate head and theme features
do_action('after_setup_theme');
do_action('wp_enqueue_scripts');

// 4. Render Front Page
require $theme_dir . '/front-page.php';
