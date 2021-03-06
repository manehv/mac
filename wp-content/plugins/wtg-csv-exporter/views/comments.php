<?php
/**
 * Export all comments.
 *
 * @package WTG CSV Exporter
 * @subpackage Views
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// Prohibit direct script loading
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Export all comments.
 * 
 * @package WTG CSV Exporter
 * @subpackage Views
 * @author Ryan Bayne
 * @since 0.0.1
 */
class WTGCSVEXPORTER_Comments_View extends WTGCSVEXPORTER_View {

    /**
     * Number of screen columns for post boxes on this screen
     *
     * @since 0.0.1
     *
     * @var int
     */
    protected $screen_columns = 1;
    
    protected $view_name = 'comments';
    
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
        // array of meta boxes + used to register dashboard widgets (id, title, callback, context, priority, callback arguments (array), dashboard widget (boolean) )   
        return $this->meta_boxes_array = array(
            // array( id, title, callback (usually parent, approach created by Ryan Bayne), context (position), priority, call back arguments array, add to dashboard (boolean), required capability
            array( $this->view_name . '-datasourcestable', __( 'Data Sources Table', 'wtgcsvexporter' ), array( $this, 'parent' ), 'normal','default',array( 'formid' => 'datasourcestable' ), true, 'activate_plugins' ),

        );    
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
        $this->Forms = WTGCSVEXPORTER::load_class( 'WTGCSVEXPORTER_Formbuilder', 'class-forms.php', 'classes' );
        
        parent::setup( $action, $data );

        // create a data table ( use "head" to position before any meta boxes and outside of meta box related divs)
        $this->add_text_box( 'head', array( $this, 'datatables' ), 'normal' );
                
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
        $WPTableObject = new WTGCSVEXPORTER_Comments_Example();
        $WPTableObject->prepare_items_further( array(), 10 );
        ?>

        <form method="get">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
            <?php             
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
    * post box function for testing
    * 
    * @author Ryan Bayne
    * @package WTG CSV Exporter
    * @since 0.0.1
    * @version 1.0
    */
    public function postbox_comments_datasourcestable( $data, $box ) { 
        global $wpdb;
        $this->UI->postbox_content_header( $box['title'], $box['args']['formid'], __( 'Select the columns of data you would like included in your .csv file.', 'wtgcsvexporter' ), false );        
        $this->Forms->form_start( $box['args']['formid'], $box['args']['formid'], $box['title'] );
        ?>  

        <table class="form-table">
        <?php         
        $table_columns = $wpdb->get_col( "DESC " . $wpdb->prefix . 'comments', 0 );
        $this->Forms->checkboxes_basic( $box['args']['formid'], 'exportablecolumns', 'exportablecolumns', __( 'Select Data', 'wtgcsvexporter' ), $table_columns, array(/*current values*/), true, array(/* validation array */) );
        ?>
        </table>
        
        <?php
        $this->UI->postbox_content_footer();
    }      
}

     
/**
* Example of WP List Table class for display on this view only.
*/
class WTGCSVEXPORTER_Comments_Example extends WP_List_Table {
    
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

            case 'comment_ID':
                return $item->comment_ID;    
                break;
            case 'comment_post_ID':
                return $item->comment_post_ID;    
                break;                
            case 'comment_author':
                return $item->comment_author;
                break;    
            case 'comment_author_email':
                return $item->comment_author_email;
                break;    
            //case 'comment_author_url':
            //    return $item['comment_author_url'];
            //    break;    
            //case 'comment_author_IP':
            //    return $item['comment_author_IP'];
            //    break;    
            //case 'comment_date':
            //    return $item['comment_date'];
            //    break;    
            //case 'comment_date_gmt':
            //    return $item['comment_date_gmt'];
            //    break;    
            //case 'comment_content':
            //    return $item['comment_content'];
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
            'comment_ID' => 'comment_ID',
            'comment_post_ID' => 'comment_post_ID',
            'comment_author'     => 'comment_author',
            'comment_author_email'     => 'comment_author_email',
            //'comment_author_url'     => 'comment_author_url',
            //'comment_author_IP'     => 'comment_author_IP',
            //'comment_date'     => 'comment_date',
            //'comment_date_gmt'     => 'comment_date_gmt',
            //'comment_content'     => 'comment_content',
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
            '<input type="checkbox" name="arecord[]" value="%s" />', $item->comment_ID
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
        if( 'delete'===$this->current_action() ) {
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
            $defaults = array(
                'author_email' => '',
                'author__in' => '',
                'author__not_in' => '',
                'include_unapproved' => '',
                'fields' => '',
                'ID' => '',
                'comment__in' => '',
                'comment__not_in' => '',
                'karma' => '',
                'number' => '',
                'offset' => '',
                'orderby' => '',
                'order' => 'DESC',
                'parent' => '',
                'post_author__in' => '',
                'post_author__not_in' => '',
                'post_ID' => '',
                'post_id' => 0,
                'post__in' => '',
                'post__not_in' => '',
                'post_author' => '',
                'post_name' => '',
                'post_parent' => '',
                'post_status' => '',
                'post_type' => '',
                'status' => 'all',
                'type' => '',
                'user_id' => '',
                'search' => '',
                'count' => false,
                'meta_key' => '',
                'meta_value' => '',
                'meta_query' => '',
                'date_query' => null, // See WP_Date_Query
            );            
            $data = get_comments( $defaults );            
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