<?php
/**
 * Copy a directory from one location to another,
 * and include all subdirs and files.
 */
function copydir( $source, $destination )
{
	if( is_dir( $source ) )
	{
		@mkdir( $destination );

		$directory = dir( $source );

		while ( FALSE !== ( $readdirectory = $directory->read() ) )
		{
			if( $readdirectory == '.' || $readdirectory == '..' )
			{
				continue;
			}

			$PathDir = $source . '/' . $readdirectory; 

			if ( is_dir( $PathDir ) )
			{
				copydir( $PathDir, $destination . '/' . $readdirectory );

				continue;
			}

			copy( $PathDir, $destination . '/' . $readdirectory );
		}
 
		$directory->close();

	}
	else
	{
		copy( $source, $destination );
	}
}

/**
 * This version of the function adds a fourth parameter where filenames can 
 * be added to exclude them from being deleted. This is nice because you could
 * exclude index.html or .htaccess files from being deleted.
 */
function delete_files( $path, $del_dir = FALSE, $level = 0, $excluded = array() )
{
	// Trim the trailing slash
	$path = rtrim( $path, DIRECTORY_SEPARATOR );

	if( ! $current_dir = @opendir( $path ) )
	{
		return FALSE;
	}

	while( FALSE !== ( $filename = @readdir( $current_dir ) ) )
	{
		$excluded[] = '.';
		$excluded[] = '..';

		if( ! in_array( $filename, $excluded ) )
		{
			if( is_dir( $path . DIRECTORY_SEPARATOR . $filename ) )
			{
				// Ignore empty folders
				if( substr( $filename, 0, 1 ) != '.' )
				{
					delete_files( $path . DIRECTORY_SEPARATOR . $filename, $del_dir, $level + 1, $excluded );
				}
			}
			else
			{
				unlink( $path . DIRECTORY_SEPARATOR . $filename );
			}
		}
	}
	@closedir( $current_dir );

	if( $del_dir == TRUE AND $level > 0 )
	{
		return @rmdir( $path );
	}

	return TRUE;
}

/**
 * Get Filenames
 *
 * Reads the specified directory and builds an array containing the filenames.
 * Any sub-folders contained within the specified path are read as well.
 *
 * @access	public
 * @param	string	path to source
 * @param	bool	whether to include the path as part of the filename
 * @param   bool    whether to include the files in sub directories
 * @param	bool	internal variable to determine recursion status - do not use in calls
 * @return	array
 */
function get_filenames( $source_dir, $include_path = FALSE, $include_subdirs = TRUE, $_recursion = FALSE )
{
	static $_filedata = array();

	if( $fp = @opendir( $source_dir ) )
	{
		// reset the array and make sure $source_dir has a trailing slash on the initial call
		if( $_recursion === FALSE )
		{
			$_filedata = array();
			$source_dir = rtrim( realpath( $source_dir ), DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
		}

		while( FALSE !== ( $file = readdir( $fp ) ) )
		{
			// If a subdir
			if( @is_dir( $source_dir . $file ) && strncmp( $file, '.', 1 ) )
			{
				// If we want subdirs
				if( $include_subdirs === TRUE )
				{
					get_filenames( $source_dir . $file . DIRECTORY_SEPARATOR, $include_path, TRUE, TRUE );
				}
			}

			// else if a normal file
			else if( strncmp( $file, '.', 1 ) !== 0 )
			{
				$_filedata[] = ( $include_path == TRUE ) ? $source_dir . $file : $file;
			}
		}

		return $_filedata;
	}
	else
	{
		return FALSE;
	}
}

// --------------------------------------------------------------------