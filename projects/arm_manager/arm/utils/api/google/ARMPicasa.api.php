<?php 
/**
 * 
 * @author alanlucian
 *
 */
class ARMPicasaAPI {
	public static function init(){
		//
	}
	public static function getPublicAlbumMedia( $publicAlbumURL , $image_size = 1024 ){
		
		
// 		ARMDebug::print_r($publicAlbumURL);
		
		//https://plus.google.com/photos/103651147482744881666/albums/5858627894347575585?banner=pwa
		
		preg_match_all( "/photos\/([0-9]+)\/albums\/([0-9]+)/" , $publicAlbumURL, $out);
		
// 		ARMDebug::print_r( $out );	
		
		if( sizeof( $out[1] ) !== 1 )
			return array();
		
		$user_id = $out[1][0];
		
		$album_id = $out[2][0];
		
		$url = "https://picasaweb.google.com/data/feed/api/user/{$user_id}/albumid/{$album_id}/?imgmax={$image_size}";
		
// 		ARMDebug::li($url);
		
		$xml = new XMLReader();
// 		$xml->setSchema("http://schemas.google.com/photos/exif/2007") ;
		$xml->open( $url );
		var_dump( $xml);
 		var_dump( $xml->expand());
		
		
		$pictures = array();
		
		while( $xml->read() &&   $xml->name  !== 'entry' );

		// now that we're at the right depth, hop to the next <product/> until the end of the tree
		while ($xml->name === 'entry')
		{
			// either one should work
// 			$entry = new XMLReader();
			//$entry->readOuterXml(  );
			
			$entry = $xml->expand();
			 
			$picture = array();
			
			
			
			
			$items = $entry->getElementsByTagName( "group" ) ;// DOMNodeList
// 			$pictures[] = ARMDataHandler::DOMNodeListToArray( $items ) ;
// 			$xml->next('entry');
// 			continue;
// 			die;
// 			if( FALSE ) $items = new DOMNodeList();

// 			var_dump( $items, $items->length );
			for ($i = 0; $i < $items->length; $i++) {
				$contentDOMNodeList = $items->item( $i )->getElementsByTagName("content")  ;
				
				$content = array();
				for( $ii = 0 ; $ii < $contentDOMNodeList->length ; $ii++ ){
					$DOMElement = $contentDOMNodeList->item( $ii ) ;
					$content[]  = ARMDataHandler::DOMElementToObject( $DOMElement );;
				}
				
				$thumbnailDOMNodeList = $items->item( $i )->getElementsByTagName("thumbnail")  ;
				$thumbnail = array();
				for( $ii = 0 ; $ii < $thumbnailDOMNodeList->length ; $ii++ ){
					$DOMElement = $thumbnailDOMNodeList->item( $ii ) ;
					
					$thumbnail[]  = ARMDataHandler::DOMElementToObject( $DOMElement );
				}
				
				$picture = (object) array( "content"=> $content, "thumbnail"=> $thumbnail );
				
			}
			
			$pictures[] = $picture;
			
			$xml->next('entry');
		}
// 		var_dump( $pictures);

		return $pictures;
	}

	
	public static function debugDomElement(  $domNode , $indent = "" ){
		
		if( FALSE ) $domNode = new DOMNode();
		
		echo( $indent . $domNode->nodeName . "\n" );
		echo( $indent . $domNode->nodeValue . "\n");
		
// 		var_dump( $domNode->attributes );
		if( $domNode->hasAttributes() ){
			
			echo( $indent . "@----  " . "\n");
			
			$attr = $domNode->attributes ;
			
			for ($i = 0; $i < $attr->length; $i++) {
				
// 				var_dump( $attr->item($i) );
// 				self::debugDomNode( $attr->item($i) , $indent."\t");
				echo( $indent . $attr->item($i)->name . " = " . $attr->item($i)->value . "\n");
			}
			echo( $indent . "----@  " . "\n");
		}
		
		if( $domNode->hasChildNodes() ){
			
			echo( $indent . "<----  " . "\n");
			
			$items = $domNode->childNodes ;
				
			for ($i = 0; $i < $items->length; $i++) {
				self::debugDomElement( $items->item($i) , $indent."\t");
			}
			
			echo( $indent . "---> ". "\n");
		}
		
// 		$items = $entry->childNodes ;
			
// 		for ($i = 0; $i < $items->length; $i++) {
// 			var_dump( $items->item($i)->nodeValue ) ;
// 		}
// 		var_dump("===================================");
		// 			var_dump( $entry, $entry->hasChildNodes(), $entry->childNodes->item(0)->nodeName );
		
	}
	
}

