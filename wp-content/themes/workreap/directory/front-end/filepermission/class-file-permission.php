<?php
/**
 * @File Access Permission
 * @return
 */
if (!class_exists('Workreap_file_permission')){
    class Workreap_file_permission{
  
        private static $instance = null;
        private static $encrpytion_salt  = '^^wtkey^^';
        public function __construct(){
           
        }

        /**
         * Returns the *Singleton* instance of this class.
         *
         * @return
         * @throws error
         * @author Amentotech<info@amentotech.com>
         */
        public static function getInstance(){
            if (self::$instance==null){
                self::$instance = new Workreap_file_permission();
            }
            return self::$instance;
        }
		
		/**
         * get Encrypted File
         *
         * @return
         * @throws error
         * @author Amentotech<info@amentotech.com>
         */
        public static function getEncryptFile($file, $post_id, $is_upload=false,$is_encrypt=false){
            $result     = array();
            $post_type	= get_post_type($post_id);
            $i          = time();
			
            if( $is_encrypt ) {

                $file_detail            = pathinfo($file);
                $extension 			    = $file_detail['extension'];
                $filename 			    = $file_detail['filename'];
				
                if($is_upload) {
                    $filename           = $file_detail['filename'].'-'.$i; 
                }
				
                $reverse_file_name      = strrev($filename);
                $new_file_name          = strrev(base64_encode($reverse_file_name.self::$encrpytion_salt.$post_id));
                $new_file_name          = $new_file_name. '.' . $extension;
                $result['url']          = $file_detail['dirname'].'/'.$new_file_name;
                $result['name']         = $new_file_name;
				
                return $result;

            } else {
                $file_detail        = pathinfo($file);
                $extension 			= $file_detail['extension'];
                $filename 			= $file_detail['filename'];
                
                $new_file_name 	= $filename .'-'.$i.'.' . $extension;
				
                $result['url']      = $file_detail['dirname'].'/'.$new_file_name;
                $result['name']     = $new_file_name;
                return $result;

            }
        }
		
		/**
         * get Decrpyt File
         *
         * @return
         * @throws error
         * @author Amentotech<info@amentotech.com>
         */
        public static function getDecrpytFile($file){
            $result              = array();
            $file_detail         = pathinfo($file['url']);
            $attachment_id       = $file['attachment_id'];
            $extension 			 = $file_detail['extension']; 
			
            if(!empty($attachment_id)) {
                //get attachment post meta
                $parent_post_id = wp_get_post_parent_id($attachment_id);
                $post_type      = get_post_type($parent_post_id);
                $is_encrypted   = get_post_meta($attachment_id, 'is_encrypted', true);
                
                if($is_encrypted) {
					$file 	       = explode('^^',base64_decode(strrev($file_detail['filename'])));
					$filename      = strrev($file[0]).'.'.$extension; 
				} else {
					$filename      = $file_detail['filename'].'.'.$extension; 
				}
				
                $result['dirname']   = $file_detail['dirname']; 
                $result['filename']  = $filename;
            }
                      
            return $result;
            
        }
		
		/**
         * Download file
         *
         * @return
         * @throws error
         * @author Amentotech<info@amentotech.com>
         */
        public static function downloadFile($attachmentId){
            $post_id    = !empty($attachmentId) ? get_post_field('post_parent',$attachmentId,true) : '';
            $post_id    = !empty($post_id) ? $post_id : '';
			
            if(!is_user_logged_in()) {
                $json['type']               = 'error';
                $json['message']       = esc_html__('You are not allowed to download this file', 'workreap');
                return $json;
            }
			
            $json = array();
            $attachmentId = !empty($attachmentId) ? intval($attachmentId) : '';
          
            if (!empty($attachmentId)) {
                $post_data = get_post_meta($attachmentId);
                $destinationfile = false;
				
                if (!empty($post_data)) {

                    $filename        = $post_data['_wp_attached_file'][0];
                    $uploadspath     = wp_upload_dir();
                    $sourcefile      = $uploadspath['basedir'].'/'.$filename;
					
                    if(!file_exists($sourcefile)) {
                        $json['type']         = 'error';
                        $json['message'] = esc_html__('Oh no! Looks like like there were no attachments', 'workreap');
                        return $json;
                    }
					
                    $param = array();
                    $param['url']               = $filename;
                    $param['attachment_id']     = $attachmentId;
                    $file_detail     = self::getDecrpytFile($param);
                    $file            = pathinfo($file_detail['filename']);
                    $newfilename     = $file['filename'].'-'.time().'.'.$file['extension'];
                    $thisdir         = "/downloads";
                    $folderPath      = $uploadspath['basedir'].$thisdir."/"; //  directory with absolute path
                    $serverfilepath  = $uploadspath['baseurl'].$thisdir."/"; //  directory with server path
                    
                    if(!is_dir($folderPath)){
                        mkdir($folderPath, 0777, true);
                    }    
					
                    $destinationfile = $folderPath.$newfilename;
                    copy($sourcefile,$destinationfile);  
                    set_transient('temp_download_file_'.time(), serialize($destinationfile),5);
                    $destinationfile = $serverfilepath.$newfilename;
                
                } else {
                    $json['type'] = 'error';
                    $json['message'] = esc_html__('Oh no! Looks like there were no attachments', 'workreap');
                    return $json;
                }
				
                $json['type'] = 'success';
                $json['attachment'] = strrev(base64_encode($destinationfile));
                $json['message'] = esc_html__('Your download was successful', 'workreap');
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = esc_html__('Looks like there was an error. Can you please try again?', 'workreap');
                return $json;
            }
        }

    }
}