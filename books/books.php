<?php /*
Plugin Name: find books
Description: This plugin find the books on category base
Author: Nishant Thakur
Version: 1.0.1
*/

/*
 *
 *  // CUSTOM POST TYPE BOOKS
 *
 *
 *
 */
if (!defined("WPINC")) {
    die();
} // end if
function create_books_cpt()
{
    $labels = [
        "name" => _x("Books", "post type general name", "stacy"),
        "singular_name" => _x("Books", "post type singular name", "stacy"),
        "menu_name" => _x("Books", "admin menu", "stacy"),
        "name_admin_bar" => _x("Books", "add new on admin bar", "stacy"),
        "add_new" => _x("Add New Books", "Books", "stacy"),
        "add_new_item" => __("Add New Books", "stacy"),
        "new_item" => __("New Books", "stacy"),
        "edit_item" => __("Edit Books", "stacy"),
        "view_item" => __("View Books", "stacy"),
        "all_items" => __("All Books", "stacy"),
        "search_items" => __("Search Books", "stacy"),
        "not_found" => __("No Books found.", "stacy"),
        "not_found_in_trash" => __("No Books found in Trash.", "stacy"),
    ];
    $args = [
        "labels" => $labels,
        "description" => __("Description.", "Add New Books on stacy"),
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "query_var" => true,
        "rewrite" => ["slug" => "books"],
        "has_archive" => true,
        "hierarchical" => false,
        "menu_position" => 4,
        "menu_icon" => "dashicons-book",
        "supports" => ["title","editor","author","thumbnail","comments","capabilities",],
        "taxonomies" => ["books_category"],
    ];
    $category_labels = [
        "name" => _x("Books Categories", "taxonomy general name"),
        "singular_name" => _x("books category", "taxonomy singular name"),
        "search_items" => __("Search Books Categories"),
        "all_items" => __("All Books Categories"),
        "parent_item" => __("Parent Categories"),
        "parent_item_colon" => __("Parent Categories:"),
        "edit_item" => __("Edit Books Categories"),
        "update_item" => __("Update Books Categories"),
        "add_new_item" => __("Add New Books Category"),
        "new_item_name" => __("New Books Category Name"),
        "menu_name" => __("Books Categories"),
    ];

    $category_args = [
        "hierarchical" => true,
        "labels" => $category_labels,
        "show_ui" => true,
        "show_admin_column" => true,
        "query_var" => true,
        "rewrite" => ["slug" => "books_category"],
    ];
    register_post_type("books", $args);
    register_taxonomy("books_category", ["books"], $category_args);
}
add_action("init", "create_books_cpt");


function theme_enqueue_styles()
{
    // added custon css file
    wp_enqueue_style(
        "custom-css",
        plugin_dir_url(__FILE__) . "/custom-css/custom-style.css"
    );
    // added font-awesome link
    wp_enqueue_style(
        "font-awsome",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    );
        // added jquery-ui css link

    wp_enqueue_style(
        "custom-ajax",
        "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css"
    );
        // added custom js file

    wp_enqueue_script(
        "custom-js",
        plugin_dir_url(__FILE__) . "/custom-js/custom-script.js",
        ["jquery"],
        null,
        true
    );
        // added sweet alert link

    wp_enqueue_script(
        "sweety-alert",
        "https://cdn.jsdelivr.net/npm/sweetalert2@11"
    );
        // added jquery range link

    wp_enqueue_script(
        "custom-jquery",
        "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"
    );
            // added jquery-ui range link

    wp_enqueue_script(
        "custom-ui-jquery",
        "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
    );
}
add_action("wp_enqueue_scripts", "theme_enqueue_styles");

// added short code

