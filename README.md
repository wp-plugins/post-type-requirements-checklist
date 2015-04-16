Post Type Requirements Checklist
============================

== Tags ==
requirements, require, required, requirement, publish, post type, metabox, wysiwyg, featured image, author, excerpt

** **

== Tested up to: ==
4.2-alpha

** **

== Description ==

Post Type Requirements Checklist allows admins to require content to be entered before a page/post can be published.  Currently it supports requirements for the following areas on the add/edit screen:
* title
* WYSIWYG editor
* featured image
* excerpt
* categories (allows for minimum required number of categories)
* tags (allows for minimum required number of tags)
* up to 5 custom taxonomies per post type (allows for minimum required number of categories/tags, as detailed above)

PTRC uses OOP standards to add options only for those metaboxes which are supported for each post type and to execute code only on those pages where it is needed.  It works especially well for sites with many custom post types that require content to be entered in a specific way (ie. when a post type requires a specific page template or when the absence of a featured image will break the intended look of a post).  Think of any theme or plugin that supports an image slider powered by a required featured image, and you can surely see where PTRC can come in handy.

To be clear, PTRC does absolutely nothing to the front-end of your site.  It simply forces certain types of data to be added to the add/edit page/post admin screen in order for that content to be published or updated.

PTRC works with multisite networks and allows users to define settings on a per-site basis.

= Suggestions are welcome =
* email the author at dave@dauid.us

** **

== Frequently Asked Questions ==

= Does it support Multisite? =

Yes.  This plugin can be either network activated or activated individually for each site on a network.

= How can I delete all data associated with this plugin? =

Simply delete this plugin to remove all data associated with it.  Deactivating the plugin will keep all plugin data saved in the database, but will not remove it.

** **

== Changelog ==

= 2.0 =
* major release with new features
* add support for custom taxonomies (up to 5 per post type)
* rewrite some plugin logic for scalability
* slight changes to checklist style for readability
* slight changes to settings page style

= 1.0.2 =
* hide requirement checklist for post types that don’t utilize it

= 1.0.1 =
* small change to checklist style

= 1.0 =
* initial release

** **

== Upgrade Notice ==

= 2.0 =
Major release with new features.  Users are strongly urged to update.

= 1.0.1 =
Adds a small change to the checklist style that plays more nicely with text added to the publish metabox via other plugins.

= 1.0 =
initial release

