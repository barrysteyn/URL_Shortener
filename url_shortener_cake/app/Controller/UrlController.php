<?PHP

class UrlController extends AppController {
	public $components = array('UrlEncode');	
	public $viewClass = 'Json';

	//Converts a shortened URL to its original format
	public function index() {
		$this->loadModel("Url");

		//No hash was given as input               
		if (!array_key_exists("hash", $this->request->query)) {
			$this->response->statusCode(403);
			$this->set('result',array("error"=>"No input given"));
		} else {
			$hashedUrl = $this->UrlEncode->parseHash($this->request->query["hash"]);
			if ($hashedUrl != -1) {
				$id = $this->UrlEncode->decodeToOriginalUrl($hashedUrl);
				$originalUrl = $this->Url->find("first", array("conditions" => array("Url.id"=>$id)));

				if (array_key_exists("Url", $originalUrl)) {
					$this->set('result', array("originalUrl"=>$originalUrl["Url"]["url"]));
				} else {
					//The URL does not exist in our database
					$this->response->statusCode(403);
					$this->set('result',array("error"=>"Url does not exist: {$this->request->query["hash"]}"));
				}
			} else {
				$this->response->statusCode(403);
				$this->set('result',array("error"=>"Hashed url host incorrect"));
			}
		}

		$this->set("_serialize", array("result"));
	}
}

?>
