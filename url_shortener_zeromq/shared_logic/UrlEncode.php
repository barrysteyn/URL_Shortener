<?PHP

class UrlEncode {
        //Will encode to a shortened URL by changing its number base
        public function encodeToShortenedUrl($id) {
            return base_convert($id, 10, 36);
        }

        //Will decode a shortened URL to its original URL
        public function decodeToOriginalUrl($shortenedURL) {
            return base_convert($shortenedURL, 36, 10);
        }

        //Enures a properly formed URL for both retrieval and storage
        public function returnUrl($url) {
            $urlParse = parse_url($url);

            //Will add a http as default
            if (!array_key_exists("scheme", $urlParse))
                    $url = "http://{$url}";

            return $url;
        }

	    public function parseHash($hashedUrl) {
		    $urlParse = parse_url($hashedUrl);
		    if (array_key_exists("host", $urlParse)) {
			    if ($urlParse["host"] != "localhost")
			    	return -1; //This is an error state - an incorrect host has been entered;
		    }
		
		    return $urlParse["path"];
	    }   	
}

?>
