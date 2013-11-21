=== CPD Search ===
Contributors: rossigee
Tags: commercial, property, database, search, office, shop, restaurant, retail, industrial, warehouse
Requires at least: 3.6
Tested up to: 3.7.1
Stable tag: 3.0.8

Thin layer to provide custom themes and plugins with access to CPD's commercial property database.

== Description ==

Acts as a thin layer between your UK commercial property estate agent's WordPress theme, and CPD's powerful commercial property search engine, with comprehensive details of the latest properties across the UK. In effect, this plugin allows your developers to add an extensive UK commercial property search facility to your website. Designed as a simple set of PHP classes, and an AJAX/JSON handler to help your page handlers capture and process your visitor's search criteria, results, contact details and their clipboard. The end goal is that an e-mail is sent to you and your visitor, containing the short-list of properties they are interested in.

== Installation ==

You need to develop or customise your WordPress theme with custom 'page-*.php' pages that handle the functionality required in your particular use case, but having those pages make calls to the CPD REST API, via the utility functions provided by this plugin.

In short, you need to create at least one custom page each for your property search form, search results, details view, and clipboard results view. More detailed documentation is forthcoming, along with a demonstration theme that you can download and play with to see how it works, and use in whole or part to augment similar functionality in your own theme.

Be sure to put a valid CPD application token into the 'CPD Search' configuration page found in the WordPress admin area, until 'Settings -> 'CPD Search'.

Support available by e-mail <support at cpd.co.uk>

== Changelog ==

= 3.0.8 =
* Drop stray empty lines from PHP files, causing 'header output already started' warning.

= 3.0.7 =
* Add functions to add/remove properties from SESSION-based shortlist.

= 3.0.6 =
* Recognise 405 status when agents 'visitor' flag is not set, raise exception.

= 3.0.5 =
* Update 'cpd_search_service_context' to a static class method.

= 3.0.4 =
* Typofix.

= 3.0.3 =
* Rename plugin from 'cpd-search-lite' to 'cpd-search'.

= 3.0.2 =
* Replace 'CPDAjax' global JS with 'CPDSearchConfig'.

= 3.0.1 =
* Various bugfixes from in-house testing.

= 3.0.0 =
* Switch functions wrapped from older SOAP API to use our newer REST API.

= 2.0.2 =
* Drop blank line at end of file causing session header problems.

= 2.0.1 =
* Initial version. Re-write, avoiding lessons learned developing/deploying v1.
