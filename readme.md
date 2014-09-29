# WordPress Plugin Skeleton

The skeleton for an object-oriented/MVC WordPress plugin.


## Features

* Clean and organized
* Designed with object-oriented principles
* Implements the Model-View-Controller pattern
* Includes basic classes for:
	* Custom post type and taxonomies
	* A settings page
	* Extra user profile fields
	* WP-Cron jobs
* Includes examples of common functionality
	* Single and network-wide activation
	* Upgrade routine
	* Shortcodes
	* CSS/JavaScript enqueing
	* Input validation, output sanitization
	* Custom hooks for extensibility
	* JavaScript event handlers
	* A test suite for unit testing with SimpleTest
* Includes `render_template()`, a wrapper for rendering view files that allows other plugins and themes to easily modify or completely override the view.

## Notes

* I prefer having controllers and models in the same class/file, so I use an unofficial @mvc tag in the phpDoc comments to mentally keep track of which methods are controllers and which are models.
* If you want to learn more about the principles behind the design of this plugin, I gave [a presentation on OOP at WordCamp Seattle 2013](http://iandunn.name/content/presentations/wp-oop-mvc/oop.php), and [another on MVC at WordCamp Columbus 2013](http://iandunn.name/content/presentations/wp-oop-mvc/mvc.php).


## Installation

* cd /var/www/vhosts/example.com/content/plugins
* git clone --recursive https://github.com/iandunn/WordPress-Plugin-Skeleton.git plugin-slug
* cd plugin-slug
* git remote rm origin
* git rm README.md
* Update bootstrap.php headers
* Reset version number to 0.1
* Find/replace class names/slugs
* git mv files to match class slugs
* Comment out references to classes that aren't needed now. Delete ones that will never be needed.
* Rename generic comments, method names, etc to describe their specific implementations.
* If you're not using a custom post type or something else that updates the rewrite rules, you can comment out the flush_rewrite_rules() call in WordPresPluginSkeleton::activate() and ::deactivate().
* For unit testing, install [SimpleTest for WordPress](http://wordpress.org/extend/plugins/simpletest-for-wordpress/) and use a shortcode like this: [simpletest name="WordPress Plugin Skeleton Unit Test Suite" path="/wordpress-plugin-skeleton/tests/wpps-test-suite.php"]


## New Code Checklist

* Security
	* Input validation for domain correctness and security.
	* Output sanitization when sending untrusted data to browser.
		* All data should be considered untrusted.
		* Escape hardcoded data to future-proof.
	* Pass any manual SQL queries through $wpdb->prepare().
	* Add/check nonces for logged in users when submitting forms and AJAX requests.
	* Make sure current user is authorized to perform the action with current_user_can().
	* If added any kind of custom auth scheme, try to think how you could get around it, then protect against those methods.
* Add custom actions and filters
* Write unit and integration tests
* Throw/catch exceptions when encountering invalid conditions.


## License

This is free and unencumbered software released into the public domain.

Anyone is free to copy, modify, publish, use, compile, sell, or
distribute this software, either in source code form or as a compiled
binary, for any purpose, commercial or non-commercial, and by any
means.

In jurisdictions that recognize copyright laws, the author or authors
of this software dedicate any and all copyright interest in the
software to the public domain. We make this dedication for the benefit
of the public at large and to the detriment of our heirs and
successors. We intend this dedication to be an overt act of
relinquishment in perpetuity of all present and future rights to this
software under copyright law.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
OTHER DEALINGS IN THE SOFTWARE.

For more information, please refer to <http://unlicense.org/>