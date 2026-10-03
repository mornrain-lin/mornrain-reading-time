---
AIGC:
    Label: "1"
    ContentProducer: 001191440300708461136T1XGW3
    ProduceID: cf93d2ba4252e3fc820ac383cb09c649_7bb5cfbbbe7a11f18019525400248c00
    ReservedCode1: uBXrUyIWfz8skFRmOJAw1mWH9trys5XmMy/Fga60DFFzhiQtQ1A2kVh7UWapic+ylrar4G2kZFyHCnhhN0kbbouAHkpl6XwXHekWgIl/Rlk++ErEp/bgB9dAtZcaEBvvmhwBQttFIbdrutOmqCVGxzN9v6uyrPmE/NPU58hWTnVgjoWKCjvA3PwiJIA=
    ContentPropagator: 001191440300708461136T1XGW3
    PropagateID: cf93d2ba4252e3fc820ac383cb09c649_7bb5cfbbbe7a11f18019525400248c00
    ReservedCode2: uBXrUyIWfz8skFRmOJAw1mWH9trys5XmMy/Fga60DFFzhiQtQ1A2kVh7UWapic+ylrar4G2kZFyHCnhhN0kbbouAHkpl6XwXHekWgIl/Rlk++ErEp/bgB9dAtZcaEBvvmhwBQttFIbdrutOmqCVGxzN9v6uyrPmE/NPU58hWTnVgjoWKCjvA3PwiJIA=
---

# MornRain Reading Time

> An estimated reading time notice, prepended automatically to every single post.

`MornRain Reading Time` is a lightweight, self-contained WordPress plugin by **MornRain**.
It makes **no outbound network requests**, loads **no external CDN assets**,
creates **no custom database tables** and touches **no user data** beyond what
the site owner explicitly configures.

| Item | Value |
| --- | --- |
| License | GPL v2 or later |
| Minimum WordPress | 6.0 |
| Minimum PHP | 8.0 |
| Text domain | `mornrain-reading-time` |
| Function prefix | `mornrain_reading_time_*` |
| Class prefix | `Mornrain_Reading_Time` |

---

## Table of contents

