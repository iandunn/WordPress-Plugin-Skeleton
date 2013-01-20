# WordPress-Plugin-Skeleton

The skeleton for an object-oriented/MVC WordPress plugin.


## Features

* Minimal, clean and organized
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


## Notes

* I decided to not internationalize the skeleton because most of my clients don't need it and having to put all regular text in PHP strings annoys me. If you're distributing the plugin, though, then you should internationalize it.
* I prefer having controllers and models in the same class/file, so I use an unofficial @mvc tag in the phpDoc comments to mentally keep track of which methods are controllers and which are models. 


## Installation

* cd /var/www/vhosts/example.com/content/plugins
* git clone https://github.com/iandunn/WordPress-Plugin-Skeleton.git plugin-slug
* cd plugin-slug
* git checkout [latest stable tag]
* git submodule init
* git submodule update
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


## TODO


* Next / In Progress
	* Add shortcodes example 
		* added, now write unit tests
	* MVC refinement
		* check all functions for proper mvc separation
			* make sure controllers aren't definiing data, only calling it from models
			* make sure models aren't including views, only controllers do that
			* make sure views aren't calling models (or API), that the controller provides data it for them?
		* maybe move images,css to views dir because they're part of view
			* maybe js too?
			* even if that's technically correct, maybe leave in root b/c it's more practical?
	* OOP refinement
		* modules are tightly coupled? try to loosen?
		* make module::instances protected, so that all the modules can access each other?
			* or should it be private? they can use ::getinstance instead
		* make $readableProperties aprt of module, and inherit? 
		* rename non-static-class to something else? 
			* difference is that it's not a module.
			* doesn't interact w/ api, would just be on it's own.
		* cpt should be inheritence instead of interface?
	* Check existing forms for nonces, check_admin_referer();
	* Add data validation to user options
		* Add domain-level validation (verify type, format, whitelist values, etc)
	* Add validation/sanization everywhere, and nonces, current_user_can()
	* Add filters to everything
	* Make WPPSCustomPostType an abstract class instead of interface? It would extend WPPSModule
		* but then the cpt class couldn't define activate() etc? well, it would extend it
		* example just sets up a var to define $labels, etc ?
	* Move trash/untrash return checks in cpt save() to abstract class instead of interface? 
	
	
