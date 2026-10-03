=== MornRain Reading Time ===
Contributors: mornrain
Donate link: https://github.com/mornrain-lin
Tags: reading time, post, estimate, cjk, content
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically shows an estimated reading time above the content of every single post.

== Description ==

MornRain Reading Time measures the length of a post and prints a small,
unobtrusive notice above the content, for example `12 minute read`.

The estimate is computed from two separate buckets so that mixed-language sites
stay accurate:

* Latin words are counted as whitespace separated tokens, at 200 words per
  minute by default.
* CJK characters (Chinese, Japanese, Korean) are counted individually, at 450
  characters per minute by default.

The analysis runs on the stored post content, so it is unaffected by themes,
page builders or shortcode expansion. Excerpts, feeds and archive pages are
never touched, and the notice is printed exactly once per post even if the
content filter runs more than once.

This plugin stores nothing you did not explicitly configure, sends
no data to any remote service, and adds no custom database tables.

== Installation ==

1. Upload the `mornrain-reading-time` folder to the `/wp-content/plugins/` directory, or
   install the ZIP through *Plugins > Add New > Upload Plugin*.
2. Activate the plugin through the *Plugins* screen in WordPress.
3. That is all. Open any single post and the notice appears automatically.

== Frequently Asked Questions ==

= Which post types are supported? =

Every public post type that renders through `the_content` on a singular view.
You can narrow it down per post with the `mornrain_reading_time_enabled`
filter.

= Can I move the notice below the content? =

Yes:

    add_filter( 'mornrain_reading_time_position', function () {
        return 'after';
    } );

= Does it work with Chinese content? =

Yes. Han, Hiragana, Katakana, Hangul and CJK extensions are counted character by
character instead of being treated as words.

= Can I print the reading time somewhere else? =

Yes, with the `[mornrain_reading_time]` shortcode, or by calling
`mornrain_reading_time_get_html( $post_id )` in a template.

= Does it slow the site down? =

The calculation is a pair of regular expressions over already-loaded post
content, so the cost is negligible.

== Screenshots ==

1. The plugin working on the front end.
2. The relevant WordPress admin screen.

== Changelog ==

= 1.0.0 =
* Initial public release.

== Upgrade Notice ==

= 1.0.0 =
Initial public release.
