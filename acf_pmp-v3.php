<?php

class acf_field_pmp extends acf_Field {
	/**
	 * Constructor
	 * - This function is called when the field class is initalized on each page.
	 * - Here you can add filters / actions and setup any other functionality for your field
	 *
	 * @author Elliot Condon
	 * @since 2.2.0
	 */
	function __construct($parent) {
		// do not delete!
		parent::__construct($parent);

		// set name / title
		$this->name = 'acf_pmp'; // variable name (no spaces / special characters / etc)
		$this->title = __("Paid Memberships Pro Level", 'acf'); // field label (Displayed in edit screens)
	}

	/**
	 * - this function is called from core/field_meta_box.php to create extra options
	 * for your field
	 *
	 * @params
	 * - $key (int) - the $_POST obejct key required to save the options to the field
	 * - $field (array) - the field object
	 *
	 * @author Elliot Condon
	 * @since 2.2.0
	 */
	function create_options($key, $field) {
		// role_capability
		// defaults
		$field['multiple'] = isset($field['multiple']) ? $field['multiple'] : '0';
		$field['allow_null'] = isset($field['allow_null']) ? $field['allow_null'] : '0';
		$field['disable'] = isset($field['disable']) ? $field['disable'] : '0';
		$field['hide_disabled'] = isset($field['hide_disabled']) ? $field['hide_disabled'] : '0';
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Disabled forms:", 'acf'); ?></label>
				<p class="description"><?php _e("You can not select these forms", 'acf'); ?></p>
			</td>
			<td>
		<?php
		//Get level names
		$levels = $this->get_all_pmp_levels ();
		$choices = array();
		$choices[0] = '---';
		$k = 1;
		foreach ($levels as $level) {
			$choices[$k] = $level->name;
			$k++;
		}
		$this->parent->create_field(array(
			'type' => 'select',
			'name' => 'fields[' . $key . '][disable]',
			'value' => $field['disable'],
			'multiple' => '1',
			'allow_null' => '0',
			'choices' => $choices,
			'layout' => 'horizontal',
		));
		?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
		        <label><?php _e("Allow Null?", 'acf'); ?></label>
			</td>
			<td>
		<?php
		$this->parent->create_field(array(
			'type' => 'radio',
			'name' => 'fields[' . $key . '][allow_null]',
			'value' => $field['allow_null'],
			'choices' => array(
				'1' => 'Yes',
				'0' => 'No',
			),
			'layout' => 'horizontal',
		));
		?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
		        <label><?php _e("Select multiple forms?", 'acf'); ?></label>
			</td>
			<td>
		<?php
		$this->parent->create_field(array(
			'type' => 'radio',
			'name' => 'fields[' . $key . '][multiple]',
			'value' => $field['multiple'],
			'choices' => array(
				'1' => 'Yes',
				'0' => 'No',
			),
			'layout' => 'horizontal',
		));
		?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
		        <label><?php _e("Hide disabled forms?", 'acf'); ?></label>
			</td>
			<td>
		<?php
		$this->parent->create_field(array(
			'type' => 'radio',
			'name' => 'fields[' . $key . '][hide_disabled]',
			'value' => $field['hide_disabled'],
			'choices' => array(
				'1' => 'Yes',
				'0' => 'No',
			),
			'layout' => 'horizontal',
		));
		?>
			</td>
		</tr>

		<?php
	}

	/**
	 * - this function is called when saving your acf object. Here you can manipulate the
	 * field object and it's options before it gets saved to the database.
	 *
	 * @author Elliot Condon
	 * @since 2.2.0
	 */
	function pre_save_field($field) {
		// do stuff with field (mostly format options data)

		return parent::pre_save_field($field);
	}

