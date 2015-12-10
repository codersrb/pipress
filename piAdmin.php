<?php

// // Plugin Admin Options
add_action( 'admin_menu', 'piPress_add_admin_menu' );
add_action( 'admin_init', 'piPress_settings_init' );

function piPress_add_admin_menu(  ) { 

	add_menu_page( 'piPress', 'piPress', 'manage_options', 'piPress', 'piPress_options_page' );

}

function piPress_settings_init(  ) { 

	register_setting( 'pluginPage', 'piPress_settings' );

	add_settings_section(
		'piPress_pluginPage_section', 
		__( 'Admin', 'wordpress' ), 
		'piPress_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'piPress_text_field_0', 
		__( 'PI Client Secret', 'wordpress' ), 
		'piPress_text_field_0_render', 
		'pluginPage', 
		'piPress_pluginPage_section' 
	);
	
	add_settings_field( 
		'piPress_text_field_1', 
		__( 'PI Client ID', 'wordpress' ), 
		'piPress_text_field_1_render', 
		'pluginPage', 
		'piPress_pluginPage_section' 
	);

}

function piPress_text_field_0_render(  ) { 

	$options = get_option( 'piPress_settings' );
	?>
	<input type='text' name='piPress_settings[piPress_text_field_0]' value='<?php echo $options['piPress_text_field_0']; ?>'>
	<?php

}

function piPress_text_field_1_render(  ) { 

	$options = get_option( 'piPress_settings' );
	?>
	<input type='text' name='piPress_settings[piPress_text_field_1]' value='<?php echo $options['piPress_text_field_1']; ?>'>
	<?php

}

function piPress_settings_section_callback(  ) { 

	echo __( 'Options page for piPress plugin.', 'wordpress' );

}

function piPress_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		
		<h2>piPress</h2>
		
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	
	<?php
	//global $piOptions;
	$options = get_option( 'piPress_settings');
	echo $options[piPress_text_field_0];
	echo "<br />";
	echo $options[piPress_text_field_1];
	echo "<br />";
	echo "PHP Version: " . phpversion();
}

?>