1. [Features](#features)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Hooks reference](#hooks-reference)
5. [File structure](#file-structure)
6. [Development and quality checks](#development-and-quality-checks)
7. [Frequently asked questions](#frequently-asked-questions)
8. [Changelog](#changelog)
9. [License](#license)

---

## Features

- Prepends an estimated reading time notice to singular content.
- Counts Latin words and CJK characters separately, so mixed-language posts
  stay accurate.
- Never touches excerpts, archives or feeds.
- Guards against duplicate output when `the_content` runs more than once.
- Offers a shortcode for printing the notice anywhere, including inside
  templates.
- Ships a tiny front-end stylesheet that only loads on singular views.
- Stateless: no options, no custom tables, nothing left behind on uninstall.

---

## Installation

### Option A - Install from the WordPress admin (recommended)

1. Download or clone this repository.
2. Compress the `mornrain-reading-time` folder itself into `mornrain-reading-time.zip`. The archive must
   contain the plugin folder, not the repository root.
3. Go to **Plugins > Add New > Upload Plugin**, choose the ZIP, click
   **Install Now**, then **Activate**.

### Option B - Copy the folder over FTP / SSH

1. Copy the whole `mornrain-reading-time` folder into `wp-content/plugins/`.
2. Go to **Plugins** and activate `MornRain Reading Time`.

### Option C - Git clone (developer workflow)

```bash
cd wp-content/plugins
git clone https://github.com/mornrain-lin/mornrain-reading-time.git
```

---

## Configuration

There is no settings screen; behaviour is controlled with filters so the plugin
stays configuration-free.

| Filter | Default | Purpose |
| --- | --- | --- |
| `mornrain_reading_time_enabled` | `true` | Turn the notice on or off per post. |
| `mornrain_reading_time_position` | `before` | Place the notice `before` or `after` the content. |
| `mornrain_reading_time_words_per_minute` | `200` | Latin reading speed. |
| `mornrain_reading_time_characters_per_minute` | `450` | CJK reading speed. |
| `mornrain_reading_time_label` | `%d minute read` | The visible label. |
| `mornrain_reading_time_html` | `<p class="mornrain-reading-time">` | The complete markup. |

Example - move the notice to the bottom of the post and speed up the estimate:

```php
add_filter( 'mornrain_reading_time_position', fn() => 'after' );
add_filter( 'mornrain_reading_time_words_per_minute', fn() => 250 );
```

---

## Hooks reference

| Hook | Type | Purpose |
| --- | --- | --- |
| `mornrain_reading_time_enabled` | filter | `bool $enabled, int $post_id` - suppress the notice. |
| `mornrain_reading_time_position` | filter | `string $position, int $post_id` - `before` or `after`. |
| `mornrain_reading_time_words_per_minute` | filter | `int` - Latin reading speed. |
| `mornrain_reading_time_characters_per_minute` | filter | `int` - CJK reading speed. |
| `mornrain_reading_time_minutes` | filter | `int $minutes, array $counts, string $content`. |
| `mornrain_reading_time_label` | filter | `string $label, int $minutes, WP_Post $post`. |
| `mornrain_reading_time_html` | filter | `string $html, int $minutes, WP_Post $post`. |

Public helper functions:

| Function | Returns |
| --- | --- |
| `mornrain_reading_time_count_content( $content )` | `array{words:int,cjk:int}` |
| `mornrain_reading_time_minutes( $content )` | `int` |
| `mornrain_reading_time_get_html( $post )` | `string` (escaped HTML) |

Shortcode: `[mornrain_reading_time]`, or `[mornrain_reading_time post_id="12"]`.

---

## File structure

```text
mornrain-reading-time/
|-- .github/
|   `-- workflows/
|       `-- build.yml
|-- assets/
|   `-- css/
|       `-- reading-time.css
|-- includes/
|   |-- class-mornrain-reading-time.php
|   |-- functions-reading-time.php
|   `-- shortcode-reading-time.php
|-- tests/
|   |-- ScaffoldTest.php
|   `-- bootstrap.php
|-- mornrain-reading-time.php
|-- composer.json
|-- LICENSE
|-- phpunit.xml.dist
|-- README.md
|-- readme.txt
`-- uninstall.php
```

---

## Development and quality checks

```bash
composer install
composer validate
composer lint   # runs php -l over every PHP file
composer test   # runs PHPUnit
```

Coding style follows the
[WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/):
tab indentation, Yoda conditions, prefixed global functions, nonce and
capability checks on every write path, and escaped output everywhere.

Continuous integration lives in `.github/workflows/build.yml`. It runs on every
push and pull request across PHP 8.1, 8.2 and 8.3: `composer install`,
`php -l` linting, PHPUnit, and finally packages a release ZIP as a build
artifact.

---

## Frequently asked questions

### Which post types are annotated?

Any public post type rendered on a singular view through `the_content`. Use the
`mornrain_reading_time_enabled` filter to narrow it down.

### Can I move the notice below the content?

Yes. Return `after` from the `mornrain_reading_time_position` filter.

### Does it work with Chinese, Japanese and Korean content?

Yes. CJK ideographs and kana are counted per character, which keeps the estimate
realistic for those scripts.

### Will it appear twice if a page builder renders the content twice?

No. The plugin records which posts it has already annotated during the request
and skips any repeat.

### Does it leave data behind when deleted?

No. There is no options row, no custom table and no transient.
`uninstall.php` documents that explicitly.

---

## Changelog

### 1.0.0

- Initial public release.

---

## License

Released under the **GNU General Public License v2 or later**. See
[LICENSE](LICENSE) for the full text.
*（内容由AI生成，仅供参考）*
