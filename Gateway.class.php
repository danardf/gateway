<?php
namespace FreePBX\modules;
/*
 * Class stub for BMO Module class
 * In _Construct you may remove the database line if you don't use it
 * In getActionbar change extdisplay to align with whatever variable you use to decide if the page is in edit mode.
 *
 */

 /**
  * https://getbootstrap.com/docs/4.6/components/badge/
  */

class Gateway extends \DB_Helper implements \BMO  {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX 	= $freepbx;
		$this->db 		= $freepbx->Database;
		$this->astman 	= $this->FreePBX->astman;
	}

	//Install method. use this or install.php using both may cause weird behavior
	public function install() {}

	//Uninstall method. use this or install.php using both may cause weird behavior
	public function uninstall() {
		$gateways = $this->getAllGateway();
		foreach($gateways as $gateway){
			$sql 	= "UPDATE sip SET data = 'from-internal' WHERE id = :extension AND keyword = 'context'";
			$stm 	= $this->db->prepare($sql);
			$stm->execute(array(":extension" => $gateway["extension"]));
		}

		$sql		= "TRUNCATE TABLE gateway;";
		$this->db->prepare($sql)->execute();
	}

	//Not yet implemented
	public function backup() {}

	//not yet implimented
	public function restore($backup) {}

	//process form
	public function doConfigPageInit($page) {}

	//This shows the submit buttons
	public function getActionBar($request) {
		$buttons = [];
		if(!empty($request["action"])){
			if($request["action"] === "help"){
				return $buttons;
			}			
		}
		else{
			return $buttons;
		}
		switch($_GET['display']) {
			case 'gateway':
				$buttons = array(
					'submit' => array(
						'name' => 'submit',
						'id' => 'submit',
						'value' => _('Submit')
					),
					'Cancel' => array(
						'name' => 'cancel',
						'id' => 'cancel',
						'value' => _('Cancel')
					),
				);
			break;
		}
		return $buttons;
	}

	public function showPage(){
		$request = freepbxGetSanitizedRequest();
		$lang 	 = $_COOKIE["lang"];
		$jsloc 	 = "[]";
		if( file_exists(__DIR__."/i18n/$lang/LC_MESSAGES/gateway.json")){
			$jsloc = file_get_contents(__DIR__."/i18n/$lang/LC_MESSAGES/gateway.json");
		}
		$request["action"] = empty($request["action"]) ? "" : $request["action"];
		switch($request["action"]){
			case "add_gateway":
				$vars = array('title' => _("Add Gateway"));
				$vars["users"]		= $this->getUsers();	
				$vars["jsloc"] = $this->cleanJSLoc($jsloc);
				return load_view(__DIR__.'/views/add_gateway.php',$vars);
			case "edit_gateway":
				$vars = array('title' => _("Edit Gateway"));
				$vars["users"]		= $this->getUsers();
				$vars["gateway"]	= $this->getGateway($request["gateway"]);
				$vars["jsloc"] = $this->cleanJSLoc($jsloc);
				return load_view(__DIR__.'/views/edit_gateway.php',$vars);
			case "help":
				$vars = ["img" => "/admin/modules/gateway/"];
				$vars["jsloc"] = $this->cleanJSLoc($jsloc);
				return load_view(__DIR__.'/views/description.php', $vars);
			default:
				$vars = ['title' => _("Gateway List")];
				$vars["jsloc"] = $this->cleanJSLoc($jsloc);
				if(!empty($request["edit"]) && $request["edit"] === "no" ){
					$validate = $this->validate($request);
					if($validate["status"] == "false"){
						$vars["message"] = $validate["message"];
						return load_view(__DIR__.'/views/grid.php',$vars);
					}
					if( empty($this->getGateway($request["extension"]))){
						$request["dids"] = json_encode($request["dids"]);
						$sql  	= "INSERT INTO gateway (`extension`, `contact` ,`description` ,`address` ,`city` ,`zip_code`,`country`,`email`,`gateway`,`dids`) VALUES (:extension, :contact, :description, :address, :city, :zip_code, :country, :email, :gateway, :dids)";
						$stm 	= $this->db->prepare($sql);
						$stm->execute(array(	':extension' 	=> $request["extension"],
												':contact' 		=> $request["contact"],
												':description' 	=> $request["description"],
												':address' 		=> $request["address"],
												':city' 		=> $request["city"],
												':zip_code' 	=> $request["zip"],
												':country' 		=> $request["country"],
												':email' 		=> $request["email"],
												':gateway' 		=> strpos($request["gateway"],":") ? $request["gateway"] : $request["gateway"].":5060",
												':dids' 		=> $request["dids"]
											)
										);
	
						$sql 	= "UPDATE sip SET data = 'from-internal-gateway' WHERE id = :extension AND keyword = 'context'";
						$stm 	= $this->db->prepare($sql);
						$stm->execute(array(":extension" =>  $request['extension']));
						needreload();
					}
				}

				if(!empty($request["edit"]) && $request["edit"] === "yes" ){				
					$validate = $this->validate($request);
					if($validate["status"] === "false"){
						$vars["message"] = $validate["message"];
						return load_view(__DIR__.'/views/grid.php',$vars);
					}

					$request["dids"] = json_encode($request["dids"]);
					$sql    = "UPDATE gateway SET contact = :contact, description = :description, address = :address, city = :city, zip_code = :zip_code, country = :country, email = :email, gateway = :gateway, dids = :dids WHERE extension = :extension";
					$stm 	= $this->db->prepare($sql);
					$stm->execute(array(	':extension' 	=> $request["extension"],
											':contact' 		=> $request["contact"],
											':description' 	=> $request["description"],
											':address' 		=> $request["address"],
											':city' 		=> $request["city"],
											':zip_code' 	=> $request["zip"],
											':country' 		=> $request["country"],
											':email' 		=> $request["email"],
											':gateway' 		=> strpos($request["gateway"],":") ? $request["gateway"] : $request["gateway"].":5060",
											':dids' 		=> $request["dids"]
										)
									);
					needreload();
				}
				return load_view(__DIR__.'/views/grid.php',$vars);
		}

	}

	/**
	 * cleanJSLoc
	 *
	 * @param  string $jsloc
	 * @return string
	 */
	public function cleanJSLoc($jsloc){
		$jsloc_array = json_decode($jsloc, true);

		if (isset($jsloc_array['locale_data']['gateway'])) {
			foreach ($jsloc_array['locale_data']['gateway'] as $key => &$translation) {
				if (is_array($translation)) {		
					if (empty($translation[0]) && !empty($translation[1])) {
						array_shift($translation);
					} elseif (count($translation) === 1) {
						$translation[0] = $translation[0];
					}
				}
			}
		}
		return json_encode($jsloc_array, JSON_UNESCAPED_UNICODE);
	}

	/**
	 * getUsers
	 *
	 * @return array
	 */
	public function getUsers(){
		$allGateway = $this->getAllGateway();
		$sql 		= "SELECT users.extension, users.name FROM users INNER JOIN sip ON (sip.id = users.extension AND sip.data LIKE 'PJSIP/%' AND users.extension NOT LIKE '99%' AND users.extension NOT LIKE '98%') ORDER BY users.extension;";
		$stm 		= $this->db->prepare($sql);
		$stm->execute();
		$ret 		= $stm->fetchAll(\PDO::FETCH_ASSOC);
		$extGateway = array_column($allGateway, 'extension');
		$result 	= array_filter($ret, function($item) use ($extGateway) {
			return !in_array($item['extension'], $extGateway);
		});
		return array_values($result);
	}

	public function ajaxRequest($req, &$setting) {
		switch ($req) {
			case 'gatewayList':
			case 'delete':
				return true;
			default:
				return false;
			break;
		}
	}

	public function ajaxHandler(){
		$request = freepbxGetSanitizedRequest();
		switch ($request['command']) {
			case 'gatewayList':
				return $this->getAllGateway();
			case 'delete':
				$sql = "DELETE FROM gateway WHERE extension = :extension LIMIT 1";
				$stm = $this->db->prepare($sql);
				$ret = $stm->execute(array(":extension" => $request['gateway']));
				if($ret === false){
					return $ret;
				}
				$sql = "UPDATE sip SET data = 'from-internal' WHERE id = :extension AND keyword = 'context'";
				$stm = $this->db->prepare($sql);
				$ret = $stm->execute(array(":extension" =>  $request['gateway']));
				if($ret === false){
					return $ret;
				}
				needreload();
				return $ret;
			default:
				return false;
			break;
		}
	}
	
	/**
	 * validate
	 *
	 * @param  array $data
	 * @return array
	 */
	public function validate($data){
		if( !is_numeric($data["extension"])){
			return ["status" => "false", "message" => _("The extension is not numeric!")];
		}

		if(	!empty($data["contact"]) && !preg_match('/^[a-zA-Z0-9éèçàùô_\s\-]+$/u', trim($data["contact"]))){			
			return ["status" => "false", "message" => _("Contact Error: Wrong characters detected!")];
		}

		if( !empty($data["description"]) && !preg_match('/^[a-zA-Z0-9éèçàùô_\s\-]+$/u', trim($data["description"]))){			
			return ["status" => "false", "message" => _("Description Error: Wrong characters detected!")];
		}

		if(	!empty($data["address"]) && !preg_match('/^[a-zA-Z0-9éèçàùô_\s\-]+$/u', trim($data["address"]))){			
			return ["status" => "false", "message" => _("Address Error: Wrong characters detected!")];
		}

		if( !empty($data["city"]) && !preg_match('/^[a-zA-Z0-9éèçàùô_\s\-]+$/u', trim($data["city"]))){			
			return ["status" => "false", "message" => _("City Error: Wrong characters detected!")];
		}

		if( !empty($data["country"]) && !preg_match('/^[a-zA-Zéèçàùô_\s\-]+$/u', trim($data["country"]))){			
			return ["status" => "false", "message" => _("Country Error: Wrong characters detected!")];
		}

		if( !empty($data["zip"]) && !is_numeric(trim($data["zip"]))){
			return ["status" => "false", "message" => _("ZIP Code is not numeric!")];
		}

		if(!empty($data["email"]) && !filter_var(trim($data["email"]), FILTER_VALIDATE_EMAIL)){
			return ["status" => "false", "message" => _("Invalid email.")];
	   	}

		if(strpos($data["gateway"],":")){
			$gateway = explode(":", trim($data["gateway"]));
			if(!filter_var($gateway[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && !filter_var($gateway[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
				return ["status" => "false", "message" => _("Gateway Error: Invalid IP address!")];
			}
		}

		foreach($data["dids"] as $did){
			if(!is_numeric($did)){
				return ["status" => "false", "message" => _("Did: is not numeric!")];
			}
		}
		
		return ["status" => "true", "message" => ""];
	}

	public function getRightNav($request) {
		$html = load_view(__DIR__ . '/views/rnav.php', $request);
		return $html;
	}

	public function getAllGateway() {
		$sql = "SELECT * FROM gateway ORDER BY extension";
		$stm = $this->db->prepare($sql);
		$stm->execute();
		$ret = $stm->fetchAll(\PDO::FETCH_ASSOC);
		return $ret;
	}
	
	/**
	 * getGateway
	 *
	 * @param  string $gateway
	 * @return bool
	 */
	public function getGateway($gateway) {
		$sql = "SELECT * FROM gateway WHERE extension = :gateway LIMIT 1";
		$stm = $this->db->prepare($sql);
		$stm->execute([":gateway" => $gateway]);
		$ret = $stm->fetch(\PDO::FETCH_ASSOC);
		return $ret;
	}

	public static function myDialplanHooks() {
		return 900;
	}

	public function doDialplanHook(&$ext, $engine, $priority) {
		if ($engine != "asterisk") { return; }
		$context = "lock-feature-code";

		$fca = $this->FreePBX->Featurecodeadmin;

		$featurecodes = $fca->printExtensions()["items"];
		foreach($featurecodes as $featurecode){
			$fc = $featurecode[1];
			if(!empty($fc)){
				$ext->add($context, $fc, '', new \ext_Hangup);
			}			
		}

		$context = "from-trunk-gateway";
		$fc = "_X.";
		$ext->add($context, $fc, '', new \ext_goto(1,'${EXTEN}','ext-gateway'));
		$ext->add($context, 'h', '', new \ext_Hangup);

		$context = "from-internal-gateway";
		$ext->addInclude('from-internal-gateway','lock-feature-code');
		$ext->add($context, $fc, '', new \ext_set('number','${PJSIP_HEADER(read,From)}'));
		$ext->add($context, $fc, '', new \ext_set('number','${CUT(number,@,1)}'));
		$ext->add($context, $fc, '', new \ext_set('number','${CUT(number,:,2)}'));
		$ext->add($context, $fc, '', new \ext_set('CALLERID(num)','${number}'));
		$ext->add($context, $fc, '', new \ext_goto(1,'${EXTEN}','from-internal'));
		$ext->add($context, 'h', '', new \ext_Hangup);
		
		$context = "ext-gateway";
		$gateways = $this->getAllGateway();
		foreach($gateways as $gateway){
			$dids = json_decode($gateway["dids"], true);
			foreach($dids as $index => $did){
				if($index === 0){
					$main_user = '"'.$did.'"';
				}
				$ext->add($context, "$did", "", new \ext_noop('--- Root DID '.$main_user.' DID ${EXTEN} -----'));
				$ext->add($context, "$did", '', new \ext_set('mainuser',"$main_user"));
				$ext->add($context, "$did", '', new \ext_dial('PJSIP/${mainuser}/sip:${EXTEN}@'.$gateway["gateway"].',,Hhtrb(func-apply-sipheaders^s^1)'));				
			}
		}
		$ext->addInclude('ext-gateway','bad-number');
		$ext->add($context, "h", '', new \ext_Hangup);	
	}
}