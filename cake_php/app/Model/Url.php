<?PHP
class Url extends AppModel{
	public $validate = array("url" => "isUnique");
}

?>
