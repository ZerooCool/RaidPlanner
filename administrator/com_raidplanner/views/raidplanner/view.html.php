<?php
/*------------------------------------------------------------------------
# RaidPlanner View for RaidPlanner Component
# com_raidplanner - RaidPlanner Component
# ------------------------------------------------------------------------
# author    Taracque
# copyright Copyright (C) 2011 Taracque. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Website: http://www.taracque.hu/raidplanner
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

JHTML::stylesheet('raidplanner.css', 'administrator/components/com_raidplanner/assets/');

class RaidPlannerViewRaidPlanner extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the data

		JToolBarHelper::title( JText::_( 'COM_RAIDPLANNER' ) );
		JToolBarHelper::preferences( 'com_raidplanner' );

		$view = JRequest::getVar('view');

		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RAIDS'), 'index.php?option=com_raidplanner&view=raids', ($view == 'raids'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CHARACTERS'), 'index.php?option=com_raidplanner&view=characters', ($view == 'characters'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_GROUPS'), 'index.php?option=com_raidplanner&view=groups', ($view == 'groups'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_ROLES'), 'index.php?option=com_raidplanner&view=roles', ($view == 'roles'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_CLASSES'), 'index.php?option=com_raidplanner&view=classes', ($view == 'classes'));
		JSubMenuHelper::addEntry(JText::_('COM_RAIDPLANNER_RACES'), 'index.php?option=com_raidplanner&view=races', ($view == 'races'));

		parent::display($tpl);
	}
}