/*
 
 
 USER:
 https://picasaweb.google.com/data/feed/api/user/106459362403726512890
 
 ALBUM:
 https://picasaweb.google.com/data/feed/api/user/106459362403726512890/albumid/5869662296289361873?imgmax=1024
 
 VIDEO:
 https://picasaweb.google.com/data/feed/api/user/106459362403726512890/albumid/5869662296289361873/photoid/5869775977866742034
 
 
 $data->entry 
 
[0]=>
    object(SimpleXMLElement)#165 (8) {
      ["id"]=>
      string(126) "https://picasaweb.google.com/data/entry/api/user/103651147482744881666/albumid/5858627894347575585/photoid/5858627898394998178"
      ["published"]=>
      string(24) "2013-03-23T19:48:24.000Z"
      ["updated"]=>
      string(24) "2013-04-22T18:03:24.138Z"
      ["category"]=>
      object(SimpleXMLElement)#209 (1) {
        ["@attributes"]=>
        array(2) {
          ["scheme"]=>
          string(37) "http://schemas.google.com/g/2005#kind"
          ["term"]=>
          string(43) "http://schemas.google.com/photos/2007#photo"
        }
      }
      ["title"]=>
      string(12) "DSC_0001.JPG"
      ["summary"]=>
      object(SimpleXMLElement)#210 (1) {
        ["@attributes"]=>
        array(1) {
          ["type"]=>
          string(4) "text"
        }
      }
      ["content"]=>
      object(SimpleXMLElement)#211 (1) {
        ["@attributes"]=>
        array(2) {
          ["type"]=>
          string(10) "images/jpeg"
          ["src"]=>
          string(101) "https://lh5.googleusercontent.com/-YzFgYw8HtXg/UU4HCIMG9aI/AAAAAAAA4XU/KiBaDSqEmSc/s1024/DSC_0001.JPG"
        }
      }
      ["link"]=>
      array(5) {
        [0]=>
        object(SimpleXMLElement)#212 (1) {
          ["@attributes"]=>
          array(3) {
            ["rel"]=>
            string(37) "http://schemas.google.com/g/2005#feed"
            ["type"]=>
            string(20) "application/atom+xml"
            ["href"]=>
            string(125) "https://picasaweb.google.com/data/feed/api/user/103651147482744881666/albumid/5858627894347575585/photoid/5858627898394998178"
          }
        }
        [1]=>
        object(SimpleXMLElement)#213 (1) {
          ["@attributes"]=>
          array(3) {
            ["rel"]=>
            string(9) "alternate"
            ["type"]=>
            string(9) "text/html"
            ["href"]=>
            string(80) "https://picasaweb.google.com/103651147482744881666/Despedida#5858627898394998178"
          }
        }
        [2]=>
        object(SimpleXMLElement)#214 (1) {
          ["@attributes"]=>
          array(3) {
            ["rel"]=>
            string(47) "http://schemas.google.com/photos/2007#canonical"
            ["type"]=>
            string(9) "text/html"
            ["href"]=>
            string(81) "https://picasaweb.google.com/lh/photo/JUpIiFAMqqdyELHRyLOsq9MTjNZETYmyPJy0liipFm0"
          }
        }
        [3]=>
        object(SimpleXMLElement)#215 (1) {
          ["@attributes"]=>
          array(3) {
            ["rel"]=>
            string(4) "self"
            ["type"]=>
            string(20) "application/atom+xml"
            ["href"]=>
            string(126) "https://picasaweb.google.com/data/entry/api/user/103651147482744881666/albumid/5858627894347575585/photoid/5858627898394998178"
          }
        }
        [4]=>
        object(SimpleXMLElement)#216 (1) {
          ["@attributes"]=>
          array(3) {
            ["rel"]=>
            string(44) "http://schemas.google.com/photos/2007#report"
            ["type"]=>
            string(9) "text/html"
            ["href"]=>
            string(119) "https://picasaweb.google.com/lh/reportAbuse?uname=103651147482744881666&aid=5858627894347575585&iid=5858627898394998178"
          }
        }
      }
    }
    
    */