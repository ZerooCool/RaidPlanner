<?php
 /**
  * @version		
  * @copyright	Copyright (C) 2011 Taracque. All rights reserved.
  * @license	GNU General Public License version 2 or later; see LICENSE.txt
  */

defined('JPATH_BASE') or die('Restricted access');

jimport('joomla.form.formfield');

JLoader::register('RaidPlannerHelper', JPATH_ADMINISTRATOR . '/components/com_raidplanner/helper.php' );

 /**
  * Custom JForm field for Character editor
  */

class JFormFieldRPCharacterEditor extends JFormField {

	protected $type = 'RPCharacterEditor';
	
	public function getInput() {

		// Load the javascript
		JHtml::_('behavior.framework');
		JHtml::_('behavior.modal', 'a.open-modal');

		// Build the script.
		$script = array();
		$script[] = '';
		$script[] = '	function jRecalCharacterValue_'.$this->id.'() {';
		$script[] = '		var ul = document.id("rp_characterEditorList_' . $this->id . '");';
		$script[] = '		var val = "";';
		$script[] = '		ul.getChildren("li").each(function(li){';
		$script[] = '			if (li.get("id") && (li.get("id") != "rp_characterEditorField_' . $this->id . '_0") && (li.getChildren("a")[0].get("text") != "") ) {';
		$script[] = '				if (li.getChildren("input")[0].get("value")) {';
		$script[] = '					val = val + li.getChildren("input")[0].get("value") + ":";';
		$script[] = '				}';
		$script[] = '				val = val + li.getChildren("a")[0].get("text") + ";";';
		$script[] = '			}';
		$script[] = '		})';
		$script[] = '		document.id("rp_characterEditorValue_' . $this->id . '").set("value", val );';
		$script[] = '	}';
		$script[] = '';
		$script[] = '	function jSelectCharacter_'.$this->id.'(idx, char_id, char_name) {';
		$script[] = '		if (char_name) {';
		$script[] = '			var line = document.id( "rp_characterEditorField_' . $this->id . '_" + idx );';
		$script[] = '			if (idx==0) {';
		$script[] = '				var ul = line.getParent("ul");';
		$script[] = '				ul.getChildren("li").each(function(li){';
		$script[] = '					if ( (li) && (li.get("id")) ) {';
		$script[] = '						if(li.get("id").replace("rp_characterEditorField_' . $this->id . '_","")*1>idx) idx=li.get("id").replace("rp_characterEditorField_' . $this->id . '_","")*1;';
		$script[] = '						if(li.get("id") == "rp_characterEditorField_' . $this->id . '_0") {';
		$script[] = '							line = li.clone().setStyle("display","block");';
		$script[] = '						}';
		$script[] = '					}';
		$script[] = '				})';
		$script[] = '				idx = Number(idx) + 1;';
		$script[] = '				line.set("id","rp_characterEditorField_' . $this->id . '_" + (idx));';
		$script[] = '				ul.grab(line,"top");';
		$script[] = '				if (SqueezeBox) {';
		$script[] = '					SqueezeBox.assign(line.getChildren("a")[0],{parse:"rel"});';
		$script[] = '				}';
		$script[] = '			}';
		$script[] = '			line.getChildren("a")[0].set("text",char_name);';
		$script[] = '			line.getChildren("a")[0].set("href","' . JURI::root() . 'index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_'.$this->id.'&amp;character=" + char_name + "&amp;char_id=" + char_id + "&amp;fieldidx=" + idx );';
		$script[] = '			line.getChildren("input")[0].set("value",char_id);';
		$script[] = '			jRecalCharacterValue_'.$this->id.'();';
		$script[] = '		}';
		$script[] = '		SqueezeBox.close();';
		$script[] = '	}';

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
	
		/* replace various possible separators to \n */
		$chars = RaidPlannerHelper::getProfileChars( $this->value, true, true );

		$html = '<input type="hidden" name="' . $this->name. '" value="' . htmlspecialchars ( $this->value, ENT_COMPAT, 'UTF-8') . '" id="rp_characterEditorValue_' . $this->id . '" />';
		$html .= '<div style="width:' . $this->element['cols'] . 'em;height:' . ($this->element['rows'] * 2) . 'em;overflow-y:auto;overflow-x:hidden;border: 1px inset gray;">';
		$html .= '<ul style="display:block;float:left;clear:left;width:100%;padding:0;margin:0;" id="rp_characterEditorList_' . $this->id . '">';
		$idx = 0;

		$html .= '<li style="display:none;float:left;clear:left;width:100%;padding:0;border-bottom:1px solid gray;" id="rp_characterEditorField_' . $this->id . '_0">';
		$html .= '<img src="' . JURI::root() . 'media/com_raidplanner/images/delete.png" alt="' . JText::_('JACTION_DELETE') . '" onclick="this.getParent(\'li\').dispose();" style="float:right;margin:0;" />';
		$html .= '<a class="open-modal" href="" rel="{handler: \'iframe\', size: {x: 450, y: 300}}"></a>';
		$html .= '<input type="hidden" value="" />';
		$html .= '</li>';
		
		foreach ($chars as $char)
		{
			$idx++;
		
			$link = JURI::root() . 'index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_'.$this->id.'&amp;character=' . htmlspecialchars( $char['char_name'], ENT_COMPAT, 'UTF-8') . '&amp;char_id=' . $char['char_id'] . '&amp;fieldidx=' . $idx;

			$html .= '<li style="display:block;float:left;clear:left;width:100%;padding:0;border-bottom:1px solid gray;" id="rp_characterEditorField_' . $this->id . '_' . $idx . '">';
			$html .= '<img src="' . JURI::root() . 'media/com_raidplanner/images/delete.png" alt="' . JText::_('JACTION_DELETE') . '" onclick="this.getParent(\'li\').dispose();jRecalCharacterValue_'.$this->id.'();" style="float:right;margin:0;" />';
			$html .= '<a class="open-modal" href="' . $link . '" rel="{handler: \'iframe\', size: {x: 450, y: 300}}">' . $char['char_name'] . '</a>';
			if ($char['guild_name']!='') {
				$html .= '<span> &lsaquo;' . $char['guild_name'] . '&rsaquo;</span>';
			}
			$html .= '<input type="hidden" value="' . $char['char_id'] . '" />';
			$html .= '</li>';
		}
		$link = JURI::root() . 'index.php?option=com_raidplanner&amp;view=character&amp;layout=modal&amp;tmpl=component&amp;function=jSelectCharacter_'.$this->id.'&amp;character=&amp;fieldidx=';
		$html .= '<li style="display:block;float:left;clear:left;width:100%;"><a class="open-modal" rel="{handler: \'iframe\', size: {x: 450, y: 300}}" href="' . $link . '"><img src="' . JURI::root() . 'media/com_raidplanner/images/new.png" alt="' . JText::_('JACTION_NEW') . '" style="margin:0;" /> '. JText::_('PLG_USER_RAIDPLANNER_ADD_NEW_CHARACTER') . '</a></li>';

		$html .= '</ul>';
		$html .= '</div>';
		return $html;
	}

}

 /**
  * Custom profile plugin for RaidPlanner Component.
  *
  * @package		RaidPlanner.Plugins
  * @subpackage	user.profile
  * @version		1.6
  */

