<?php


function get_bubble_post_types() {

	$post_types = get_post_types();

	foreach( $post_types as $post_type_name ) 
    {
        $ignore_post_types  =   array(
            'reply',
            'topic',
            'report',
            'status',
            'attachment' 
        );
                                                
        if(in_array($post_type_name, $ignore_post_types))
            continue;
                                                
        //if(is_post_type_hierarchical($post_type_name))
        //    continue;
                                                    
        $post_type_data = get_post_type_object( $post_type_name );
        if($post_type_data->show_ui === FALSE)
            continue;

        $types[$post_type_data->name] = $post_type_data->label;

    }

    return $types;

}