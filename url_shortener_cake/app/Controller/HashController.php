<?PHP

class HashController extends AppController {
	public $components = array('UrlEncode');	
	public $viewClass = 'Json';

	//Returns a url's hash (if it exists)
	public function index() {
		$this->loadModel("Url");
		$host = Configure::read("HOST");

		if (!array_key_exists("url", $this->request->query) || $this->request->query['url'] == "") {
                        $this->response->statusCode(403);
			$this->set("result",array("error"=>"No input given or input is incorrect (Make sure there are no trailing spaces)"));
		} else {
			$this->request->query['url'] = trim($this->request->query['url']);
			$url = $this->Url->find("first", array("conditions" => array("Url.url" => $this->UrlEncode->returnUrl($this->request->query["url"]))));
			if (array_key_exists("Url", $url)) {
				$hash = $this->UrlEncode->encodeToShortenedUrl($url["Url"]["id"]);
				$this->set("result", array("hashedUrl"=>"http://{$host}/{$hash}"));
			} else {
				$this->response->statusCode(403);
				$this->set("result",array("error"=>"Url does not exist: {$this->request->query['url']}"));	
			}
		}

		$this->set("_serialize", array("result"));
	}

	//Creates a url hash
	public function add() {
		$this->loadModel("Url");
		$host = Configure::read("HOST");
		
		if (!array_key_exists("url", $this->request["data"])) {
                        $this->response->statusCode(403);
			$this->set("result",array("error"=>"No input given"));
		} else {
                        $data = array(
				"Url" => array("url" => $this->UrlEncode->returnUrl($this->request["data"]["url"]))
			);
			$this->Url->create($data);
			if ($this->Url->save($data)) {
				$shortenedUrl = $this->UrlEncode->encodeToShortenedUrl($this->Url->id);
				$this->set("result", array("hashedUrl"=>"http://{$host}/{$shortenedUrl}"));	
			} else {
				//There was an error when saving, this is only because the url already exists
				$url = $this->Url->find("first", array("conditions" => array("Url.url" => $this->UrlEncode->returnUrl($this->request["data"]["url"]))));
				if (array_key_exists("Url", $url)) {
					$hash = $this->UrlEncode->encodeToShortenedUrl($url["Url"]["id"]);
					$this->set("result", array("hashedUrl"=>"http://{$host}/{$hash}", "error"=>"Url already existed"));
				} else {
					$this->response->statusCode(403);
					$this->set("result",array("error"=>"Unspecified error: Please email barry.steyn@gmail.com with details"));	
				}
			}
		}

		$this->set("_serialize", array("result"));
	}
}

?>
