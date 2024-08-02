<?php

GFForms::include_addon_framework();

class GF_Lead_Tracker_AddOn extends GFAddOn {

	protected $_version = GF_LEAD_TRACKER_ADDON_VERSION;
	protected $_min_gravityforms_version = '2.0';
	protected $_slug = 'gf_lead_tracker_addon';
	protected $_title = '5x5 Lead Tracker';
	private static $_instance = null;
    
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GF_Lead_Tracker_AddOn();
		}

		return self::$_instance;
	}

    public function init() {

        parent::init();

        add_action( 'gform_form_settings_page_'.$this->get_slug(), function(){
            include 'export-scripts.php';
        });

        
        add_action( 'wp_ajax_ff_gf_generate_csv', [ $this, 'generate_csv_ajax' ] );

        // add_action( 'wp_footer', [ $this, 'frontend_test' ] );
    }
    
	public function form_settings_fields( $form ) {
        
        $setting_fields = [];

        $setting_fields[] = [
            'name'      => 'export_columns',
            'type'      => 'generic_map',
            'label'     => esc_html__( 'Select Fields to Export', 'ff' ),
            'key_field' => [
                'title' => 'Column Name',
                'type'  => 'text',
                'placeholder' => 'Enter column name',
            ],
            'value_field' => array(
                'title' => 'Field',
                'text'  => 'text',
            ),
        ];

        $setting_fields[] = array(
            'type'    => 'checkbox',
            'choices' => array(
                array(
                    'label' => esc_html__( 'Exclude older entries since last export', 'ff' ),
                    'name'  => 'exclude_entries_since_last_export',
                ),
            ),
        );

		return [
            [
                'title'  => esc_html__( '5x5 Lead Tracker Settings', 'ff' ),
				'fields' => $setting_fields,
            ]
        ];
	}

    public function generate_csv_ajax(){

        $debug = [];

        $form_id = $_POST['form_id'];
        $addon_slug = $_POST['addon_slug'];

        $form = GFAPI::get_form( $form_id );
        $form_settings = $form[$addon_slug];

        $entries = GFAPI::get_entries( $form_id );
        
        // headers
        $headers = [];
        foreach( $form_settings['export_columns'] as $item ) {
            $headers[] = $item['custom_key'];
        }

        // rows
        $rows = [];
        foreach( $entries as $entry ) {

            if( $form_settings['exclude_entries_since_last_export'] ) {
                if( $entry['date_created'] < $form_settings['last_export_date'] ) {
                    // exclude entries where create date is older since last export
                    continue;
                }
            }

            $row = [];
            foreach( $form_settings['export_columns'] as $item ) {
                $row[] = $entry[$item['value']] ?? '';
            }
            $rows[] = $row;
        }

        $base_file_name = sanitize_title(get_bloginfo('name')) . '-'. sanitize_title($form['title']);

        $file_url = $this->create_csv_file( $base_file_name, $headers, $rows );

        // save last export date
        $form_settings['last_export_date'] = date('Y-m-d H:i:s');
        $form[$addon_slug] = $form_settings;
        GFFormsModel::update_form_meta( $form_id, $form );
        
        wp_send_json([
            'debug' => $debug,
            'file_url' => $file_url,
        ]);
    }
    

    private function create_csv_file( $base_file_name, $headers, $rows ){
        
        $upload_dir = wp_get_upload_dir();
        $upload_path = $upload_dir['basedir'] . '/temp';
        $upload_url = $upload_dir['baseurl'] . '/temp';

        // create folder if it does not exist yet
        if ( !file_exists($upload_path) ) {
            mkdir($upload_path, 0755, true);
        }

        $file_name = $base_file_name .'-'. date( 'Y-m-d-H-i-s' ) .'.csv';

        $file_path = $upload_path .'/'. $file_name;
        $file_url = $upload_url .'/'. $file_name;
        
        // delete old files before creating new
        $this->delete_files( $base_file_name, $upload_path );

        // create csv file
        $fp = fopen( $file_path, 'w' );
        fputcsv( $fp, $headers );
        foreach ( $rows as $row ) {
            fputcsv( $fp, $row );
        }
        fclose( $fp );

        return $file_url;
    }

    private function delete_files( $base_file_name, $upload_path ){
        if ( $handle = opendir($upload_path) ) {
            // Scan through directory: get all files
            while ( false !== ( $file = readdir($handle) ) ) {
                if ( $file != "." && $file != ".." ) {
                    if( strpos( $file, $base_file_name ) !== false ) {
                        $file_path = $upload_path .'/'. $file;
                        unlink( $file_path );
                    }
                }
            }
            closedir( $handle );
        }
    }

    public function frontend_test(){
        $form_id = 1;
        $entries = GFAPI::get_entries( $form_id );
        // $addon_slug = "gf_lead_tracker_addon";
        $addon_slug = $this->get_slug();
        $form = GFAPI::get_form( $form_id );
        $form_settings = $form[$addon_slug];
        pre_debug(date('Y-m-d H:i:s'));
        pre_debug($form_settings);
        pre_debug($entries);
    }

}