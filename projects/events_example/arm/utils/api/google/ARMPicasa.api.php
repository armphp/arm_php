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

	/**
	 * se souber o user id e o album envie
	 * @param $user_id
	 * @param $album_id
	 * @param int $image_size
	 * @return array
	 */
	public static function getPublicAlbumByUserAlbum($user_id, $album_id, $image_size = 1024){
		$url = "https://picasaweb.google.com/data/feed/api/user/{$user_id}/albumid/{$album_id}/?imgmax={$image_size}";

		//TODO: Refazer a API

// 		ARMDebug::li($url);

		$xml = new XMLReader();
		$xml->open( $url );


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
	/*
	 <entry>
		<id>http://picasaweb.google.com/data/entry/api/user/116802681592967253904/albumid/6281653638875239553</id>
		<published>2016-05-01T07:00:00.000Z</published>
		<updated>2016-05-06T19:37:22.569Z</updated>
		<category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/photos/2007#album'/>
		<title type='text'>UndoKai - May 1, 2016</title>
		<summary type='text'>Demonstration kihon, idogeiko and kata</summary>
		<rights type='text'>public</rights>
		<link rel='http://schemas.google.com/g/2005#feed' type='application/atom+xml'
			  href='http://picasaweb.google.com/data/feed/api/user/116802681592967253904/albumid/6281653638875239553'/>
		<link rel='alternate' type='text/html'
			  href='https://picasaweb.google.com/116802681592967253904/6281653638875239553'/>
		<link rel='self' type='application/atom+xml'
			  href='http://picasaweb.google.com/data/entry/api/user/116802681592967253904/albumid/6281653638875239553'/>
		<author>
			<name>Wata Watanabe</name>
			<uri>https://picasaweb.google.com/116802681592967253904</uri>
		</author>
		<gphoto:id>6281653638875239553</gphoto:id>
		<gphoto:name>6281653638875239553</gphoto:name>
		<gphoto:location>Nikkey Club Marilia SP-Brazil</gphoto:location>
		<gphoto:access>public</gphoto:access>
		<gphoto:timestamp>1462086000000</gphoto:timestamp>
		<gphoto:numphotos>54</gphoto:numphotos>
		<gphoto:user>116802681592967253904</gphoto:user>
		<gphoto:nickname>Wata Watanabe</gphoto:nickname>
		<media:group>
			<media:content
				url='https://lh3.googleusercontent.com/-UTA1Q_aiLII/Vyzqs_My1IE/AAAAAAAAAec/YEbrZRRGVmUUSrXmv9lpO_HyNWQnvgwUwCHMQAQ/6281653638875239553'
				type='image/jpeg' medium='image'/>
			<media:credit>Wata Watanabe</media:credit>
			<media:description type='plain'>Demonstration kihon, idogeiko and kata</media:description>
			<media:keywords/>
			<media:thumbnail
				url='https://lh3.googleusercontent.com/-UTA1Q_aiLII/Vyzqs_My1IE/AAAAAAAAAec/YEbrZRRGVmUUSrXmv9lpO_HyNWQnvgwUwCHMQAQ/s160-c/6281653638875239553'
				height='160' width='160'/>
			<media:title type='plain'>UndoKai - May 1, 2016</media:title>
		</media:group>
	</entry>
	 */
	public static function getPublicAlbuns( $publicAlbumURL ){
		$url = "http://picasaweb.google.com/data/feed/api/user/".$publicAlbumURL ;
		/* @var $obj SimpleXMLElement  */
		$obj = simplexml_load_string( str_replace(array("gphoto:", "media:"), array("gphoto_","media_"), file_get_contents($url) ) ) ;
		return json_decode( json_encode($obj) );
	}


}

/*
 
 
 USER:
 https://picasaweb.google.com/data/feed/api/user/106459362403726512890
	http://picasaweb.google.com/data/feed/api/user/116802681592967253904/
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
          string(10) "image/jpeg"
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