<?php
/**
*
* @package phpBB Extension - Total edited posts
* @copyright (c) 2015 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\totaleditedposts\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;
	/**
	* Constructor
	* @param \phpbb\template\template			$template
	* @param \phpbb\user						$user
	* @param \phpbb\db\driver\driver_interface	$db
	*
	*/
	public function __construct(\phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db)
	{
		$this->template = $template;
		$this->user = $user;
		$this->db = $db;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'	=> 'page_header',
		);
	}

	public function page_header($event)
	{
		$this->user->add_lang_ext('dmzx/totaleditedposts', 'common');

		$sql = 'SELECT SUM(post_edit_count) AS count
			FROM ' . POSTS_TABLE;
		$result = $this->db->sql_query($sql);
		$total_edit_post_count = (int) $this->db->sql_fetchfield('count');

		$this->template->assign_vars(array(
			'TOTAL_EDIT_POST'	=> $this->user->lang['TOTAL_EDIT_POST'] . ' <strong>' . number_format($total_edit_post_count) . '</strong>',
		));
	}
}