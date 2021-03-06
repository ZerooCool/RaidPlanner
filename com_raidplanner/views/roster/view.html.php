<?php
/*------------------------------------------------------------------------
# Roster View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.view');
jimport( 'joomla.application.component.controller' );

/* create JViewLegacy if not exist */
if (!class_exists('JViewLegacy')) {
	class JViewLegacy extends JView {}
}

class RaidPlannerViewRoster extends JViewLegacy
{
	function display($tpl = null)
	{
		/* Load required javascripts */
		RaidPlannerHelper::loadJSFramework( true );
		JHTML::script('com_raidplanner/HtmlTable.Extended.js', false, true);

		$model = $this->getModel();
		$paramsObj = JComponentHelper::getParams( 'com_raidplanner' );
		$menuitemid = JRequest::getInt( 'Itemid' );
		if ($menuitemid)
		{
			$menu = JFactory::getApplication()->getMenu();
			$menuparams = $menu->getParams( $menuitemid );
			$paramsObj->merge( $menuparams );
		}

		$guild_id = $paramsObj->get('guild_id', '0');

		$show_account = $paramsObj->get('show_account', '0');
		$initial_sort = $paramsObj->get('initial_sort', '0');

		$guild_plugin = RaidPlannerHelper::getGuildPlugin( $guild_id );
		
		if ($guild_plugin != null)
		{
			$sync_interval = $paramsObj->get( 'sync_interval', 4 );
			$sync_enabled = ($paramsObj->get('armory_sync', '0') == 1);
		
			if ($sync_enabled )
			{
				$guild_plugin->trigger( 'onRPSyncGuild', array( $guild_id, $sync_interval, false ) );
			}

			$guild_plugin->trigger( 'onRPLoadCSS' );
		}

		$this->assignRef( 'guild_plugin', $guild_plugin );
		$this->assign( 'characters', $model->getGuildCharacters( $guild_id ) );
		$this->assign( 'guildinfo', $model->getGuildInfo( $guild_id ) );
		$this->assign( 'ranks', RaidPlannerHelper::getRanks() );
		$this->assignRef( 'show_account', $show_account );
		$this->assignRef( 'initial_sort', $initial_sort );

		parent::display($tpl);
	}

}