/**
 * Class Presentation on XSS
 *
 * @package		XSS
 * @version		0.1
 * @author		Juan "JayCreations" Hernandez
 * @license		MIT
 * @copyright		2011 Juan J. Hernandez
 * @link
 */

// Hide the add comment.
$(document).ready(function()
{
	$('#add_comment').hide();

	// Show it if clicked on.
	$('#add').click(function()
	{
		$('#add_comment').toggle('slow');
	});
});