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
* Comment out references to classes that aren't needed now. Delete ones that will never be needed.
* Rename generic comments, method names, etc to describe their specific implementations. 
* If you're not using a custom post type or something else that updates the rewrite rules, you can comment out the flush_rewrite_rules() call in WordPresPluginSkeleton::activate() and ::deactivate().
* For unit testing, install [SimpleTest for WordPress](http://wordpress.org/extend/plugins/simpletest-for-wordpress/) and use a shortcode like this: [simpletest name="WordPress Plugin Skeleton Unit Test Suite" path="/wordpress-plugin-skeleton/tests/wpps-test-suite.php"]


## TODO


* Next / In Progress
	* Refactor classes as extending abstract base module class
		* Static methods are great for methods that take input, return output and never affect a global state. Ones that always return same output given same input. But they should never be used if they affect a global state.
			Scan each function in the modules to see if it should really be static		
				* settings validatorfieldoptoins
		update phpdoc for upgrade/actiavet b/c of new var
		* activate/deactivte/singleactive have to be non-static b/c of calling modules
			see sweatshop warning plugin for solution
			activate new site pass networkwide
			updatesettings non-static, but maybe going away anyway
		* test activate/deactive
		* foreach( $this->modules as $module ) should do "as &$module", or does that happen automatically? 
		
	* Is making WPPSSettings::$settings public the right way to share it across classes? Maybe use magic getters instead with readonly?
		Yeah, just make it a protected var with w/ readable through __get().
			main class can use $this->modules[settings]->settings
			other modules can use $settings = settings::getinstance->settings
		replace updatesettings() with __set().
			if(property==settings) update_option(...)
		unit test $foo->settings = array() and $foo->settings['field'] = 'string'
		have validatefieldvalues() take indiviaul attribute like isvalid does?
		remove note about how it should be considered readonly? probably
	
	* Checkout master, merge abstract
	* Tag 0.2 and push tags to origin
	* post followup on php-49
		if anyone's curious, after more research made these changes
		converted all classes to singletons (except the non-static one, which is meant for stuff that doesn't interact w/ api)
		created abstract "module" class that all other classes extend
		created interface for custom post types
		check out code on github if interested
	
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
	
	
## New Code Checklist

* Security
	* Input validation for domain correctness and security.
	* Pass any manual SQL queries through $wpdb->prepare().
	* Output sanitization when sending untrusted data to browser. All data should be considered untrusted.
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