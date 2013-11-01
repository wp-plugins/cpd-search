=== CPD Search ===
Contributors: rossigee
Tags: commercial, property, database, search, office, shop, restaurant, retail, industrial, warehouse
Requires at least: 3.6.1
Tested up to: 3.6.1
Stable tag: 1.7.2

Allows you to add an extensive UK commercial property search facility to your website.

== Description ==

This plugin provides shortcodes and widgets that embed search forms into your WP posts or pages, and handlers that connect to CPD's extensive database of UK commercial property.

== Installation ==

After installation, see further instructions on configuration page (admin area, 'Settings -> CPD Search').

== Changelog ==

= 1.7.2 =
* Move JS files alongside their PHP equivalents for ease-of-navigation.

= 1.7.1 =
* Flatten 'cpd-search-options' into multiple 'cpd_*' options.

= 1.7.0 =
* Convert SOAP API calls to REST API.

= 1.6.7 =
* Make use of REST call to retrieve CPD sectors list.

= 1.6.6 =
* Add automatic detection of agent ref, display of token information in plugin settings.

= 1.6.5 =
* Fix for detection of AuthenticationFailedExceptionMsg.

= 1.6.4 =
* Include 'EPC' field in output.

= 1.6.3 =
* Fix for search by units other than metres treating figures provided as metres.

= 1.6.2 =
* Provide setting to allow 'terms and conditions' link to be customised.

= 1.6.1 =
* Provide setting to allow PDFs to be viewed without needing the user to register.

= 1.6.0 =
* Use application token instead of agent password for agent authentication.

= 1.4.7 =
* Fix to make use of sectors appropriate to authenticated agent.

= 1.4.6 =
* Fix bug where registering user with an empty context.

= 1.4.5 =
* Fix some bugs related to auto-submitting of searches.

= 1.4.4 =
* Fix bug conflicting with 'submit' button of admin login.

= 1.4.3 =
* Fix ambiguous jQuery selector which accidentally overrode the admin login submit button.

= 1.4.2 =
* Fix sneaky post-release bug (including wrong template).

= 1.4.1 =
* Fixed regressions.

= 1.4.0 =
* Convert to OO/classes to improve extensibility.

= 1.2.8 =
* Pass ServiceContext in RegisterUser request.

= 1.2.7 =
* Allow for ServiceContext to be customisable per site.

= 1.2.6 =
* Update to work with JQuery in WordPress 3.4
* Further improvements to clipboard code.
* Add ability for admin user to create pages from search results

= 1.2.5 =
* Fix CSS layout issue.

= 1.2.4 =
* Fix bugs related to page navigation.
* Add initial search form sidebar widget.
* Add beta code for putting results into a clipboard.

= 1.2.3 =
* Fix bugs related to page navigation.

= 1.2.2 =
* Re-release w/version number fixed in plugin.

= 1.2.1 =
* Fix issues with paging and registering interest on current instructions results.

= 1.2.0 =
* First public release.

= 1.1.1 =
* Add support for user registration and 'register interest' facility.
* Switch to new CPD area ids.
* Add 'development' mode for quicker testing of templates etc.

= 1.1.0 =
* Version with separate tags for Google Map search 

= 1.0.0 =
* Initial version.
