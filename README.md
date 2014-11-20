wp-admin-options
================

This is a wordpress admin option development framework, help devlopers to code more fast, and focus on front end.

It's a easy way to set admin options in dashboard, and use in front end. Like MVC framework, Admin Options has three parts:
1. menus, like controller
2. view, to print in admin dashboard
3. hooks and functions, to do actions and to use in theme php files

Write your own options updating programs? No, just give name="option[key]" in view file, you can use this key. For example:

<input type=text name=option[page_to_show]>

then get_admin_options('page_to_show');
or more easy

global $admin_options;
$admin_opitons['page_to_show'];

use update_admin_options(key,value);

to use media management in admin just do_action('admin_options_media_dialog');

so easy!