class plgUserRaidPlanner extends JPlugin
{
	/**
	 * @param	string	The context for the data
	 * @param	int		The user id
	 * @param	object
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile','com_users.registration','com_users.user','com_admin.profile' ))){
			return true;
		}

		if (is_object($data)) {
			$userId = isset($data->id) ? $data->id : 0;
			if ($userId) {
				$juser =JFactory::getUser($userId);
				if ($this->params->get('raidplanner-profile-group', 1) == 0)
				{
					$data_key = 'params';
				} else {
					$data_key = 'raidplanner';
				}
	
				$data->$data_key = array(
					'characters' => $juser->getParam('characters', ''),
					'calendar_secret' => $juser->getParam('calendar_secret', ''),
					'vacation' => $juser->getParam('vacation', '')
				);
	
			}
			if (!JHtml::isRegistered('users.characters')) {
				JHtml::register('users.characters', array(__CLASS__, 'characters'));
			}
		}

		return true;
	}

	public static function characters($value)
	{
		if ($value) {
			$chars = RaidPlannerHelper::getProfileChars( $value, true, true );
			$ret = '';
			foreach ($chars as $char) {
				$ret .= '<span class="' . $char['class_css'] . ' ' . $char['race_css'] . '">' . $char['char_name'] . '<span>';
				if ($char['guild_name']!='') {
					$ret .= ' &lsaquo;' . $char['guild_name'] . '&rsaquo;';
				}
				$ret .= "\n";
			}
			$ret = str_replace( "\n" , "; " , trim($ret) );

			return $ret;
		} else {
			return "";
		}
	}

	/**
	 * @param	JForm	The form to be altered.
	 * @param	array	The associated data for the form.
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareForm($form, $data)
	{
		// Load user_profile plugin language
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_raidplanner', JPATH_ADMINISTRATOR);

		if (!($form instanceof JForm)) {
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}
		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_users.profile', 'com_users.registration','com_users.user','com_admin.profile'))) {
			return true;
		}

		// Add the profile fields to the form.
		JForm::addFormPath(dirname(__FILE__).'/profiles');
		$profile_prefix = '';
		if ($this->params->get('raidplanner-profile-group', 1) == 0)
		{
			$profile_prefix = 'params_';
		}
		if ($this->params->get('enable-character-editor', 0) == 1)
		{
			$form->loadFile( $profile_prefix . 'profile_editor', false);
		} else {
			$form->loadFile( $profile_prefix . 'profile', false);
		}

		if ($form->getName()=='com_users.profile')
		{

			// Toggle whether the something field is required.
			if ($this->params->get('profile-require_raidplanner', 1) > 0) {
				$form->setFieldAttribute('characters', 'required', false, 'profile');
				$form->setFieldAttribute('calendar_secret', 'required', false, 'profile');
				$form->setFieldAttribute('vacation', 'required', false, 'profile');
			} else {
				$form->removeField('characters', 'profile');
				$form->removeField('calendar_secret', 'profile');
				$form->removeField('vacation', 'profile');
			}
		}

		//In this example, we treat the frontend registration and the back end user create or edit as the same.
		elseif ($form->getName()=='com_users.registration' || $form->getName()=='com_users.user' )
		{		
			if ($this->params->get('raidplanner-profile-group', 1) == 0)
			{
				$profile_prefix = 'params';
			} else {
				$profile_prefix = 'profile';
			}
			// Toggle whether the characters field is required.
			if ($this->params->get('register-require_raidplanner', 1) > 0) {
				$form->setFieldAttribute('characters', 'required', false, $profile_prefix);
				$form->setFieldAttribute('calendar_secret', 'required', false, $profile_prefix);
				$form->setFieldAttribute('vacation', 'required', false, $profile_prefix);
			} elseif ($form->getName()=='com_users.registration') {	//registration form
				$form->removeField('characters', $profile_prefix);
				$form->removeField('calendar_secret', $profile_prefix);
				$form->removeField('vacation', $profile_prefix);
			}
		}
	}

	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');
		if ($this->params->get('raidplanner-profile-group', 1) == 0)
		{
			$data_key = 'params';
		} else {
			$data_key = 'raidplanner';
		}
		if ($userId && $result && isset($data[$data_key]) && (count($data[$data_key])))
		{
			try
			{
				$juser =JFactory::getUser($userId);
				$params = $juser->getParameters(false)->toObject();

				if ( $data_key == 'params')
				{
					$data_arr = json_decode($data[$data_key], true);
				} else {
					$data_arr = $data[$data_key];
				}

				foreach ($data_arr as $k => $v) {
					if (in_array($k, array('characters', 'calendar_secret', 'vacation')))
					{
						$juser->setParam($k, $v);
						$params->$k = $v;
					}
				}

				$table = $juser->getTable();
				$table->load($juser->id);
				$juser->params = json_encode($params);
/*				$table->bind($juser->getProperties()); */
				$table->store();

				if ( (isset($data_arr['characters'])) && ($this->params->get('enable-character-editor', 0) == 1) )
				{
					$db	=JFactory::getDBO();
					$query = 'UPDATE #__raidplanner_character SET profile_id=-profile_id WHERE profile_id='. $userId;
					$db->setQuery($query);
					$db->query();
					
					$chars = RaidPlannerHelper::getProfileChars( $data_arr['characters'] );
					foreach ($chars as $char)
					{
						if ($char['char_id']=='') {
							$query = "UPDATE #__raidplanner_character SET profile_id=".$userId." WHERE char_name='". $db->escape( $char['char_name'] ) ."'";
						} else {
							$query = "UPDATE #__raidplanner_character SET profile_id=".$userId." WHERE character_id=" . intval($char['char_id']) ." AND char_name='". $db->escape( $char['char_name'] ) ."'";
						}
						$db->setQuery($query);
						$db->query();
					}

					$query = 'DELETE FROM #__raidplanner_character WHERE profile_id=-'. $userId;
					$db->setQuery($query);
					$db->query();

				}
			}
			catch (JException $e) {
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}
		return true;
	}

}
