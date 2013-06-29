<?php
/*
 *  acf-pmp.php
 *
 *  Add one or more paid memberships pro level to a custom field
 *
 *  @since 4.0.0
 *
 *  @info  http://github.com/mirkolofio/acf-pmp-field
 */

class acf_field_pmp extends acf_field {
	/**
	 *  Set name / label needed for actions / filters
	 *
	 *  @since 3.6
	 *  @date  23/01/13
	 */
	function __construct() {
		// vars
		$this->name = 'acf_pmp';
		$this->label = __('Paid Memberships Pro Level');


		// do not delete!
		parent::__construct();
	}

	/**
	 *  This filter is appied to the $value after it is loaded from the db
	 *
	 *  @type  filter
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $value - the value found in the database
	 *  @param $post_id - the $post_id from which the value was loaded from
	 *  @param $field - the field array holding all the field options
	 *
	 *  @return  $value - the value to be saved in te database
	 */
	function load_value($value, $post_id, $field) {
		return $value;
	}

	/**
	 *  This filter is appied to the $value before it is updated in the db
	 *
	 *  @type  filter
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $value - the value which will be saved in the database
	 *  @param $field - the field array holding all the field options
	 *  @param $post_id - the $post_id of which the value will be saved
	 *
	 *  @return  $value - the modified value
	 */
	function update_value($value, $field, $post_id) {
		return $value;
	}

	/**
	 *  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
	 *
	 *  @type  filter
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $value  - the value which was loaded from the database
	 *  @param $field  - the field array holding all the field options
	 *
	 *  @return  $value  - the modified value
	 */
	function levelat_value($value, $field) {
		return $value;
	}

	/**
	 *  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	 *
	 *  @type  filter
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $value  - the value which was loaded from the database
	 *  @param $field  - the field array holding all the field options
	 *
	 *  @return  $value  - the modified value
	 */
	function levelat_value_for_api($value, $field) {
		if (!$value || $value == 'null') {
			return false;
		}

		return $value;
	}

	/**
	 *  This filter is appied to the $field after it is loaded from the database
	 *
	 *  @type  filter
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $field - the field array holding all the field options
	 *
	 *  @return  $field - the field array holding all the field options
	 */
	function load_field($field) {
		return $field;
	}

	/**
	 *  This filter is appied to the $field before it is saved to the database
	 *
	 *  @type  filter
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $field - the field array holding all the field options
	 *  @param $post_id - the field group ID (post_type = acf)
	 *
	 *  @return  $field - the modified field
	 */
	function update_field($field, $post_id) {
		return $field;
	}

	/**
	 *  Create the HTML interface for your field
	 *
	 *  @type  action
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $field - an array holding all the field's data
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

				// Mark level as selected as required
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
	 *  Create extra options for your field. This is rendered when editing a field.
	 *  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	 *
	 *  @type  action
	 *  @since 3.6
	 *  @date  23/01/13
	 *
	 *  @param $field  - an array holding all the field's data
	 */
	function create_options($field) {
		// vars
		$defaults = array(
			'multiple' => 0,
			'allow_null' => 0,
			'default_value' => '',
			'choices' => '',
			'disable' => '',
			'hide_disabled' => 0,
		);

		$field = array_merge($defaults, $field);
		$key = $field['name'];
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Disabled Levels:", 'acf'); ?></label>
				<p class="description"><?php _e("You will not be able to select these levels", 'acf'); ?></p>
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
		do_action('acf/create_field', array(
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
				do_action('acf/create_field', array(
					'type' => 'radio',
					'name' => 'fields[' . $key . '][allow_null]',
					'value' => $field['allow_null'],
					'choices' => array(
						1 => __("Yes", 'acf'),
						0 => __("No", 'acf'),
					),
					'layout' => 'horizontal',
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Select Multiple?", 'acf'); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type' => 'radio',
					'name' => 'fields[' . $key . '][multiple]',
					'value' => $field['multiple'],
					'choices' => array(
						1 => __("Yes", 'acf'),
						0 => __("No", 'acf'),
					),
					'layout' => 'horizontal',
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e("Hide disabled levels?", 'acf'); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
					'type' => 'radio',
					'name' => 'fields[' . $key . '][hide_disabled]',
					'value' => $field['hide_disabled'],
					'choices' => array(
						1 => __("Yes", 'acf'),
						0 => __("No", 'acf'),
					),
					'layout' => 'horizontal',
				));
				?>
			</td>
		</tr>
		<?php
	}

	/**
	 *  input_admin_enqueue_scripts()
	 *
	 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	 *  Use this action to add css + javascript to assist your create_field() action.
	 *
	 *  $info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *  @type  action
	 *  @since 3.6
	 *  @date  23/01/13
	 */
	function input_admin_enqueue_scripts() {
	}

	/**
	 *  This action is called in the admin_head action on the edit screen where your field is created.
	 *  Use this action to add css and javascript to assist your create_field() action.
	 *
	 *  @info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	 *  @type  action
	 *  @since 3.6
	 *  @date  23/01/13
	 */
	function input_admin_head() {
	}

	/**
	 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	 *  Use this action to add css + javascript to assist your create_field_options() action.
	 *
	 *  $info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	 *  @type  action
	 *  @since 3.6
	 *  @date  23/01/13
	 */
	function field_group_admin_enqueue_scripts() {
	}

	/**
	 *  This action is called in the admin_head action on the edit screen where your field is edited.
	 *  Use this action to add css and javascript to assist your create_field_options() action.
	 *
	 *  @info  http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	 *  @type  action
	 *  @since 3.6
	 *  @date  23/01/13
	 */
	function field_group_admin_head() {
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

// create field
new acf_field_pmp();
?>