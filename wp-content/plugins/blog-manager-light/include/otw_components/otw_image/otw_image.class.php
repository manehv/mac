<?php
class OTW_Image extends OTW_Component{
	
	/**
	 * Profiles
	 */
	private $profiles = array();
	
	/**
	 * Image extensions accepted for resize
	 */
	private $types = array("gif", "jpeg", "jpg", "png", "wbmp");
	
	public function init(){
		
		if( is_admin() ){
			add_action( 'admin_notices', array( $this, 'render_errors' ) );
		}
	}
	
	public $errors = array();
	
	/**
	 * add profile
	 */
	public function add_profile( $upload_path, $upload_url, $sub_folder, $cache_folder = 'tmb', $quality = 90,  $types = false, $default_type = 'jpg', $default_background = array( 255, 255, 255 ), $use_cache = true ){
		
		// Check if Uploads directory is writtable
		if( !is_writable( $upload_path ) ){
			$this->errors[] = 'Folder: '. $upload_path .' is not writtable. Make sure you have read/write permissions.';
			return;
		}elseif( !file_exists( $upload_path.$sub_folder ) ){
			mkdir( $upload_path.$sub_folder, 0777 );
			
			if( !file_exists( $upload_path.$sub_folder ) ){
				$this->errors[] = 'Can not create upload folder: '. $upload_path.$sub_folder;
				return;
			}
		}elseif( !is_writable( $upload_path.$sub_folder ) ){
			$this->errors[] = 'Folder: '. $upload_path.$sub_folder .' is not writtable. Make sure you have read/write permissions.';
			return;
		}
		//create the cache folder
		if( !file_exists( $upload_path.$sub_folder.'/'.$cache_folder.'/' ) ){
			mkdir( $upload_path.$sub_folder.'/'.$cache_folder.'/', 0777 );
			
			if( !file_exists( $upload_path.$sub_folder ) ){
				$this->errors[] = 'Can not create upload folder: '. $upload_path.$sub_folder.'/'.$cache_folder.'/';
				return;
			}
		}elseif( !is_writable( $upload_path.$sub_folder.'/'.$cache_folder.'/' ) ){
			$this->errors[] = 'Folder: '. $upload_path.$sub_folder.'/'.$cache_folder.'/' .' is not writtable. Make sure you have read/write permissions.';
			return;
		}
		
		if( !$types ){
			$types = $this->types;
		}
		
		if( !$default_type ){
			$default_type = 'jpg';
		}
		
		$profile = count( $this->profiles );
		
		$this->profiles[ $profile ] = array(
			'path'   => $upload_path,
			'url'    => $upload_url,
			'cache_path' => $upload_path.$sub_folder.'/'.$cache_folder.'/',
			'cache_url' => $upload_url.$sub_folder.'/'.$cache_folder.'/',
			'folder' => $sub_folder,
			'types' => $types,
			'quality' => $quality,
			'default_type' => $default_type,
			'default_background' => $default_background,
			'use_cache' => $use_cache
		);
		return $profile;
	}
	
	public function embed_resize( $profile, $html, $width, $height, $scale = false ){
		
		$style = '';
		
		if( $width ){
			$html = preg_replace( "/\s+width=\"(\d+)\"/", " width=\"".$width."\"", $html );
			
			$style .= ' width:'.$width.'px;';
		}
		
		if( $height ){
			$html = preg_replace( "/\s+height=\"(\d+)\"/", " height=\"".$height."\"", $html );
			
			$style .= ' height:'.$height.'px;';
		}
		
		return '<div style="'.trim( $style ).'" class="otw_ier">'.$html.'</div>';
	}
	
