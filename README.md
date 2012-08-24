# WordPress-Plugin-Skeleton

The skeleton for an object-oriented/MVC WordPress plugin.


## Features

* Minimal, clean and organized
* Sample classes
	* Custom post type and taxonomies
	* User Settings
	* WP-Cron
* Single blog and network-wide activation
* Plugin options
* Upgrade routine
* CSS/JavaScript enqueing

## Notes

* I decided to not internationalize the skeleton because most of my clients don't need it and having to put all regular text in PHP strings annoys me. If you're distributing the plugin, though, then you should internationalize it.
* I prefer having controllers and models in the same class/file, so I use an unofficial @mvc tag in the phpDoc comments to mentally keep track of which methods are controllers and which are models. 


## Installation

* cd /var/www/vhosts/example.com/content/plugins
* git clone git://github.com/iandunn/WordPress-Plugin-Skeleton.git plugin-slug
* cd plugin-slug
* git remote rm origin
* git rm README.md
* Find/replace class names/slugs
* Rename files to match class slugs
* Rename files you don't think you'll to to _[original-name]. Delete them before pushing to production if you don't end up using them.
* Rename generic comments, method names, etc to describe their specific implementations. 
* Update bootstrap.php headers
* If you're not using a custom post type or something else that updates the rewrite rules, you can remove the flush_rewrite_rules() call in WordPresPluginSkeleton::activate() and ::deactivate().


## TODO

* High Priority
	* Bug - notices inside WordPressPluginSkeleton::init() never get cleared when viewing cpt page, but they mostly do on dashboard
	* Add more sample classes, then add to features
		* Custom menu pages w/ options. Add to Settings class
		* AJAX. Not really its own class, so maybe just add to CPT
		* Shortcodes. Not really its own class, so maybe just add to CPT
		* Widgets
		* Look at BGMP and other past plugins for ideas
	* Javascript
		* Register event handlers
		* Add AJAX calls
	* Add data validation to options, etc 
	* Add sanization/escaping, then add as feature
	* Add filters to everything, then add as feature
	
* Medium Priority
	* Add db upgrade logic
	* Support for conditionally loading js/css
	* Add to notes section for any non-standard things or anything that needs explaining
	* Add exceptions. Add try/catch blocks to all hook callbooks, but nowhere else
	* Add extra error checking/handling when calling API functions (e.g., register_post_type() )
	* Update Features w/ any other advantages
	* Throw/catch exceptions in places. Maybe just in action/filter callbacks, since everything should bubble up to them
	* CPT meta boxes - use get_current_screen() instead of global $post
	* Add network-wide deactivation? Or is that done automatically?
	* BGMP addFeaturedImageSupport() ?
	
* Low Priority
	* Better singular/plural handling for custom post type names
	* Maybe use a single view file for all meta boxes (within a class), rather than multiple. Switch on the box id just like the callback does.
	
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