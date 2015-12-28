To Do List
==========

* Create a way to remove orphaned auth session records:

	DELETE a
	FROM auth_sessions a
	LEFT JOIN ci_sessions c
	ON  c.id = a.id
	WHERE c.id IS NULL