	public function resize( $profile, $image_path, $width, $height, $scale = false, $base_path = false, $white_space = true, $background_color = false ){
	
		if( !$image_path ){
			return;
		}
		
		$scale_settings = array();
		$scale_settings[0] = false;
		$scale_settings[1] = false;
		$scale_url_name    = 0;
		if( $scale ){
			
			if( preg_match( "/^(top|center|bottom)_(left|center|right)$/", $scale, $scale_matches ) ){
				
				$scale_settings[0] = $scale_matches[1];
				$scale_settings[1] = $scale_matches[2];
				$scale_url_name = $scale_matches[1]{0}.'_'.$scale_matches[2]{0};
			}else{
				$scale = false;
			}
		}
		
		if( !$base_path && isset( $_SERVER['DOCUMENT_ROOT'] ) ){
			$base_path = $_SERVER['DOCUMENT_ROOT'];
		}
		
		$background_url_code = 0;
		$background = $this->profiles[ $profile ]['default_background'];
		
		if( preg_match( "/^\#?([a-zA-Z0-9]{6})$/", $background_color, $color_matches ) ){
			$background_url_code = $color_matches[1];
			$background = $this->html2rgb( $color_matches[1] );
		}
		
		$result_url = $image_path;
		
		$file_info = pathinfo( $image_path );
		
		$thumb_file_name = $file_info['filename'].'_'.@filemtime( $base_path.$image_path ).'_'.$width.'X'.$height.'_'.$scale_url_name.'_'.intval( $white_space ).'_'.$background_url_code.'.'.$this->profiles[ $profile ]['default_type'];
		
		if( $this->profiles[ $profile ]['use_cache'] ){
			
			if( is_file( $this->profiles[ $profile ]['cache_path'].$thumb_file_name ) ){
				
				return $this->profiles[ $profile ]['cache_url'].$thumb_file_name;
			}
		}
		
		$thumb_info = array();
		$move_info = array();
		
		$move_info['left'] = 0;
		$move_info['top']  = 0;
		
		$bkg_info = array();
		$bkg_info['width'] = 0;
		$bkg_info['height'] = 0;
		
		$off_info = array();
		$off_info['width'] = 0;
		$off_info['height'] = 0;
		
		if( extension_loaded('gd') ){
			
			if( isset( $this->profiles[ $profile ] ) ){
				
				if( isset( $file_info['extension'] ) && in_array( $file_info['extension'], $this->profiles[ $profile ]['types'] ) ){
					
					$source_info = $this->get_image_info( $base_path.$image_path );
					
					if( count( $source_info ) ){
						
						if( !$scale || ( $width == 0 ) || ( $height == 0 ) ){
						
							if( ( $width != 0 ) && ( $height != 0 ) ){
								
								if( $source_info['width'] > $source_info['height'] ){
									
									if( round( $width / $height, 1  ) !=  round( $source_info['width'] / $source_info['height'] , 1 ) ){
										
										//try with the width
										$thumb_info['width']  = $width;
										$thumb_info['height'] = round( $source_info['height'] * ( $thumb_info['width'] / $source_info['width'] )  );
										
										if( $thumb_info['width'] <= $width && $thumb_info['height'] <= $height ){
											
										//	$move_info['top'] = ( $height - $thumb_info['height'] ) / 2;
										}
										else{
											$thumb_info['height'] = $height;
											$thumb_info['width'] = round( $height * ( $source_info['width'] / $source_info['height'] )  );
										//	$move_info['left'] = ( $width - $thumb_info['width'] ) / 2;
										}
										//$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
									}
									else{
										
										$thumb_info['width']  = $width;
										$thumb_info['height'] = round( $source_info['height'] * ( $thumb_info['width'] / $source_info['width'] )  );
									}
									
								}elseif( $source_info['width'] < $source_info['height'] ){
									
									if( round( $width / $height, 1  ) !=  round( $source_info['width'] / $source_info['height'] , 1 ) ){
										
										/*$bkg_info['width'] = $width;
										$bkg_info['height'] = $height;
										*/
										//try with the width
										$thumb_info['width']  = $width;
										$thumb_info['height'] = round( $source_info['height'] * ( $thumb_info['width'] / $source_info['width'] )  );
										
										if( $thumb_info['width'] <= $width && $thumb_info['height'] <= $height ){
										
										//	$move_info['top'] = ( $height - $thumb_info['height'] ) / 2;
										}
										else{
											$thumb_info['height'] = $height;
											$thumb_info['width'] = round( $height * ( $source_info['width'] / $source_info['height'] )  );
										//	$move_info['left'] = ( $width - $thumb_info['width'] ) / 2;
										}
									}
									else{
										
										$thumb_info['height'] = $height;
										$thumb_info['width']  = round( $source_info['width'] * ( $thumb_info['height'] / $source_info['height'] )  );
									}
									
								}else{
									if( round( $width / $height, 1  ) !=  round( $source_info['width'] / $source_info['height'] , 1 ) ){
										
										/*
										$bkg_info['width'] = $width;
										$bkg_info['height'] = $height;
										*/
										//try with the width
										$thumb_info['width']  = $width;
										$thumb_info['height'] = round( $source_info['height'] * ( $thumb_info['width'] / $source_info['width'] )  );
										
										if( $thumb_info['width'] <= $width && $thumb_info['height'] <= $height ){
										
										//	$move_info['top'] = ( $height - $thumb_info['height'] ) / 2;
										}
										else{
											$thumb_info['height'] = $height;
											$thumb_info['width'] = round( $height * ( $source_info['width'] / $source_info['height'] )  );
										//	$move_info['left'] = ( $width - $thumb_info['width'] ) / 2;
										}
									}else{
										
										$thumb_info['width']  = $width;
										$thumb_info['height'] = round( $source_info['height'] * ( $thumb_info['width'] / $source_info['width'] )  );
									}
								}
								
							}elseif( ( $width != 0 ) || ( $height == 0 ) ){
								
								$thumb_info['width']  = $width;
								$thumb_info['height'] = floor( $source_info['height'] * ( $thumb_info['width'] / $source_info['width'] ) );
								
								if( $thumb_info['width'] > $source_info['width'] ){
									
								//	$bkg_info['width'] = $width;
									
									$thumb_info['width'] = $source_info['width'];
									$thumb_info['height'] = $source_info['height'];
									
								//	$bkg_info['height'] = $thumb_info['height'];
									
								//	$move_info['left'] = ( $width - $thumb_info['width'] ) / 2;
								}
								
							}elseif( ( $width == 0 ) || ( $height != 0 ) ){
								
								$thumb_info['height'] = $height;
								$thumb_info['width']  = floor( $source_info['width'] * ( $thumb_info['height'] / $source_info['height'] ) );
								
								if( $thumb_info['height'] > $source_info['height'] ){
									
								//	$bkg_info['height'] = $height;
									
									$thumb_info['width'] = $source_info['width'];
									$thumb_info['height'] = $source_info['height'];
									
								//	$bkg_info['width'] = $thumb_info['width'];
									
								//	$move_info['top'] = ( $height - $thumb_info['height'] ) / 2;
								}
							}
							
							if( ( $thumb_info['width'] > $source_info['width'] ) && ( $thumb_info['height'] > $source_info['height'] ) ){
								$thumb_info['width']  = $source_info['width'];
								$thumb_info['height'] = $source_info['height'];
							}
						}else{
							$ratio = $width / $height;
							
							if( $ratio > 1 ){
								
								$thumb_info['height'] = $height;
								$thumb_info['width']  = $width;
								
								$off_info['width']  = 0;
								$off_info['height'] = 0;
								
								if( ( $thumb_info['width'] > $source_info['width'] ) && ( $thumb_info['height'] > $source_info['height'] ) ){
								
									$thumb_info['width']  = $source_info['width'];
									$thumb_info['height'] = $source_info['height'];
								}
								
								if( $source_info['width'] > $source_info['height'] ){
									
									if( round( $width / $height, 1  ) !=  round( $source_info['width'] / $source_info['height'] , 1 ) ){
										
										if( ( $source_info['width'] > $width ) && ( $source_info['height'] > $height ) ){
										
											$newHeight  = ceil(  $width * ( $source_info['height']  / $source_info['width'] )  );
											$newWidth   = ceil(  $height * ( $source_info['width']  / $source_info['height'] )  );
											
											if( $newHeight < $thumb_info['height'] ){
												
												//check if the width will match the size
												if( $newWidth >= $thumb_info['width'] ){
													
													$thumb_info['width'] = $newWidth;
												}else{
													$thumb_info['height'] = $newHeight;
												}
												
												$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
												
												$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
											}
											else{
												
												$thumb_info['height'] = $newHeight;
												
												$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
												
												$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
											}
										}
										elseif( ( $source_info['width'] <= $width ) && ( $source_info['height'] > $height ) ){
										
											$thumb_info['width'] = $source_info['width'];
											$thumb_info['height']  = ceil(  $thumb_info['width'] * ( $source_info['height']  / $source_info['width'] )  );
											
											$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
											
											$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										}
										elseif( ( $source_info['width'] > $width ) && ( $source_info['height'] <= $height ) ){
											
											$thumb_info['height'] = $source_info['height'];
											$thumb_info['width']  = ceil( $thumb_info['height'] * ( $source_info['width']  / $source_info['height'] )  );
											
											$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
											
											$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										}
										elseif( ( $source_info['width'] < $width ) && ( $source_info['height'] < $height ) ){
											
											$thumb_info['height'] = $source_info['height'];
											$thumb_info['width'] = $source_info['width'];
											
											$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
											
											$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										}
									}
								}
								elseif( $source_info['width'] < $source_info['height'] ){
								
									if( round( $width / $height, 1  ) !=  round( $source_info['width'] / $source_info['height'] , 1 ) ){
										
										if( $width > $source_info['width'] ){
											
											$thumb_info['width']  = $source_info['width'];
											$thumb_info['height'] = $source_info['height'];
											
											$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
											
											$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
											
											
										}else{
											$thumb_info['height'] = ceil( $width * (  $source_info['height'] / $source_info['width']  ) );
											
											$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
											
											$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										}
									}else{
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
									}
								}
								else{
									
									$thumb_info['width']  = $width;
									$thumb_info['height'] = ceil( $width * (  $source_info['height'] / $source_info['width']  ) );
									
									$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
									
									$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									
								}
								
							}elseif( $ratio < 1 ){
								
								$thumb_info['height'] = $height;
								$thumb_info['width']  = $width;
								
								if( $source_info['width'] > $source_info['height'] ){
									
									if( $source_info['width'] < $width && $source_info['height'] < $height ){
									
										$thumb_info['height']  = $source_info['height'];
										$thumb_info['width']   = $source_info['width'];
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									
									}else{
										$thumb_info['height']  = $height;
										$thumb_info['width']   = ceil(  $height * ( $source_info['width']  / $source_info['height'] )  );
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									}
								}
								elseif( $source_info['width'] < $source_info['height'] ){
									
									if( $height > $source_info['height']  ){
										
										$thumb_info['height'] = $source_info['height'];
										$thumb_info['width']   = ceil(  $thumb_info['height'] / ( $source_info['height']  / $source_info['width'] ) );
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										
									}else{
										$thumb_info['height'] = $height;
										$thumb_info['width']   = ceil(  $thumb_info['height'] / ( $source_info['height']  / $source_info['width'] ) );
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									}
								}else{
									
									$thumb_info['width'] =  ceil( $height * ( $source_info['width']  / $source_info['height'] ) );
									
									$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
									
									$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									
								}
								
							}else{
								
								if( $source_info['width'] > $source_info['height'] ){
								
									$thumb_info['width']  = $width;
									$thumb_info['height'] = $height;
									
									
									if( $thumb_info['width'] > $source_info['width'] || $thumb_info['height'] > $source_info['height'] ){
									
										$thumb_info['width'] = $source_info['width'];
										$thumb_info['height'] = $source_info['height'];
										$off_info['width']   = 0;
										$off_info['height']  = 0;
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										
									}else{
										
										if( $source_info['width'] > $source_info['height'] ){
											$thumb_info['width']   = ceil(  $thumb_info['height'] / ( $source_info['height']  / $source_info['width'] ) );
										}elseif( $source_info['width'] > $source_info['height'] ){
											$thumb_info['height']   = ceil(  $thumb_info['width'] / ( $source_info['width']  / $source_info['height'] ) );
										}
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									}
								}
								elseif( $source_info['width'] < $source_info['height'] ){
									
									$thumb_info['width']  = $width;
									$thumb_info['height'] = $height;
									
									if( $thumb_info['width'] > $source_info['width'] && $thumb_info['height'] > $source_info['height'] ){
										
										$thumb_info['width'] = $source_info['width'];
										$thumb_info['height'] = $source_info['height'];
										$off_info['width']   = 0;
										$off_info['height']  = 0;
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										
									}
									elseif( $thumb_info['width'] < $source_info['width'] && $thumb_info['height'] < $source_info['height'] ){
										
										if( $source_info['width'] < $source_info['height'] )
										{
											$thumb_info['height'] = floor( $height * ( $source_info['height']  / $source_info['width'] ) );
											
											$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
											
											$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
										}
									}
									elseif( $thumb_info['width'] > $source_info['width'] && $thumb_info['height'] <= $source_info['height'] ){
										
										$thumb_info['width'] = $source_info['width'];
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									}
									else{
										
										$off_info['height']   = 0;
										$off_info['width']  = 0;
									}
								}
								else{
									
									$thumb_info['width']  = $width;
									$thumb_info['height'] = $height;
									
									if( $thumb_info['width'] > $source_info['width'] && $thumb_info['height'] > $source_info['height'] ){
										
										$thumb_info['width'] = $source_info['width'];
										$thumb_info['height'] = $source_info['height'];
										$off_info['width']   = 0;
										$off_info['height']  = 0;
										
										$bkg_info = $this->bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space );
										
										$move_info = $this->add_move( $thumb_info, $bkg_info['width'], $bkg_info['height'], $move_info, $scale_settings );
									}
								}
							}
						
						}
						
						$thumb_res = false;
						
						if( $bkg_info['width'] && $bkg_info['height'] ){
							
							$thumb_res = imagecreatetruecolor( $bkg_info['width'], $bkg_info['height'] );
							$bg_color = imagecolorallocate( $thumb_res, $background[0], $background[1], $background[2] );
							imagefill( $thumb_res , 0,0 , $bg_color);
						}else{
							$thumb_res = imagecreatetruecolor( $thumb_info['width'], $thumb_info['height'] );
						}
						
						if( $thumb_res ){
							
							$source_res = $this->get_image_resource( $base_path.$image_path, $source_info );
							
							if( $source_res ){
							
								$valid = false;
								
								if( @imagecopyresampled( $thumb_res, $source_res, $move_info['left'], $move_info['top'], $off_info['width'], $off_info['height'], $thumb_info['width'], $thumb_info['height'], $source_info['width'], $source_info['height'] ) ){
								
									$valid = true;
								}
								imagedestroy( $source_res );
								
								if( $valid ){
									
									switch( $this->profiles[ $profile ]['default_type'] ){
									
										case 'jpg':
										case 'jpeg':
												imagejpeg( $thumb_res, $this->profiles[ $profile ]['cache_path'].$thumb_file_name, $this->profiles[ $profile ]['quality'] );
											break;
										case 'png':
												imagepng( $thumb_res, $this->profiles[ $profile ]['cache_path'].$thumb_file_name, round( $this->profiles[ $profile ]['quality'] / 10 ) - 1);
											break;
										case 'gif':
												imagegif( $thumb_res, $this->profiles[ $profile ]['cache_path'].$thumb_file_name, $this->profiles[ $profile ]['quality'] );
											break;
									}
									
									if( is_file( $this->profiles[ $profile ]['cache_path'].$thumb_file_name ) ){
										return $this->profiles[ $profile ]['cache_url'].$thumb_file_name;
									}
								}
							}
						}
					}
				}
			}
		}
		