* High Priority
	* Change models to return a WP_Error instead of false or null, so that controllers can get a detailed error message
		* Models shouldn't ever add notices, only return error to controller so it can add notice
		* Throw exception if encounter unexpected condition, but return WP_Error if just need to return early
	* Provide 2 examples of everything to make architecture more clear
	* Add more sample classes, then add to features
		* AJAX. Not really its own class, so maybe just add to CPT
		* Look at BGMP and other past plugins for ideas
		* Widgets. Write an interface.
	* Javascript
		* Add AJAX calls
			* Make sure use nonces
		* Example of filters/hooks once WP settles how those will be handeled (see comments on http://www.meetup.com/SeattleWordPressMeetup/events/76033072/)
	* Bug - cron job not scheduled under WPMS 3.4.1, but works fine on single install. Maybe related to WPMS cron bugs, see Trac tickets.
	* Add integration tests
		* Things like get_post_types() and check if it's present, is_post_type_hierarchical(), etc
		* Ceck that cron jobs and intervals are registered, that settings pages/sections/fields are registered, etc
		* Move test suite to main tests/ dir instead of in unit sub-sir 
		* Fire cron job and test that it affected something
		* activation/deactivation? 
	* When replacing "WordPress Plugin Skeleton" on installation, it also replaces the "this was built on..." notice in bootstrap.
		* Change bootstrap text to avoid - replace spaces with underscores?
	* Write shell script to rename 
		* Ship as .txt file, so user has to manually rename to .sh and execute
		* Output warning to delete script after finished
	* Add do_action( WordPressPluginSkeleton::PREFIX . 'descriptive-name-before|after' ); to views so other devs can hook into them
	
* Medium Priority
	* Look through current code for best practices to add to checklist
	* Bug - notices inside WordPressPluginSkeleton::init() never get cleared when viewing cpt page, but they mostly do on dashboard
	* Support for conditionally loading js/css
	* Add to notes section for any non-standard things or anything that needs explaining
	* Add exceptions. Add try/catch blocks to all hook callbooks, but nowhere else. Let them bubble up to first callback.
	* Add extra error checking/handling when calling API functions (e.g., register_post_type() )
	* Update Features w/ any other advantages
	* Throw/catch exceptions in places. Maybe just in action/filter callbacks, since everything should bubble up to them
	* CPT meta boxes - use get_current_screen() instead of global $post
	* Add network-wide deactivation? Or is that done automatically?
	* BGMP addFeaturedImageSupport()
	* Add BGMP release checklist? Entire TODO file?
	* Look for @todo's and cleanup
	* Update requirements warning language. Primary concern is more about PHP 5.3 rather than PHP 5.
	* Write a shell script for renaming class names, variables, etc?
	* Maybe there's a way WordPressPluginSkeleton->upgrade() can do settings[ 'db-version' ] = x instead of = array( 'db-version' => x );
		* http://mwop.net/blog/131-Overloading-arrays-in-PHP-5.2.0.html
	* Add uninstall.php
	* Definte constants during init hook callback and only if they haven't already been defined, so they can be overridden easily
		* http://willnorris.com/2009/06/wordpress-plugin-pet-peeve-3-not-being-extensible
	* Singleton unnecessary for front controller? http://stackoverflow.com/questions/4595964/who-needs-singletons/4596323#4596323
	* cpt - support restore revisions. bug when restoring?
	* change js/css to be for individual modules rather than whole plugin
		* more modular and organized. could result in lots of http requests, but can concatinate/minify at runtime w/ other plugins
	 
* Low Priority
	* Better singular/plural handling for custom post type names
	* Maybe use a single view file for all meta boxes (within a class), rather than multiple. Switch on the box id just like the callback does.
	* Add underscore to custom post meta fields, so they don't show up in Custom Fields box? See http://net.tutsplus.com/tutorials/wordpress/creating-custom-fields-for-attachments-in-wordpress/
	* Use API functions in WPPSCPTExample::savePost() instead of accessing $post directly
	* Make sure all hook callbacks have all of their parameters declared, even if you don't typically use them
	* Add a status icon for each of the plugin requirements in the requirements-not-met view, so the user can easily tell which are met and which aren't
	* In page settings view, you should be able to grab the title from an API function instead of manually typing it
	* Clear admin notices when tearing down unit tests so that they don't show up on the next normal page load
	* Add command to instructions to clear git log/history/commits etc, so that it starts fresh?
	* Use {$new_status}_{$post->post_type} instead of save_post in CPTs ?
		* Otherwise run into problem where save_post only fires if a core field is changed?
	* Refactor the conditionals at the begining of CPTExample::savePost() so they can be reused?
	* Break CPT TAG_NAME into TAG_NAME_SINGULAR and TAG_NAME_PLURAL
	* validateSettigns() - force db-version to equal self::db-version? no reason why it should ever be set to anything else?
	* Consider if it'd be useful to add any custom wp-cli commands, or extensions to Debug Bar or Debug This
	
## New Code Checklist

* Security
	* Input validation for domain correctness and security.
	* Output sanitization when sending untrusted data to browser.
		* All data should be considered untrusted.
		* Escape hardcoded data to future-proof.
	* Pass any manual SQL queries through $wpdb->prepare().
	* Add/check nonces for all forms and AJAX requests.
	* Make sure current user is authorized to perform the action with current_user_can().
* Add filters
* Write unit and integration tests
* Throw/catch exceptions when encountering invalid conditions.
* Use did_action() in action callbacks 


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