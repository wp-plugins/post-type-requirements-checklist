=== Post Type Requirements Checklist ===
Contributors: dauidus
Author URI: http://dauid.us
Tags: requirements, require, required, requirement, publish, post type, metabox, wysiwyg, featured image, author, excerpt
Requires at least: 3.1
Tested up to: 4.1
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows admins to require content to be entered before a page/post can be published.

== Description ==

Post Type Requirements Checklist allows admins to require content to be entered before a page/post can be published.  Currently it supports requirements for the following areas on the add/edit screen:
** **
* title
* WYSIWYG editor
* featured image
* excerpt
* categories (allows for minimum required number of categories)
* tags (allows for minimum required number of tags)

PTRC uses OOP standards to add options only for those metaboxes which are supported for each post type and to execute code only on those pages where it is needed.  It works especially well for sites with many custom post types that require content to be entered in a specific way (ie. when a post type requires a specific page template or when the absence of a featured image will break the intended look of a post).  Think of any theme or plugin that supports an image slider powered by a required featured image, and you can surely see where PTRC can come in handy.

To be clear, PTRC does absolutely nothing to the front-end of your site.  It simply forces certain types of data to be added to the add/edit page/post admin screen in order for that content to be published or updated.

PTRC works with multisite networks and allows users to define settings on a per-site basis.

== Installation ==

Installation from zip:

1. From wp-admin interface, select Plugins -> Add New
2. Click Upload
3. Click "Choose File" and select post-type-requirements-checklist.zip
4. Click "Install Now"
5. Activate the plugin through the 'Plugins' menu in WordPress
6. Add instructive text from the `settings -> Post Type Requirements` admin page

Installation from WordPress Plugin Directory:

1. From wp-admin interface, go to Plugins -> Add New
2. Search for "Post Type Requirements Checklist"
3. Click Install Now under the plugin name
4. Click Ok to install the plugin
5. Click Activate Plugin once installed
6. Add instructive text from the `settings -> Post Type Requirements` admin page

== Frequently Asked Questions ==

= Does it support Multisite? =

Yes.  This plugin can be either network activated or activated individually for each site on a network.

= How can I delete all data associated with this plugin? =

Simply delete this plugin to remove all data associated with it.  Deactivating the plugin will keep all plugin data saved in the database, but will not remove it.

== Screenshots ==

1. Publishing/updating is disabled until all requirements are met.
2. Once requirements are met, the user can publish/update.

== Changelog ==

= 1.0.2 =
* hide requirement checklist for post types that don’t utilize it

= 1.0.1 =
* small change to checklist style

= 1.0 =
* initial release

== Upgrade Notice ==

= 1.0.1 =
Adds a small change to the checklist style that plays more nicely with text added to the publish meatball via other plugins.

= 1.0 =
initial release