add_shortcode("user_books", "get_all_books_display");
function get_all_books_display()
{
    ?>
<section class="user-blog-post">
    <div class="years-range-block">
        <div id="slider-range" class="years-filter-range" name="rangeInput">
        </div>
        <div class="row">
            <div class="col-md-2">
                From Years<input type="text" class="form-control years-range-field" min=0 max="9900"
                    oninput="validity.valid||(value='0');" id="min_years" />
            </div>
            <div class="col-md-2">
                To Years<input type="text" class="form-control years-range-field" min=0 max="10000"
                    oninput="validity.valid||(value='10000');" id="max_years" />
            </div>
            <div class="col-md-1">
                <button class="years-range-search" id="years-range-submit" type="submit" style="display:none;">
                    <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="loading-state loader" style="display: none;">
    </div>
    <div class="loading-overlay">
    </div>
    <div class="blogs users-blog">
    </div>
</section>
<script>
jQuery(document).ready(function($) {
    function showLoader() {
        $('.loading-overlay').show();
        $('.loading-state').show();
    }

    function hideLoader() {
        $('.loading-overlay').hide();
        $('.loading-state').hide();
    }

    // Ajax Load on page load
    var site_url = '<?php echo site_url(); ?>';
    var main_action = "action=get_all_books_users";
    var formData = main_action;

    showLoader(); // Initial Show loader

    $.ajax({
        url: `${site_url}/wp-admin/admin-ajax.php`,
        type: 'post',
        data: formData,
        dataType: 'html',
        success: function(response) {
            hideLoader();
            $(".users-blog").html(response);
        }
    });
    // Show more button
    var posts_per_page = 3; // Post per page
    var pageNumber = 1;
    $(document).on('click', '#load_more_post', function() {
        showLoader();
        pageNumber++;
        var min_years = $("#min_years").val();
        var max_years = $("#max_years").val();
        var site_url = '<?php echo site_url(); ?>';
        var main_action = "action=get_all_books_users";
        var formData = main_action + "&pageNumber=" + pageNumber + "&posts_per_page=" + posts_per_page +
            "&min_years=" + min_years + "&max_years=" + max_years;
        $.ajax({
            url: `${site_url}/wp-admin/admin-ajax.php`,
            type: 'post',
            data: formData,
            dataType: 'html',
            success: function(response) {
                hideLoader();
                $(".users-blog").append(response);
            }
        });
        $(this).hide();
    });
    // Search Code start
    $('#years-range-submit,#slider-range').click(function() {
        var min_years = $("#min_years").val();
        var max_years = $("#max_years").val();
        if (min_years == "" || max_years == "") {
            Swal.fire("please select your from year and to year!");

        } else {

            var main_action = "action=get_all_books_users";
            var site_url = '<?php echo site_url(); ?>';
            var formData = main_action + "&min_years=" + min_years + "&max_years=" + max_years;
            showLoader();
            $.ajax({
                url: `${site_url}/wp-admin/admin-ajax.php`,
                type: 'post',
                data: formData,
                dataType: 'html',
                success: function(response) {
                    hideLoader();
                    $(".users-blog").html(response);
                }
            });
        }
    });

});
</script>

<?php
}
add_action("wp_ajax_get_all_books_users", "get_all_books_users");
add_action("wp_ajax_nopriv_get_all_books_users", "get_all_books_users");
function get_all_books_users()
{
    $posts_per_page = isset($_POST["posts_per_page"])
        ? $_POST["posts_per_page"]
        : 3;
    $pageNumber = isset($_POST["pageNumber"]) ? $_POST["pageNumber"] : 1;
    $from_date = isset($_POST["min_years"]) ? $_POST["min_years"] : "";
    $to_date = isset($_POST["max_years"]) ? $_POST["max_years"] : "";
    if (!empty($from_date) && !empty($to_date)) {
        $tax_query = [
            [
                "taxonomy" => "books_category",
                "field" => "slug",
                "terms" => range($from_date, $to_date),
                "operator" => "IN",
            ],
        ];
    } else {
        $tax_query = [];
    }
    $args = [
        "post_type" => "books",
        "posts_per_page" => $posts_per_page,
        "post_status" => "publish",
        "order" => "ASC",
        "paged" => $pageNumber,
        "tax_query" => $tax_query,
    ];
    //    echo "<pre>"; print_r($args); echo "</pre>"; die;
    $query = new WP_Query($args);
    if ($query->have_posts()):
        // "<pre>"; print_r($query); echo "</pre>";
        $count = 0; ?>
<section class="main-section">
    <div class="container">
        <div class="row">
            <?php while ($query->have_posts()):

                $query->the_post();
                $count++;
                $categories = get_the_terms(get_the_ID(), "books_category");
                ?>
            <?php $id = get_the_ID(); ?>
            <?php $content = get_post_field("post_content", $id); ?>
            <?php $title = get_the_title($id); ?>
            <?php $image = wp_get_attachment_url(get_post_thumbnail_id($id)); ?>
            <div class="col">
                <img src="<?php echo $image; ?>" alt="post-img" style="width: 150px;">
                <h4><?php echo $title; ?></h4>
                <!-- <p><?php echo $content; ?></p> -->
                <?php foreach ($categories as $category): ?>
                <p><?php echo $category->name; ?></p>
                <?php endforeach; ?>
            </div>
            <?php
            endwhile; ?>
        </div>
    </div>
</section>
<?php if ($count >= 3) { ?>
<div class="post-navigation">
    <button class="newslist_show-more_button" id="load_more_post">Load More</button>
</div>
<?php } ?>
<?php
    endif;
    wp_die();
} ?>
