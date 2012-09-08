# WordPress-Plugin-Skeleton

The skeleton for an object-oriented/MVC WordPress plugin.


## Features

* Minimal, clean and organized
* Sample classes
	* Custom post type and taxonomies
	* Plugin settings
	* Extra user profile fields
	* WP-Cron jobs
* Single and network-wide activation
* Upgrade routine
* CSS/JavaScript enqueing

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


## TODO

* High Priority
	* Ask other devs for feedback on most things being static, general OO design, etc
		* add #note about the static methods
			reference php-49 list archive and replies
			http://stackoverflow.com/questions/2470552/is-there-anything-wrong-with-a-class-with-all-static-methods
	* Add unit tests
		* add #note linking to simpletest wp plugin
	* Add more sample classes, then add to features
		* AJAX. Not really its own class, so maybe just add to CPT
		* Shortcodes. Not really its own class, so maybe just add to CPT
		* Look at BGMP and other past plugins for ideas
		* Widgets
	* Javascript
		* Register event handlers
		* Add AJAX calls
		* Example of filters/hooks once WP settles how those will be handeled (see comments on http://www.meetup.com/SeattleWordPressMeetup/events/76033072/)
	* Add data validation to user options
		* Add domain-level validation (verify type, format, whitelist values, etc)
	* Add validation/sanization everywhere, then add as feature
	* Add filters to everything, then add as feature
	* Bug - cron job not scheduled under WPMS 3.4.1, but works fine on single install. Maybe related to WPMS cron bugs, see Trac tickets.
	
* Medium Priority
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
	* Maybe make $notices public in main class, and have others call it, instead of each class creating its own reference
	* Is making WPPSSettings::$settings public the right way to share it across classes? Maybe use magic getters instead?
	* Use API functions in WPPSCustomPostType::savePost() instead of accessing $post directly
	* Make sure all hook callbacks have all of their parameters declared, even if you don't typically use them
	
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