	/**
	 * - this function is called on edit screens to produce the html for this field
	 *
	 * @author Elliot Condon
	 * @since 2.2.0
	 */
	function create_field($field) {

		$field['multiple'] = isset($field['multiple']) ? $field['multiple'] : false;
		$field['disable'] = isset($field['disable']) ? $field['disable'] : false;
		$field['hide_disabled'] = isset($field['hide_disabled']) ? $field['hide_disabled'] : false;

		// Add multiple select functionality as required
		$multiple = '';
		if ($field['multiple'] == '1') {
			$multiple = ' multiple="multiple" size="5" ';
			$field['name'] .= '[]';
		}

		// Begin HTML select field
		echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';

		// Add null value as required
		if ($field['allow_null'] == '1') {
			echo '<option value="null"> - Select - </option>';
		}


		// Display all contact paid memberships pro levels
		$levels = $this->get_all_pmp_levels();
		if ($levels) {
			foreach ($levels as $k => $level) {
				$key = $level->ID;
				$value = $level->name;
				$selected = '';

				// Mark form as selected as required
				if (is_array($field['value'])) {
					// If the value is an array (multiple select), loop through values and check if it is selected
					if (in_array($key, $field['value'])) {
						$selected = 'selected="selected"';
					}
				} else {
					// If not a multiple select, just check normaly
					if ($key == $field['value']) {
						$selected = 'selected="selected"';
					}
				}
				//Check if field is disabled
				if (in_array(($k + 1), $field['disable'])) {
					//Show disabled levels?
					if ($field['hide_disabled'] == 0) {
						echo '<option value="' . $key . '" ' . $selected . ' disabled="disabled">' . $value . '</option>';
					}
				} else {
					echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
				}
			}
		}

		echo '</select>';
	}

	/**
	 * - this function is called in the admin_head of the edit screen where your field
	 * is created. Use this function to create css and javascript to assist your 
	 * create_field() function.
	 *
	 * @author Elliot Condon
	 * @since 2.2.0
	 */
	function admin_head() {
	}

	/**
	 * admin_print_scripts / admin_print_styles
	 * - this function is called in the admin_print_scripts / admin_print_styles where 
	 * your field is created. Use this function to register css and javascript to assist 
	 * your create_field() function.
	 *
	 * @author Elliot Condon
	 * @since 3.0.0
	 */
	function admin_print_scripts() {
	}
	function admin_print_styles() {
	}

	/**
	 * - this function is called when saving a post object that your field is assigned to.
	 * the function will pass through the 3 parameters for you to use.
	 *
	 * @params
	 * - $post_id (int) - usefull if you need to save extra data or manipulate the current
	 * post object
	 * - $field (array) - usefull if you need to manipulate the $value based on a field option
	 * - $value (mixed) - the new value of your field.
	 *
	 * @author Elliot Condon
	 * @since 2.2.0	 */
	function update_value($post_id, $field, $value) {
		// do stuff with value
		// save value
		parent::update_value($post_id, $field, $value);
	}

	/**
	 * - called from the edit page to get the value of your field. This function is useful
	 * if your field needs to collect extra data for your create_field() function.
	 *
	 * @params
	 * - $post_id (int) - the post ID which your value is attached to
	 * - $field (array) - the field object.
	 *
	 * @author Elliot Condon
	 * @since 2.2.0
	 */
	function get_value($post_id, $field) {
		// get value
		$value = parent::get_value($post_id, $field);

		// format value
		// return value
		return $value;
	}

	/**
	 * - called from your template file when using the API functions (get_field, etc). 
	 * This function is useful if your field needs to format the returned value
	 *
	 * @params
	 * - $post_id (int) - the post ID which your value is attached to
	 * - $field (array) - the field object.
	 *
	 * @author Elliot Condon
	 * @since 3.0.0
	 */
	function get_value_for_api($post_id, $field) {
		// get value
		$value = $this->get_value($post_id, $field);
		if (!$value || $value == 'null') {
			return false;
		}

		return $value;
	}
	
	/**
	 * @global wpdb $wpdb
	 * @return array
	 * 
	 * @author Mirco Babini <mirkolofio@gmail.com>
	 */
	function get_all_pmp_levels() {
		global $wpdb;
		$levels = $wpdb->get_results("
			SELECT ID, name
			FROM {$wpdb->prefix}pmpro_membership_levels
			WHERE 1
		");

		return $levels;
	}
}
?>