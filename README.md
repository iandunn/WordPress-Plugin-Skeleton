# WordPress-Plugin-Skeleton

The skeleton for an object-oriented/MVC WordPress plugin.


## Features

* Minimal, clean and organized
* Object-oriented
* Uses [the Model-View-Controller pattern](http://www.codinghorror.com/blog/2008/05/understanding-model-view-controller.html)
* Sample classes
	* Custom post type and taxonomies
	* Plugin settings
	* Extra user profile fields
	* WP-Cron jobs
* Unit testing
* Single and network-wide activation
* Upgrade routine
* CSS/JavaScript enqueing
* JavaScript event handlers


## Notes

* I decided to not internationalize the skeleton because most of my clients don't need it and having to put all regular text in PHP strings annoys me. If you're distributing the plugin, though, then you should internationalize it.
* I prefer having controllers and models in the same class/file, so I use an unofficial @mvc tag in the phpDoc comments to mentally keep track of which methods are controllers and which are models. 


## Installation

* cd /var/www/vhosts/example.com/content/plugins
* git clone https://github.com/iandunn/WordPress-Plugin-Skeleton.git plugin-slug
* cd plugin-slug
* git submodule init
* git submodule update
* git remote rm origin
* git rm README.md
* Update bootstrap.php headers
* Reset version number to 0.1
* Find/replace class names/slugs
* git mv files to match class slugs
* Comment out references to classes that aren't needed.
* Rename generic comments, method names, etc to describe their specific implementations. 
* If you're not using a custom post type or something else that updates the rewrite rules, you can remove the flush_rewrite_rules() call in WordPresPluginSkeleton::activate() and ::deactivate().
* For unit testing, install [SimpleTest for WordPress](http://wordpress.org/extend/plugins/simpletest-for-wordpress/) and use a shortcode like this: [simpletest name="WordPress Plugin Skeleton Unit Test Suite" path="/wordpress-plugin-skeleton/tests/unit/wpps-unit-test-suite.php"]


## TODO


* Next
	* Refactor classes as extending abstract base module class
		* non-abstract singleton, abstract activate/registerhookcallbacks, etc 
		* http://scotty-t.com/2012/07/09/wp-you-oop/
			probably do abstract registercallbacks() instead of init, or maybe do both
			reqyures php5.3 if use late static binding
			add construct/__get/__set
			could remove the bit that makes sure registerhooks is only called once since it's a singleton now
		* maybe rename WordPressPluginSkeleton to WPPSMainConrtoller or something like that
		* Some of the classes do have a few stateful variables, so maybe make all the classes singletons and have a $modules array in the main class w/ references to the objects?
			* Static methods are great for methods that take input, return output and never affect a global state. Ones that always return same output given same input. But they should never be used if they affect a global state.
	
	* CPT architecture
		base abstract class for cpts? or interfacts has registerpost type, taxonies, save
		have a abstract WPPSModule class, and a WPPSCustomPostType base abstract class (or interface)
		WPPSCustomPostTypeBase extends WPPSModule
		individual cpts extend WPPSCustomPosttypeBase, or maybe extend wppsmodule and implement customposttypebase, or maybe use traits? but traits are more for things that would be used in multiple classes
	
	* Is making WPPSSettings::$settings public the right way to share it across classes? Maybe use magic getters instead with readonly?
		Yeah, just make it a protected var with w/ readable through __get()
		
	readablepublicvars should really be a constant array, but php doesn't allow, so maybe make a static array. it'd be mutable, but best compromise?
	* Maybe make $notices public in main class, and have others call it, instead of each class creating its own reference
		* maybe add adminnotice instantiatoin as part of base class
		* or use getting to grab it from main controller class?
			WordPressPluginSkeleton::$notices->enqueue() - er, no, 'cause it wouldn't be static
			use dependency injection to pass instance of main contorller class to modules?
				so then it'd be $this->main->notices->enqueue();
				but then how can static methods enqueue notices?
				you could say that would make them non static b/c they're affecting state, but they're affecting state of $notices object
	
* High Priority
	* Check existing forms for nonces, check_admin_referer();
	* Add data validation to user options
		* Add domain-level validation (verify type, format, whitelist values, etc)
	* Add validation/sanization everywhere, and nonces, current_user_can(), then add as feature
	* Add filters to everything, then add as feature
	
	* Provide 2 examples of everything to make architecture more clear
	* Ask other devs for feedback on most things being static, general OO design, etc
		* If it's good, add #note about the static methods
			http://iandunn.name/designing-object-oriented-plugins-for-a-procedural-application/
			* http://stackoverflow.com/questions/2470552/is-there-anything-wrong-with-a-class-with-all-static-methods
			http://scotty-t.com/2012/07/09/wp-you-oop/
			http://xplus3.net/2011/03/08/rethinking-object-oriented-wordpress-plugins/comment-page-1/#comment-2923
	* Have all classes disabled by default, to make isntallation quicker
		* Maybe make WordPressPluginSkeleton::activate/upgrade/etc loops that add all activated classes. So you only have to activate a module in one places, instead of uncommenting several lines.
		* This could integrate w/ the main class singleton having an array of modules/classes
	* Add more sample classes, then add to features
		* AJAX. Not really its own class, so maybe just add to CPT
		* Shortcodes. Not really its own class, so maybe just add to CPT
		* Look at BGMP and other past plugins for ideas
		* Widgets
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
	 
* Low Priority
	* Better singular/plural handling for custom post type names
	* Maybe use a single view file for all meta boxes (within a class), rather than multiple. Switch on the box id just like the callback does.
	* Add underscore to custom post meta fields, so they don't show up in Custom Fields box? See http://net.tutsplus.com/tutorials/wordpress/creating-custom-fields-for-attachments-in-wordpress/
	* Use API functions in WPPSCustomPostType::savePost() instead of accessing $post directly
	* Make sure all hook callbacks have all of their parameters declared, even if you don't typically use them
	* Add a status icon for each of the plugin requirements in the requirements-not-met view, so the user can easily tell which are met and which aren't
	* In page settings view, you should be able to grab the title from an API function instead of manually typing it
	 
	
## New Code Checklist

* Security
	* Input Validation
	* Prepare any manual SQL queries
	* Output Sanitization
	* Nonces for all forms and AJAX requests
	* current_user_can()
* Add filters
* Write unit and integration tests
* Throw/catch exceptions


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