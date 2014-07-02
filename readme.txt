=== Grunion Ajax ===
Contributors: thenbrent
Tags: grunion contact form, grunion, ajax, jquery
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 1.3

Using Grunion Contact Form? Make form submission slick with Grunion Ajax.


== Description ==

Every time a page reloads to submit a form, a puppy dies. This plugin is the saviour of puppies.

Grunion Ajax submits a [Grunion Contact Form](http://wordpress.org/extend/plugins/grunion-contact-form/) using Ajax. It makes it super fast for visitors to submit a form.

The Grunion Ajax Javascript file is only loaded if the post or page in question includes the Grunion `[contact-form]` shortcode, so it's efficient too.

The only caveat, Ajax submission has not been tested for Grunion Forms included in a widget.


== Installation ==

You know the drill. 

1. Unzip and upload `/grunion-ajax/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Grunion Ajax will apply to any form added with the `[contact-form]` shortcode


== Changelog ==

= 1.3 =
Fix issues when using a contact forms on a page which includes a subquery, for something like Testimonials.

= 1.1 =
Fixing issues with full post content being displayed after form submission. Better error animations.

= 1.0 =
Page no longer being submitted if there has been an error when submitted the form is submitted and then the form is submitted again. Props Matthew Burrows from [Electric Studio](http://www.electricstudio.co.uk/).

= 0.2 =
Working around print_scripts issue in Grunion 2.3.

= 0.1 =
First version.

== Upgrade Notice ==

= 1.3 =
Upgrade to fix issues with contact forms on a page which include subqueries, for content like testimonials.

= 1.1 =
Upgrade to fix a bug which was duplicating post content after a form is submitted.

= 1.0 =
Please upgrade to prevent page refreshes when a form has previously been submitted and an error occurred in submission.

= 0.2 =
Please upgrade to fix an incompatibility issues with version 2.3 of Grunion Contact Form.
