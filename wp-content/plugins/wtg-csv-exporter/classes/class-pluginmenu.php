<?php
/**
* Beta testing only (check if in use yet) - phasing array files into classes of their own then calling into the main class
*/
class WTGCSVEXPORTER_TabMenu {
    public function menu_array() {
        $menu_array = array();
        
        ######################################################
        #                                                    #
        #                        MAIN                        #
        #                                                    #
        ######################################################
        // can only have one view in main right now until WP allows pages to be hidden from showing in
        // plugin menus. This may provide benefit of bringing user to the latest news and social activity
        // main page
        $menu_array['main']['tabgroup'] = 'main';        
        $menu_array['main']['longname'] = 'wtgcsvexporter';// home page slug set in main file
        $menu_array['main']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php               
        $menu_array['main']['adminmenutitle'] = __('Plugin Dashboard', 'wtgcsvexporter' );// plugin admin menu
        $menu_array['main']['pluginmenu'] = __( 'Plugin Dashboard' ,'wtgcsvexporter' );// for tabbed menu
        $menu_array['main']['shortname'] = "main";// name of page (slug) and unique
        $menu_array['main']['viewtitle'] = 'Dashboard';// title at the top of the admin page
        $menu_array['main']['tabgroupparent'] = 'parent';// either "parent" or the name of the parent - used for building tab menu         
        $menu_array['main']['showtabmenu'] = false;// boolean - true indicates multiple pages in section, false will hide tab menu and show one page 

        
        ######################################################
        #                                                    #
        #                  CREATE PROFILES                   #
        #                                                    #
        ###################################################### 
                
        // createbasicprofiles
        $menu_array['createbasicprofiles']['tabgroup'] = 'createprofiles';
        $menu_array['createbasicprofiles']['longname'] = 'wtgcsvexporter_createbasicprofiles'; 
        $menu_array['createbasicprofiles']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        $menu_array['createbasicprofiles']['adminmenutitle'] = __( 'Create Profiles (alpha)', 'wtgcsvexporter' );
        $menu_array['createbasicprofiles']['pluginmenu'] = __( 'Create Basic Profiles', 'wtgcsvexporter' );
        $menu_array['createbasicprofiles']['shortname'] = "creationform";
        $menu_array['createbasicprofiles']['viewtitle'] = __( 'Create Basic Profiles', 'wtgcsvexporter' ); 
        $menu_array['createbasicprofiles']['tabgroupparent'] = 'parent'; 
        $menu_array['createbasicprofiles']['showtabmenu'] = true;
                                
        // createdetailedprofiles
        /*
        $menu_array['createdetailedprofiles']['tabgroup'] = 'createprofiles';
        $menu_array['createdetailedprofiles']['longname'] = 'wtgcsvexporter_createdetailedprofiles'; 
        $menu_array['createdetailedprofiles']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        $menu_array['createdetailedprofiles']['adminmenutitle'] = __( 'Create Profiles (alpha)', 'wtgcsvexporter' );
        $menu_array['createdetailedprofiles']['pluginmenu'] = __( 'Create Detailed Profiles', 'wtgcsvexporter' );
        $menu_array['createdetailedprofiles']['shortname'] = "creationform";
        $menu_array['createdetailedprofiles']['viewtitle'] = __( 'Create Detailed Profiles', 'wtgcsvexporter' ); 
        $menu_array['createdetailedprofiles']['tabgroupparent'] = 'createbasicprofiles'; 
        $menu_array['createdetailedprofiles']['showtabmenu'] = true;
         */       
               
        ######################################################
        #                                                    #
        #                  BASIC PROFILES                    #
        #       Basic - good starting points for custom.     #
        ###################################################### 
           
        // allposts
        $menu_array['allposts']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['allposts']['longname'] = 'wtgcsvexporter_allposts'; 
        $menu_array['allposts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        $menu_array['allposts']['adminmenutitle'] = __( 'Basic Default Profiles', 'wtgcsvexporter' );
        $menu_array['allposts']['pluginmenu'] = __( 'All Post Types', 'wtgcsvexporter' );
        $menu_array['allposts']['shortname'] = "allposts";
        $menu_array['allposts']['viewtitle'] = __( 'All Post Types', 'wtgcsvexporter' ); 
        $menu_array['allposts']['tabgroupparent'] = 'parent'; 
        $menu_array['allposts']['showtabmenu'] = true; 

        // posts
        $menu_array['posts']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['posts']['longname'] = 'wtgcsvexporter_posts';
        $menu_array['posts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        $menu_array['posts']['adminmenutitle'] = __( 'Posts', 'wtgcsvexporter' );
        $menu_array['posts']['pluginmenu'] = __( 'Posts', 'wtgcsvexporter' );
        $menu_array['posts']['shortname'] = "posts";
        $menu_array['posts']['viewtitle'] = __( 'Posts', 'wtgcsvexporter' ); 
        $menu_array['posts']['tabgroupparent'] = 'allposts'; 
        $menu_array['posts']['showtabmenu'] = true;   
                
        // pages
        $menu_array['pages']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['pages']['longname'] = 'wtgcsvexporter_pages';
        $menu_array['pages']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        $menu_array['pages']['adminmenutitle'] = __( 'Pages', 'wtgcsvexporter' );
        $menu_array['pages']['pluginmenu'] = __( 'Pages', 'wtgcsvexporter' );
        $menu_array['pages']['shortname'] = "pages";
        $menu_array['pages']['viewtitle'] = __( 'Pages', 'wtgcsvexporter' ); 
        $menu_array['pages']['tabgroupparent'] = 'allposts'; 
        $menu_array['pages']['showtabmenu'] = true; 
        
        /*  
        // comments
        $menu_array['comments']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['comments']['longname'] = 'wtgcsvexporter_comments';
        $menu_array['comments']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                               
        $menu_array['comments']['adminmenutitle'] = __( 'Comments', 'wtgcsvexporter' );
        $menu_array['comments']['pluginmenu'] = __( 'Comments', 'wtgcsvexporter' );
        $menu_array['comments']['shortname'] = "wordpressfeatures";
        $menu_array['comments']['viewtitle'] = __( 'Comments', 'wtgcsvexporter' ); 
        $menu_array['comments']['tabgroupparent'] = 'allposts'; 
        $menu_array['comments']['showtabmenu'] = true; 
        */
        
        // links 
        //$menu_array['links']['tabgroup'] = 'basicdefaultprofiles';
        //$menu_array['links']['longname'] = 'wtgcsvexporter_links'; 
        //$menu_array['links']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        //$menu_array['links']['adminmenutitle'] = __( 'Links', 'wtgcsvexporter' );
        //$menu_array['links']['pluginmenu'] = __( 'Links', 'wtgcsvexporter' );
        //$menu_array['links']['shortname'] = "tablelist";
        //$menu_array['links']['viewtitle'] = __( 'Links', 'wtgcsvexporter' ); 
        //$menu_array['links']['tabgroupparent'] = 'allposts'; 
        //$menu_array['links']['showtabmenu'] = true; 
        
        // options
        //$menu_array['options']['tabgroup'] = 'basicdefaultprofiles';
        //$menu_array['options']['longname'] = 'wtgcsvexporter_options';
        //$menu_array['options']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        //$menu_array['options']['adminmenutitle'] = __( 'Options', 'wtgcsvexporter' );
        //$menu_array['options']['pluginmenu'] = __( 'Options', 'wtgcsvexporter' );
        //$menu_array['options']['shortname'] = "options";
        //$menu_array['options']['viewtitle'] = __( 'Options', 'wtgcsvexporter' ); 
        //$menu_array['options']['tabgroupparent'] = 'allposts'; 
        //$menu_array['options']['showtabmenu'] = true; 
               
        // users
        //$menu_array['users']['tabgroup'] = 'basicdefaultprofiles';
        //$menu_array['users']['longname'] = 'wtgcsvexporter_users';
        //$menu_array['users']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        //$menu_array['users']['adminmenutitle'] = __( 'Users', 'wtgcsvexporter' );
        //$menu_array['users']['pluginmenu'] = __( 'Users', 'wtgcsvexporter' );
        //$menu_array['users']['shortname'] = "users";
        //$menu_array['users']['viewtitle'] = __( 'Users', 'wtgcsvexporter' ); 
        //$menu_array['users']['tabgroupparent'] = 'allposts'; 
        //$menu_array['users']['showtabmenu'] = true; 
                       
        // recent posts (wp_get_recent_posts)
        //$menu_array['recentposts']['tabgroup'] = 'basicdefaultprofiles';
        //$menu_array['recentposts']['longname'] = 'wtgcsvexporter_recentposts';
        //$menu_array['recentposts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        //$menu_array['recentposts']['adminmenutitle'] = __( 'Recent Posts', 'wtgcsvexporter' );
        //$menu_array['recentposts']['pluginmenu'] = __( 'Recent Posts', 'wtgcsvexporter' );
        //$menu_array['recentposts']['shortname'] = "recentposts";
        //$menu_array['recentposts']['viewtitle'] = __( 'Recent Posts', 'wtgcsvexporter' ); 
        //$menu_array['recentposts']['tabgroupparent'] = 'allposts'; 
        //$menu_array['recentposts']['showtabmenu'] = true; 

        ######################################################
        #                                                    #
        #                 EXTENDED PROFILES                  #
        #          Can export objects with meta data         #
        ###################################################### 
        
        /*
        // allposts + single meta values
        $menu_array['allposts']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['allposts']['longname'] = 'wtgcsvexporter_allposts'; 
        $menu_array['allposts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        $menu_array['allposts']['adminmenutitle'] = __( 'Default Profiles', 'wtgcsvexporter' );
        $menu_array['allposts']['pluginmenu'] = __( 'All Post Types', 'wtgcsvexporter' );
        $menu_array['allposts']['shortname'] = "allposts";
        $menu_array['allposts']['viewtitle'] = __( 'All Post Types', 'wtgcsvexporter' ); 
        $menu_array['allposts']['tabgroupparent'] = 'parent'; 
        $menu_array['allposts']['showtabmenu'] = true; 
           
        // allposts + comment count
        $menu_array['allposts']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['allposts']['longname'] = 'wtgcsvexporter_allposts'; 
        $menu_array['allposts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        $menu_array['allposts']['adminmenutitle'] = __( 'Default Profiles', 'wtgcsvexporter' );
        $menu_array['allposts']['pluginmenu'] = __( 'All Post Types', 'wtgcsvexporter' );
        $menu_array['allposts']['shortname'] = "allposts";
        $menu_array['allposts']['viewtitle'] = __( 'All Post Types', 'wtgcsvexporter' ); 
        $menu_array['allposts']['tabgroupparent'] = 'parent'; 
        $menu_array['allposts']['showtabmenu'] = true;

        // allposts + author
        $menu_array['allposts']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['allposts']['longname'] = 'wtgcsvexporter_allposts'; 
        $menu_array['allposts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                      
        $menu_array['allposts']['adminmenutitle'] = __( 'Default Profiles', 'wtgcsvexporter' );
        $menu_array['allposts']['pluginmenu'] = __( 'All Post Types', 'wtgcsvexporter' );
        $menu_array['allposts']['shortname'] = "allposts";
        $menu_array['allposts']['viewtitle'] = __( 'All Post Types', 'wtgcsvexporter' ); 
        $menu_array['allposts']['tabgroupparent'] = 'parent'; 
        $menu_array['allposts']['showtabmenu'] = true;
                   
        // comments + post ID + post title
        $menu_array['comments']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['comments']['longname'] = 'wtgcsvexporter_comments';
        $menu_array['comments']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                               
        $menu_array['comments']['adminmenutitle'] = __( 'Comments', 'wtgcsvexporter' );
        $menu_array['comments']['pluginmenu'] = __( 'Comments', 'wtgcsvexporter' );
        $menu_array['comments']['shortname'] = "wordpressfeatures";
        $menu_array['comments']['viewtitle'] = __( 'Comments', 'wtgcsvexporter' ); 
        $menu_array['comments']['tabgroupparent'] = 'allposts'; 
        $menu_array['comments']['showtabmenu'] = true; 
          
        // posts + authors
        $menu_array['posts']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['posts']['longname'] = 'wtgcsvexporter_posts';
        $menu_array['posts']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        $menu_array['posts']['adminmenutitle'] = __( 'Posts', 'wtgcsvexporter' );
        $menu_array['posts']['pluginmenu'] = __( 'Posts', 'wtgcsvexporter' );
        $menu_array['posts']['shortname'] = "posts";
        $menu_array['posts']['viewtitle'] = __( 'Posts', 'wtgcsvexporter' ); 
        $menu_array['posts']['tabgroupparent'] = 'allposts'; 
        $menu_array['posts']['showtabmenu'] = true;   
                
        // pages + authors
        $menu_array['pages']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['pages']['longname'] = 'wtgcsvexporter_pages';
        $menu_array['pages']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        $menu_array['pages']['adminmenutitle'] = __( 'Pages', 'wtgcsvexporter' );
        $menu_array['pages']['pluginmenu'] = __( 'Pages', 'wtgcsvexporter' );
        $menu_array['pages']['shortname'] = "pages";
        $menu_array['pages']['viewtitle'] = __( 'Pages', 'wtgcsvexporter' ); 
        $menu_array['pages']['tabgroupparent'] = 'allposts'; 
        $menu_array['pages']['showtabmenu'] = true; 
               
        // users + post count + comment count
        $menu_array['users']['tabgroup'] = 'basicdefaultprofiles';
        $menu_array['users']['longname'] = 'wtgcsvexporter_users';
        $menu_array['users']['menu'] = 'wtgcsvexporter';// wtgcsvexporter, index.php, edit.php, upload.php, link-manager.php, edit.php?post_type=page, edit-comments.php, edit.php?post_type=your_post_type, themes.php, plugins.php, users.php, tools.php, options-general.php                              
        $menu_array['users']['adminmenutitle'] = __( 'Users', 'wtgcsvexporter' );
        $menu_array['users']['pluginmenu'] = __( 'Users', 'wtgcsvexporter' );
        $menu_array['users']['shortname'] = "users";
        $menu_array['users']['viewtitle'] = __( 'Users', 'wtgcsvexporter' ); 
        $menu_array['users']['tabgroupparent'] = 'allposts'; 
        $menu_array['users']['showtabmenu'] = true; 
        */

        ######################################################
        #                                                    #
        #                 ADVANCED PROFILES                  #
        #  taxonomies and media data, plus images in folder  #
        ######################################################   
        
        

        ######################################################
        #                                                    #
        #                 CUSTOM PROFILES                    #
        #   first view allows selections to build new view   #
        ######################################################

        
        /*  
            i.e. posts with next gen gallery data
            
            user profiles with a popular shopping cart plugins data
            
            these will act as samples and encourage requests for hire
            to make more.
        */
                                  
        return $menu_array;
    }
} 
?>
