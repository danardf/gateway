<?php
namespace FreePBX\modules\Gateway;
use FreePBX\modules\Backup as Base;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Finder\Finder;
class Restore Extends Base\RestoreBase{
	
	/**
	 * truncate
	 *
	 * @param  string $table
	 * @return bool
	 */
	private function truncate($table){
		$db 		= $this->FreePBX->Database;
		$sql		= "TRUNCATE TABLE $table;";
		return $db->prepare($sql)->execute();
	}

	public function runRestore(){
		$config 	= $this->getConfigs();
		$db 		= $this->FreePBX->Database;
        
		/*	Restoring gateway data 	*/
		if($this->truncate("gateway") && !empty($config["gateway"])){
			foreach($config["gateway"] as $request){
                $request["dids"] = json_encode($request["dids"]);
                $sql  	= "INSERT INTO gateway (`extension`, `contact` ,`description` ,`address` ,`city` ,`zip_code`,`country`,`email`,`gateway`,`dids`) VALUES (:extension, :contact, :description, :address, :city, :zip_code, :country, :email, :gateway, :dids) ";
                $stm 	= $db->prepare($sql);
                $stm->execute(array(	':extension' 	=> $request["extension"],
                                        ':contact' 		=> $request["contact"],
                                        ':description' 	=> $request["description"],
                                        ':address' 		=> $request["address"],
                                        ':city' 		=> $request["city"],
                                        ':zip_code' 	=> $request["zip"],
                                        ':country' 		=> $request["country"],
                                        ':email' 		=> $request["email"],
                                        ':gateway' 		=> $request["gateway"],
                                        ':dids' 		=> json_decode($request["dids"], true)
                                    )
                                );

				$sql 	= "UPDATE sip SET data = 'from-internal-gateway' WHERE id = :extension AND keyword = 'context'";
				$stm 	= $db->prepare($sql);
				$stm->execute(array(":extension" => $request["extension"]));
			}
		}

	}

	public function processLegacy($pdo, $data, $tables, $unknownTables) {}
}