		return $result_url;
	}
	
	private function bkg_info( $thumb_info, $width, $height, $bkg_info, $white_space ){
		
		if( $white_space ){
			$bkg_info['width']  = $width;
			$bkg_info['height'] = $height;
		}else{
			if( $width > $thumb_info['width'] ){
				$bkg_info['width'] = $thumb_info['width'];
			}else{
				$bkg_info['width']  = $width;
			}
			
			if( $height > $thumb_info['height'] ){
				$bkg_info['height'] = $thumb_info['height'];
			}else{
				$bkg_info['height']  = $height;
			}
		}
		
		return $bkg_info;
	}
	
	private function add_move( $thumb_info, $width, $height, $move_info, $scale_settings ){
		
		if( $thumb_info['width'] > $width ){
			
			switch( $scale_settings[1] ){
			
				case 'left':
						$move_info['left'] = 0;
					break;
				case 'right':
						$move_info['left'] = ( $width - $thumb_info['width'] );
					break;
				default:
						$move_info['left'] = ( $width - $thumb_info['width'] ) / 2 ;
					break;
			}
			
		}elseif( $thumb_info['width'] < $width ){
			
			switch( $scale_settings[1] ){
			
				case 'left':
						$move_info['left'] = 0;
					break;
				case 'right':
						$move_info['left'] = ( $width - $thumb_info['width'] );
					break;
				default:
						$move_info['left'] = ( $width - $thumb_info['width'] ) / 2 ;
					break;
			}
		}
		if( $thumb_info['height'] > $height ){
		
			switch( $scale_settings[0] ){
				
				case 'top':
						$move_info['top'] = 0;
					break;
				case 'bottom';
						$move_info['top'] = -( ( $thumb_info['height'] - $height ) );
					break;
				default:
						$move_info['top'] = -( ( $thumb_info['height'] - $height ) / 2 );
					break;
			}
			
		}elseif( $thumb_info['height'] < $height ){
		
			switch( $scale_settings[0] ){
				
				case 'top':
						$move_info['top'] = 0;
					break;
				case 'bottom';
						$move_info['top'] = -( ( $thumb_info['height'] - $height ) );
					break;
				default:
						$move_info['top'] = -( ( $thumb_info['height'] - $height ) / 2 );
					break;
			}
			
		}
		
		return $move_info;
	
	}
	
	public function render_errors(){
	
		if( count( $this->errors ) ){
			echo '<div id="message" class="error">';
			echo implode( '<br />', $this->errors );
			echo '</div>';
		}
	}
	
	public function get_image_info( $image_path ){
		
		$info = array();
		
		if( is_file( $image_path ) ){
			
			$size_info = @getimagesize( $image_path );
			
			if( count( $size_info ) ){
				
				$info['width'] = $size_info[0];
				$info['height'] = $size_info[1];
				$info['mime'] = $size_info['mime'];
			}
		}
		return $info;
	}
	
	public function get_image_resource( $image_path, $source_info ){
	
		$res = false;
		switch( $source_info['mime'] ){
			
			case 'image/jpeg':
					$res = imagecreatefromjpeg( $image_path );
				break;
			case 'image/png':
					$res = imagecreatefrompng( $image_path );
				break;
			case 'image/bmp':
			case 'image/x-windows-bmp':
					$res = imagecreatefromxbm( $image_path );
				break;
			case 'image/gif':
					$res = imagecreatefromgif( $image_path );
				break;
		}
		return $res;
	}
	
	public static function html2rgb( $color ){
		
		if( strlen( $color ) == 6 ){
			list( $r, $g, $b ) = array( 
				$color{0}.$color{1},
				$color{2}.$color{3},
				$color{4}.$color{5}
			);
		}else{
			return array( 0, 0, 0);
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );
		
		return array( $r, $g, $b );
	}
}

?>