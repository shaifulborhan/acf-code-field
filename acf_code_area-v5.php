<?php

class acf_field_code_area_V5 extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	08/06/13
	*/
	
	function __construct()
	{
		// vars
		$this->name = 'code_area';
		$this->label = __('Code Area');
		$this->category = __("Content",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			//'preview_size' => 'thumbnail'
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = array(
			'path' => plugin_dir_path(__FILE__),
			'dir' => plugin_dir_url( __FILE__ ),
			'version' => '1.0.0'
		);

	}
	
	
	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	08/06/13
	*
	*  @param	$field	- an array holding all the field's data
	*/
	
	function render_field_settings( $field )
	{

		acf_render_field_setting( $field, array(
			'label'			=> __('Language', 'code_area'),
			'instructions'	=> __('Type of code to display','code_area'),
			'type'			=> 'radio',
			'name'			=> 'language',
			'value'	=>	$field['language'],
			'choices' => array(
				'css'	=>	__("CSS",'code_area'),
				'javascript'	=>	__("Javascript",'code_area'),
				'htmlmixed'	=>	__("HTML",'code_area'),
				'php'	=>	__("PHP",'code_area'),
			)
		));

		acf_render_field_setting( $field, array(
			'label'			=> __('Theme', 'code_area'),
			'instructions'	=> __('Set a theme for the editor (<a href=\"http://codemirror.net/demo/theme.html\" target=\"_blank\">Preview Here</a>)','code_area'),
			'type'			=> 'select',
			'name'			=> 'theme',
			'value'	=>	$field['theme'],
			'choices' => array(
				'default'	=>	__("Default",'code_area'),
				'ambiance'	=>	__("Ambiance",'code_area'),
				'blackboard'	=>	__("Blackboard",'code_area'),
				'cobalt'	=>	__("Cobalt",'code_area'),
				'eclipse'	=>	__("Eclipse",'code_area'),
				'elegant'	=>	__("Elegant",'code_area'),
				'erlang-dark'	=>	__("Erlang Dark",'code_area'),
				'lesser-dark'	=>	__("Lesser Dark",'code_area'),
				'midnight'	=>	__("Midnight",'code_area'),
				'monokai'	=>	__("Monokai",'code_area'),
				'neat'	=>	__("Neat",'code_area'),
				'night'	=>	__("Night",'acf'),
				'rubyblue'	=>	__("Rubyblue",'code_area'),
				'solarized'	=>	__("Solarized",'code_area'),
				'twilight'	=>	__("Twilight",'code_area'),
				'vibrant-ink'	=>	__("Vibrant Ink",'code_area'),
				'xq-dark'	=>	__("XQ Dark",'code_area'),
				'xq-light'	=>	__("XQ Light",'code_area'),
			)
		));
		
	}
	
	
	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	08/06/13
	*/
	
	function render_field( $field )
	{
		$field['value'] = esc_textarea($field['value']);

		$language = '';
		switch($field["language"]){
			case 'css':
				$language = 'CSS';
				break;
			case 'javascript':
				$language = 'Javascript';
				break;
			case 'htmlmixed':
				$language = 'HTML';
				break;
			case 'php':
				$language = 'PHP';
				break;
		}

		echo '<textarea id="' . $field['id'] . '" rows="4" class="' . $field['class'] . '" name="' . $field['name'] . '" >' . $field['value'] . '</textarea>';
		echo '<p style="margin-bottom:0;"><small>You are writing '.$language.' code.</small></p>';
  		?>

		<link rel="stylesheet" href="<?php echo $this->settings['dir'];?>/css/theme/<?= $field["theme"];?>.css">
		<script>
	  		jQuery(document).ready(function($){
				var editor_<?= str_replace('-', '_', $field['id']);?> = CodeMirror.fromTextArea(document.getElementById('<?= $field['id'];?>'), {
			        lineNumbers: true,
			        tabmode: 'indent',
			        mode: '<?= $field["language"];?>',
			        theme: '<?= $field["theme"];?>'
			    });
			});
	  	</script>

		<?php
	}
	
	
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	08/06/13
	*/

	function input_admin_enqueue_scripts()
	{
		
		// register acf scripts
		wp_register_script( 'acf-input-code_area-code_mirror_js', $this->settings['dir'] . 'js/codemirror.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-input-code_area-code_mirror_mode_js', $this->settings['dir'] . 'js/mode/javascript.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-input-code_area-code_mirror_mode_css', $this->settings['dir'] . 'js/mode/css.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-code_area-code_mirror_css', $this->settings['dir'] . 'css/codemirror.css', array('acf-input'), $this->settings['version'] ); 
		wp_register_script( 'acf-input-code_area-code_mirror_mode_html', $this->settings['dir'] . 'js/mode/htmlmixed.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-input-code_area-code_mirror_mode_xml', $this->settings['dir'] . 'js/mode/xml.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-input-code_area-code_mirror_mode_php', $this->settings['dir'] . 'js/mode/php.js', array('acf-input'), $this->settings['version'] );
		wp_register_script( 'acf-input-code_area-code_mirror_mode_clike', $this->settings['dir'] . 'js/mode/clike.js', array('acf-input'), $this->settings['version'] );

		
		// scripts
		wp_enqueue_script(array(
			'acf-input-code_area-code_mirror_js',
			'acf-input-code_area-code_mirror_mode_js',	
			'acf-input-code_area-code_mirror_mode_css',
			'acf-input-code_area-code_mirror_mode_html',
			'acf-input-code_area-code_mirror_mode_xml',
			'acf-input-code_area-code_mirror_mode_php',
			'acf-input-code_area-code_mirror_mode_clike',
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-code_area-code_mirror_css',	
		));		
		
	}
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add css and javascript to assist your create_field() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	08/06/13
	*/

	function input_admin_head()
	{
		
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	08/06/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		
	}

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add css and javascript to assist your create_field_options() action.
	*
	*  @info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_head
	*  @type	action
	*  @since	3.6
	*  @date	08/06/13
	*/

	function field_group_admin_head()
	{
		
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	08/06/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value( $value, $post_id, $field )
	{
		
		switch($field["language"]){
			case 'css':
				return '<style>'.$value.'</style>';
				break;
			case 'javascript':
				return '<script>'.$value.'</script>';
				break;
			case 'htmlmixed':
				return nl2br($value);
				break;
			case 'php':
				return eval($value);
				break;
			default:
				return $value;
		}

		return $value;

	}
		
}


// create field
new acf_field_code_area_V5();

?>