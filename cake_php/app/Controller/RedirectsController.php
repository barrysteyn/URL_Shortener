<?PHP

class RedirectsController extends AppController {
	public $components = array('UrlEncode');

	public function index($shortenedUrl) {
		$this->loadModel("Url");
		$host = Configure::read("HOST");

		//Handles case where nothing is passed in a very graceful way
		$shortenedUrl = $this->request->pass[1];

		if ($shortenedUrl) {
			$id = $this->UrlEncode->decodeToOriginalUrl($shortenedUrl);
			$originalUrl = $this->Url->find("first", array("conditions" => array("Url.id"=>$id)));

			//Redirect if url path exists (otherwise load up view)
			if (array_key_exists("Url", $originalUrl))
				$this->redirect($originalUrl["Url"]["url"]);
			else 
				$this->Session->setFlash("<div id='error' class='alert-error'><strong>http://{$host}/{$shortenedUrl}</strong> does not exist on our system.</div>");
		}
	}
}

?>
