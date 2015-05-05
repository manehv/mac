<?php
/**
 * Form for creating single .csv file profiles.
 *
 * @package WTG CSV Exporter
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * View class offering forms for creating single .csv profiles profile.
 * 
 * @package WTG CSV Exporter
 * @subpackage Views
 * @author Ryan Bayne
 * @since 0.0.1
 */
class WTGCSVEXPORTER_createbasicprofiles_View extends WTGCSVEXPORTER_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 0.0.1
     *
     * @var int
     */
    protected $screen_columns = 1;
    
    protected $view_name = 'createbasicprofiles';
    
    public $purpose = 'normal';// normal, dashboard

    /**
    * Array of meta boxes, looped through to register them on views and as dashboard widgets
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function meta_box_array() {
        global $wtgcsvexporter_settings;
        
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        $this->meta_boxes_array = array(
            array( $this->view_name . '-primarybasic', __( '1. Primary Core Data', 'wtgcsvexporter' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'primarybasic' ), true, 'activate_plugins' ),
        );
                
        // postboxes are hidden until previous form submitted
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'] ) ) {  
            $this->meta_boxes_array = array_merge( $this->meta_boxes_array, array( array( $this->view_name . '-metabasic', __( '2. Meta', 'wtgcsvexporter' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'metabasic' ), true, 'activate_plugins' ) ) );
            $this->meta_boxes_array = array_merge( $this->meta_boxes_array, array( array( $this->view_name . '-taxonomiesbasic', __( '3. Taxonomies', 'wtgcsvexporter' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'taxonomiesbasic' ), true, 'activate_plugins' ) ) );  
        }
                                       
        if( in_array( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'], $this->post_types_array ) ) {    
            $this->meta_boxes_array = array_merge( $this->meta_boxes_array, array( array( $this->view_name . '-mediabasic', __( '4. Media', 'wtgcsvexporter' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'mediabasic' ), true, 'activate_plugins' ) ) );
        }
        
        // if primary selected - allow user to select database tables 
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'] ) ) {
            $this->meta_boxes_array = array_merge( $this->meta_boxes_array, array( array( $this->view_name . '-finishbasic', __( 'Finish', 'wtgcsvexporter' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'finishbasic' ), true, 'activate_plugins' ) ) );
        }
                        
        return $this->meta_boxes_array;    
    }
            
    /**
     * Set up the view with data and do things that are specific for this view
     *
     * @since 0.0.1
     *
     * @param string $action Action for this view
     * @param array $data Data for this view
     */
    public function setup( $action, array $data ) {
        global $wtgcsvexporter_settings;
        
        // create constant for view name
        if(!defined( "WTGCSVEXPORTER_VIEWNAME") ){define( "WTGCSVEXPORTER_VIEWNAME", $this->view_name );}
        
        // create class objects
        $this->WTGCSVEXPORTER = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER', 'class-wtgcsvexporter.php', 'classes' );
        $this->UI = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER_UI', 'class-ui.php', 'classes' ); 
        $this->DB = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER_DB', 'class-wpdb.php', 'classes' );
        $this->PHP = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER_PHP', 'class-phplibrary.php', 'classes' );
        $this->FORMS = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER_Formbuilder', 'class-forms.php', 'classes' );
        
        parent::setup( $action, $data );

        $this->post_types_objects = get_post_types( '', 'objects' );
        $this->post_types_array = array();
        if( $this->post_types_objects )
        {
            foreach( $this->post_types_objects as $pt )
            {
                $this->post_types_array = array_merge( $this->post_types_array, array( $pt->name => $pt->labels->name ) );
            }
        }
        
        // create a data table ( use "head" to position before any meta boxes and outside of meta box related divs)
        //$this->add_text_box( 'head', array( $this, 'datatables' ), 'normal' );
                
        // using array register many meta boxes
        foreach( self::meta_box_array() as $key => $metabox ) {
            // the $metabox array includes required capability to view the meta box
            if( isset( $metabox[7] ) && current_user_can( $metabox[7] ) ) {
                $this->add_meta_box( $metabox[0], $metabox[1], $metabox[2], $metabox[3], $metabox[4], $metabox[5] );   
            }               
        }                  
    }

    /**
    * Displays one or more tables of data at the top of the page before post boxes
    * 
    * @author Ryan R. Bayne
    * @package WordPress Plugin WTG CSV Exporter Pro
    * @since 0.0.1
    * @version 1.0
    */
    public function datatables( $data, $box ) {       
        $WPTableObject = new WTGCSVEXPORTER_createbasicprofiles_Exampledata();
        $WPTableObject->prepare_items_further( array(), 10 );
        ?>

        <form method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
            <?php             
            _e( 'This is a preview of some data based on your new profiles settings so far.', 'wtgcsvexporter' );
            
            // pass data here or use prepare_items_further() itself to query data
            $WPTableObject->prepare_items_further( false, 5 );
            
            // add search field
            $WPTableObject->search_box( 'search', 'theidhere' ); 
            
            // display the table
            $WPTableObject->display();
            ?>
        </form>
 
        <?php               
    }
    
    /**
    * Outputs the meta boxes
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.3
    * @version 1.0
    */
    public function metaboxes() {
        parent::register_metaboxes( self::meta_box_array() );     
    }

    /**
    * This function is called when on WP core dashboard and it adds widgets to the dashboard using
    * the meta box functions in this class. 
    * 
    * @uses dashboard_widgets() in parent class WTGCSVEXPORTER_View which loops through meta boxes and registeres widgets
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.2
    * @version 1.0
    */
    public function dashboard() { 
        parent::dashboard_widgets( self::meta_box_array() );  
    }
    
    /**
    * All add_meta_box() callback to this function to keep the add_meta_box() call simple.
    * 
    * This function also offers a place to apply more security or arguments.
    * 
    * @author Ryan R. Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    function parent( $data, $box ) {
        eval( 'self::postbox_' . $this->view_name . '_' . $box['args']['formid'] . '( $data, $box );' );
    }

    /**
    * Select post, page, (list of custom post types), comments, media.
    * 
    * Selection of each will display further options once submitted. 
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_createbasicprofiles_primarybasic( $data, $box ) {                         
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Select the main focus for your data export. It will probably make up most of your .csv files data however you will get the chance to add more columns after you save your selection.', 'wtgcsvexporter' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
                
        global $wtgcsvexporter_settings;

        // set current value
        $current_value = '';
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'] ) ) { 
            $current_value = $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'];
        }
        
        // initiate items
        $items_array = array( 'comments' => __( 'Comments', 'wtgcsvexporter' ), 'users' => __( 'User Data', 'wtgcsvexporter' ), 'allposttypes' => __( 'All Post Types', 'wtgcsvexporter' ) );
        $items_array = array_merge( $items_array, $this->post_types_array );
                                   
        echo '<table class="form-table">';
        $this->FORMS->menu_basic( $box['args']['formid'], 'primarysources', 'primarysources', __( 'Your Primary Source', 'wtgcsvexporter' ), $items_array, true, $current_value );
        echo '</table>';
             
        $this->UI->postbox_content_footer( __( 'Save', 'wtgcsvexporter' ) );
    }
    
    /**
    * Select what meta is to be exported.
    * 
    * After selecting primary, all known meta keys will be listed. 
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_createbasicprofiles_metabasic( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Each selection here will add a new column to your .csv file.', 'wtgcsvexporter' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
                
        global $wtgcsvexporter_settings;
        
        $meta_keys = $this->DB->metakeys_distinct();
        
        // re-build array so that checkbox values are metakeys
        $items_array = array();
        foreach( $meta_keys as $key => $meta_key ) 
        {         
            $items_array[ $meta_key->meta_key ] = $meta_key->meta_key;
        }
        
        $current_value = array();
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'] ) )
        {
            $current_value = $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['metakeys'];
        }
        
        echo '<table class="form-table">';
        $this->FORMS->checkboxes_basic( $box['args']['formid'], 'distinctmetakeys', 'distinctmetakeys', __( 'Meta Data', 'wtgcsvexporter' ), $items_array, $current_value, true, array(), false );
        echo '</table>';
        
        $this->UI->postbox_content_footer( __( 'Save', 'wtgcsvexporter' ) );
    }    
    
    /**
    * Select what taxonomies are be exported.
    * 
    * After selecting primary, all known meta keys will be listed. 
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_createbasicprofiles_taxonomiesbasic( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Select which taxonomies are to be exported to your .csv file. This form offers taxonomies for your primary data source only. Remember that a single taxonomy column may hold many individual values.', 'wtgcsvexporter' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
                
        global $wtgcsvexporter_settings;
        
        $current_value = array();
        $items_array = array();   

        $taxonomies = get_object_taxonomies( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['primary'], 'object' );
          
        if( $taxonomies )
        {
            foreach( $taxonomies as $pt )
            {
                $items_array = array_merge( $items_array, array( $pt->name => $pt->labels->name ) );
            }
        }
        
        $current_value = array();
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'] ) )
        {
            $current_value = $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['taxonomies'];
        }
        
        echo '<table class="form-table">';
        $this->FORMS->checkboxes_basic( $box['args']['formid'], 'exporttaxonomies', 'exporttaxonomies', __( 'Registered Taxonomies', 'wtgcsvexporter' ), $items_array, $current_value, true, array(), false );        
        echo '</table>';
        
        $this->UI->postbox_content_footer( __( 'Save', 'wtgcsvexporter' ) );
    }      
      
    /**
    * Select attachment options per post-type already selected.
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_createbasicprofiles_mediabasic( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Form introduction.', 'wtgcsvexporter' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
                
        global $wtgcsvexporter_settings;
        
        $current_settings_array = array();
        if( isset( $wtgcsvexporter_settings['csvexportprofiles']['singlefileprofile']['tables']['posts'] ) ) { 
        }

        $this->UI->postbox_content_footer( __( 'Save', 'wtgcsvexporter' ) );
    }      

    /**
    * Final Actions: create profile by moving singlefileprofile profile
    * to it's own array in $wtgcsvexporter_settings.
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_createbasicprofiles_finishbasic( $data, $box ) { 
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'When your happy with your selections you can use this Save button to create a new profile. Please do this before creating a new profile on this view as all selections here are temporary.', 'wtgcsvexporter' ), false );        
        $this->FORMS->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
                
        global $wtgcsvexporter_settings;
                   
        echo '<table class="form-table">';
        
        $this->FORMS->input_subline( $this->UI->linkaction( 'wtgcsvexporter_createbasicprofiles', 'testbasicprofile', __( 'Export your data now to test your selections before clicking Save.', 'wtgcsvexporter' ), __( 'Export Test', 'wtgcsvexporter' ) ), __( 'Want to test first?', 'wtgcsvexporter' ) );
        
        $this->FORMS->text_basic( $box['args']['formid'], 'usersnewprofilename', 'usersnewprofilename', __( 'New Profile Name', 'wtgcsvexporter' ), '', true, array() );
        
        echo '</table>';
        
        echo __( 'I have removed this forms button temporarily. I decided to stop development at this point where we can
        still export using the test button above. The next update should allow the profile to be saved as intended.', 'wtgcsvexporter' );
        //$this->UI->postbox_content_footer( __( 'Save', 'wtgcsvexporter' ) );
    }
}

     
/**
* Example of WP List Table class for display on this view only.
*/
class WTGCSVEXPORTER_createbasicprofiles_Exampledata extends WP_List_Table {
    
    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct() {
        global $status, $page;
             
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    
    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default( $item, $column_name ){   
      
        $attributes = "class=\"$column_name column-$column_name\"";
                
        switch( $column_name){
            
            case 'ID':
                return $item->ID;    
                break;
            //case 'post_author':
            //    return $item['post_author'];    
            //    break;                
            //case 'post_date':
            //    return $item['post_date'];
            //    break;  
            //case 'post_date_gmt':
            //    return $item['post_date_gmt'];
            //    break;  
            //case 'post_content':
            //    return $item['post_content'];
            //    break;  
            case 'post_title':
                return $item->post_title;
                break;  
            //case 'post_excerpt':
            //    return $item['post_excerpt'];
            //    break;  
            case 'post_status':
                return $item->post_status;
                break;  
            //case 'comment_status':
            //    return $item['comment_status'];
            //    break;  
            //case 'ping_status':
            //    return $item['ping_status'];
            //    break;  
            //case 'post_password':
            //    return $item['post_password'];
            //    break;  
            case 'post_name':
                return $item->post_name;
                break;  
            //case 'to_ping':
            //    return $item['to_ping'];
            //    break;  
            //case 'pinged':
            //    return $item['pinged'];
            //    break;  
            //case 'post_modified':
            //    return $item['post_modified'];
            //    break;  
            //case 'post_modified_gmt':
            //    return $item['post_modified_gmt'];
            //    break;  
            //case 'post_content_filtered':
            //    return $item['post_content_filtered'];
            //    break;  
            //case 'post_parent':
            //    return $item['post_parent'];
            //    break;  
            //case 'guid':
            //    return $item['guid'];
            //    break;  
            //case 'menu_order':
            //    return $item['menu_order'];
            //    break;  
            case 'post_type':
                return $item->post_type;
                break;  
            //case 'post_mime_type':
            //    return $item['post_mime_type'];
            //    break;  
            //case 'comment_count':
            //    return $item['comment_count'];
            //    break;  
                    
            default:
                return 'No column function or default setup in switch statement';
        }
    }
                    
    /** ************************************************************************
    * Recommended. This is a custom column method and is responsible for what
    * is rendered in any column with a name/slug of 'title'. Every time the class
    * needs to render a column, it first looks for a method named 
    * column_{$column_title} - if it exists, that method is run. If it doesn't
    * exist, column_default() is called instead.
    * 
    * This example also illustrates how to implement rollover actions. Actions
    * should be an associative array formatted as 'slug'=>'link html' - and you
    * will need to generate the URLs yourself. You could even ensure the links
    * 
    * 
    * @see WP_List_Table::::single_row_columns()
    * @param array $item A singular item (one full row's worth of data)
    * @return string Text to be placed inside the column <td> (movie title only )
    **************************************************************************/
    /*
    function column_title( $item){

    } */
    
    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns() {
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'ID' => 'ID',
            //'post_author' => 'post_author',
            //'post_date'     => 'post_date',
            //'post_date_gmt' => 'post_date_gmt',
            //'post_content' => 'post_content',
            'post_title' => 'post_title',
            //'post_excerpt' => 'post_excerpt',
            'post_status' => 'post_status',
            //'comment_status' => 'comment_status',
            //'ping_status' => 'ping_status',
            //'post_password' => 'post_password',
            'post_name' => 'post_name',
            //'to_ping' => 'to_ping',
            //'pinged' => 'pinged', 
            //'post_modified' => 'post_modified',
            //'post_modified_gmt' => 'post_modified_gmt',
            //'post_content_filtered' => 'post_content_filtered',
            //'post_parent' => 'post_parent',
            //'guid' => 'guid',
            //'menu_order' => 'menu_order',
            'post_type' => 'post_type',
            //'post_mime_type' => 'post_mime_type',
            //'comment_count' => 'comment_count'
        );
        
        /*
        if( isset( $this->action ) ){
            $columns['action'] = 'Action';
        } 
        */                                      
           
        return $columns;
    }
   
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="arecord[]" value="%s" />', $item->ID
        );    
    }
        
    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items_further() and sort
     * your data accordingly (usually by modifying your query ).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array( 'data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            //'post_title'     => array( 'post_title', false ),     //true means it's already sorted
        );
        return $sortable_columns;
    }
    
    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'canceltask' => 'Cancel Tasks'
        );
        return $actions;
    }

    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items_further()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete' === $this->current_action() ) {
            wp_die( 'Items deleted (or they would be if we had items to delete)!' );
        }
        
    }
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items_further( $data, $per_page = 20 ) {
        global $wpdb; //This is used only if making any database queries        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        /**
        * OPTIONAL APPROACH. We can get our data here if not using $data. We can also
        * use $columns to improve the query and only return the values we will actually use.
        * 
        * We can apply $_GET['s'] for search here and improve the query further 
        */
        if( $data === false || !is_array( $data ) ) {
            $args = array(
                'posts_per_page'   => 5,
                'offset'           => 0,
                'category'         => '',
                'category_name'    => '',
                'orderby'          => 'post_date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'any',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'post_status'      => 'all',
                'suppress_filters' => true );  
             $data = get_posts( $args );          
        }
        
        // in this example I'm going to remove records from the array that do not have the searched string (test string is: 2)
        if( isset( $_GET['s'] ) && !empty( $_GET['s'] ) ) {
            
            $searched_string = wp_unslash( $_GET['s'] );
            
            foreach( $data as $key => $record_values  ) {
                
                $match_found = false;
                
                foreach( $record_values as $example_value ) {
                   if ( strpos( $example_value, $searched_string ) !== FALSE) { // Yoshi version
                        $match_found = true;
                        break;
                   }                
                }    
                
                // if no $match_found remove the current $record_values using the $key
                if( !$match_found ) {
                    unset( $data[ $key ] );    
                }
            }
        }
                
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array( $columns, $hidden, $sortable);
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
      
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count( $data);

        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice( $data,(( $current_page-1)*$per_page), $per_page);
 
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
  
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}

?>