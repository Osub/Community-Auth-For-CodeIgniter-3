To Do List
==========

* Docs for AJAX logins

* Blog post showing enhanced form validation removed and put in new repo as of 2/12/2016

* Blog post showing url helper removed and put in new repo as of 2/12/2016

* Blog post and/or docs to show ACL implementation:
	The idea here is that an interface needs to be created to manage permissions.
	Each permission has a category name, and an action name.
	An interface would be created for user management, giving the admin the 
	ability to designate a user to have or not have permissions to take certain
	actions. When a user logs in, as long as "add_acl_to_auth_vars" is TRUE in the 
	authentication config, the user's specific ACL values are added 
	to the authentication variables, giving the dev a chance to check if 
	the user should have permission to take a specific action.
	The ACL permissions in the authentication variables are added to an array, 
	where the category name and action name are joined with a period. 
	So for instance, if you have a category named "general", and an action 
	named "view_reports", and if the logged in user should be able to take that
	action, their ACL array would contain "general.view_reports".

	The whole idea of using an ACL allows for a finer control over users. In 
	my experience there are times when role based authentication can get messy 
	because there is a lack of user based ACL, and Community Auth needs it 
	to keep things clean and organized in larger sites.

	Because no ACL interface has been implemented, this really gives the developer 
	a chance to customize the way it is used. For instance, if by default a user 
	should have permission to do everything in a ACL category, all they may 
	need is an ACL record "general.*". This kind of implementation could keep
	the database super lean, and checking for permission as simple as:

		if( $this->permit('general.view_reports') )
			// ... Do something ...

	BTW, there is currently no permit function in the Auth Controller. Don't worry, 
	it's coming soon. In that permit function, if the user's ACL has "general.*", 
	then the permit function would return TRUE.