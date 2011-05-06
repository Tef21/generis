<?php

error_reporting(E_ALL);

/**
 * This class helps you to manage meta references to resources
 * (classes and instances). 
 * You can define the caching method by resource kind.
 * By default, the classes reference is cached in memory
 * and the instances are not cached
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package core
 * @subpackage kernel_persistence_hardapi
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/* user defined includes */
// section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000162A-includes begin
// section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000162A-includes end

/* user defined constants */
// section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000162A-constants begin
// section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000162A-constants end

/**
 * This class helps you to manage meta references to resources
 * (classes and instances). 
 * You can define the caching method by resource kind.
 * By default, the classes reference is cached in memory
 * and the instances are not cached
 *
 * @access public
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package core
 * @subpackage kernel_persistence_hardapi
 */
class core_kernel_persistence_hardapi_ResourceReferencer
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * single self instance
     *
     * @access private
     * @var ResourceReferencer
     */
    private static $_instance = null;

    /**
     * Short description of attribute CACHE_NONE
     *
     * @access public
     * @var int
     */
    const CACHE_NONE = 0;

    /**
     * Short description of attribute CACHE_MEMORY
     *
     * @access public
     * @var int
     */
    const CACHE_MEMORY = 1;

    /**
     * Short description of attribute classMode
     *
     * @access protected
     * @var int
     */
    protected $classMode = 0;

    /**
     * Short description of attribute instanceMode
     *
     * @access protected
     * @var int
     */
    protected $instanceMode = 0;

    /**
     * Short description of attribute _classes
     *
     * @access private
     * @var array
     */
    private static $_classes = array();

    /**
     * Short description of attribute _resources
     *
     * @access private
     * @var array
     */
    private static $_resources = array();

    // --- OPERATIONS ---

    /**
     * Short description of method __construct
     *
     * @access private
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    private function __construct()
    {
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001633 begin
        
    	//default cache values
		$this->classMode 	= self::CACHE_MEMORY;
		$this->instanceMode = self::CACHE_NONE;
    	
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001633 end
    }

    /**
     * Short description of method singleton
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return core_kernel_persistence_hardapi_ResourceReferencer
     */
    public static function singleton()
    {
        $returnValue = null;

        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001635 begin
        
        if (is_null(self::$_instance)){
			$class = __CLASS__;
        	self::$_instance = new $class();
        }
        $returnValue = self::$_instance;
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001635 end

        return $returnValue;
    }

    /**
     * Short description of method setClassCache
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  int mode
     * @return mixed
     */
    public function setClassCache($mode)
    {
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000164C begin
        
    	if($mode == self::CACHE_NONE || $mode == self::CACHE_MEMORY){
			$this->classMode = $mode;
		}
    	
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000164C end
    }

    /**
     * Short description of method setInstanceCache
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  int mode
     * @return mixed
     */
    public function setInstanceCache($mode)
    {
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000164F begin
        
    	if($mode == self::CACHE_NONE || $mode == self::CACHE_MEMORY){
			$this->instanceMode = $mode;
		}
    	
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000164F end
    }

    /**
     * Short description of method loadClasses
     *
     * @access private
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  boolean force
     * @return mixed
     */
    private function loadClasses($force = false)
    {
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001666 begin
        
    	if(count(self::$_classes) == 0 || $force){
			$dbWrapper = core_kernel_classes_DbWrapper::singleton();
			$result = $dbWrapper->execSql("SELECT `id`, `uri`, `table` FROM `class_to_table`");
			 while (!$result->EOF) {
	        	self::$_classes[] = array(
	        		'id'	=> $result->fields['id'],
	        		'uri' 	=> $result->fields['uri'],
	        		'table' => $result->fields['table']
	        	);
	        	$result->moveNext();
	        }
		}
    	
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001666 end
    }

    /**
     * Short description of method isClassReferenced
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Class class
     * @param  string table
     * @return boolean
     */
    public function isClassReferenced( core_kernel_classes_Class $class, $table = null)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001652 begin
        
        if(!is_null($class)){
			switch($this->classMode){
				
				case self::CACHE_NONE:
					$dbWrapper = core_kernel_classes_DbWrapper::singleton();
					if(is_null($table)){
						$result = $dbWrapper->execSql("SELECT `id` FROM `class_to_table` WHERE `uri` = ?", array($class->uriResource));
					}
					else{
						$result = $dbWrapper->execSql("SELECT `id` FROM `class_to_table` WHERE `uri` = ? AND table = ?", array($class->uriResource, $table));
					}
					
					if($result->RecordCount() > 0){
						$returnValue = true;
					}
					break;
					
				case self::CACHE_MEMORY:
					
					$this->loadClasses();
					
						if(is_null($table)){
							foreach(self::$_classes as $aClass){
								if(isset($aClass['uri']) && $aClass['uri'] == $class->uriResource ){
									$returnValue = true;
									break;
								}
							}
						}
						else{
							foreach(self::$_classes as $aClass){
							if(isset($aClass['uri']) && $aClass['uri'] == $class->uriResource 
								&& isset($aClass['table']) && $aClass['table'] == $table){
								$returnValue = true;
								break;
							}
						}
					}
					
					break;
			}
		}
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001652 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method referenceClass
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Class class
     * @param  string table
     * @return boolean
     */
    public function referenceClass( core_kernel_classes_Class $class, $table = null)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001655 begin
        
        if(!$this->isClassReferenced($class, $table)){
        	if(is_null($table)){
        		$table = core_kernel_persistence_hardapi_Utils::getShortName($class);
        	}
        	
			$dbWrapper = core_kernel_classes_DbWrapper::singleton();
			
			$query = "INSERT INTO `class_to_table` (`uri`, `table`) VALUES (?,?)";
			$result = $dbWrapper->execSql($query, array(
				$class->uriResource, 
				$table
			));
			if($result !== false){
				$returnValue = true;
				if($this->classMode == self::CACHE_MEMORY){
					$memQuery = "SELECT `id`, `uri`, `table` FROM `class_to_table` WHERE `uri` = ? AND `table` = ?";
					$memResult = $dbWrapper->execSql($memQuery, array($class->uriResource, $table));
					while(!$memResult->EOF){
						self::$_classes[] = array(
			        		'id'	=> $memResult->fields['id'],
			        		'uri' 	=> $memResult->fields['uri'],
			        		'table' => $memResult->fields['table']
			        	);
						$memResult->moveNext();
					}
				}
			}
		}
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001655 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method unReferenceClass
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Class class
     * @return boolean
     */
    public function unReferenceClass( core_kernel_classes_Class $class)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001658 begin
        
        if($this->isClassReferenced($class)){
			
			$tableName = core_kernel_persistence_hardapi_Utils::getShortName($class);
			
			$dbWrapper = core_kernel_classes_DbWrapper::singleton();
			
			$query = "DELETE class_to_table.*, resource_has_class.*, resource_to_table.* FROM class_to_table 
								INNER JOIN resource_has_class ON resource_has_class.class_id = class_to_table.id
								INNER JOIN resource_to_table ON resource_has_class.resource_id = resource_to_table.resource_id
								WHERE class_to_table.`table` = '{$tableName}' OR resource_to_table.`table` = '{$tableName}'";
		
//			$query = "DELETE class_to_table.*, resource_to_table.* FROM class_to_table 
//								INNER JOIN resource_to_table ON class_to_table.id = resource_to_table.id
//								WHERE class_to_table.`table` = '{$tableName}' OR resource_to_table.`table` = '{$tableName}'";
			
			$result = $dbWrapper->execSql($query);
			if($result !== false){
				$returnValue = true;
				if($this->classMode == self::CACHE_MEMORY){
					foreach(self::$_classes as $index => $aClass){
						if($aClass['uri'] == $class->uriResource){
							unset(self::$_classes[$index]);
						}
					}
				}
			}
		}
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001658 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method loadResources
     *
     * @access private
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  boolean force
     * @return mixed
     */
    private function loadResources($force = false)
    {
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000166E begin
        
    	if(count(self::$_resources) == 0 || $force){
			$dbWrapper = core_kernel_classes_DbWrapper::singleton();
			$result = $dbWrapper->execSql("SELECT `uri`, `table` FROM `resource_to_table`");
			while (!$result->EOF) {
	        	self::$_resources[] = array(
	        		'uri' 	=> $result->fields['uri'],
	        		'table' => $result->fields['table']
	        	);
	        	$result->moveNext();
	        }
		}
    	
    	
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000166E end
    }

    /**
     * Short description of method isResourceReferenced
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Resource resource
     * @return boolean
     */
    public function isResourceReferenced( core_kernel_classes_Resource $resource)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000165B begin
        
        if(!is_null($resource)){
			switch($this->instanceMode){
				
				case self::CACHE_NONE:
					$dbWrapper = core_kernel_classes_DbWrapper::singleton();
					$result = $dbWrapper->execSql("SELECT `id` FROM `resource_to_table` WHERE `uri` = ?", array($resource->uriResource));
					if($result->RecordCount() > 0){
						$returnValue = true;
					}
					break;
					
				case self::CACHE_MEMORY:
					
					$this->loadResources();
					foreach( self::$_resources as $res){
						if($res['uri'] == $resource->uriResource){
							$returnValue = true;
							break;
						}
					}
					break;
			}
		}
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000165B end

        return (bool) $returnValue;
    }

    /**
     * Short description of method referenceResource
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Resource resource
     * @param  string table
     * @param  array types
     * @param  boolean referenceClassLink
     * @return boolean
     */
    public function referenceResource( core_kernel_classes_Resource $resource, $table, $types = null, $referenceClassLink = false)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000165E begin
        $types = !is_null($types) ? $types : $resource->getType();
        if(!$this->isResourceReferenced($resource)){
			$dbWrapper = core_kernel_classes_DbWrapper::singleton();

        	// Get the resource id
			$queryResource = "SELECT id FROM `{$table}` WHERE uri=?";
        	$resultResource = $dbWrapper->execSql($queryResource, array($resource->uriResource));
			if($dbWrapper->dbConnector->errorNo() !== 0){
				throw new core_kernel_persistence_hardapi_Exception("Unable to select the resource to reference : {$resource->uriResource} : " .$dbWrapper->dbConnector->errorMsg());
			}
			if(!$resultResource->EOF){
				$resourceId = $resultResource->fields['id']; 
			}else {
				throw new core_kernel_persistence_hardapi_Exception("Unable to find the resource to reference : {$resource->uriResource} : " .$dbWrapper->dbConnector->errorMsg());
			}
			
			$query = "INSERT INTO `resource_to_table` (`uri`, `table`, `resource_id`) VALUES (?,?,?)";
			$insertResult = $dbWrapper->execSql($query, array($resource->uriResource, $table, $resourceId));
			if($dbWrapper->dbConnector->errorNo() !== 0){
				throw new core_kernel_persistence_hardapi_Exception("Unable to reference the resource : {$resource->uriResource} / {$table} : " .$dbWrapper->dbConnector->errorMsg());
			}
			if($referenceClassLink && $insertResult !== false){
				$query = "SELECT * FROM `resource_to_table` WHERE `uri` = ? AND `table` = ?";
				$result = $dbWrapper->execSql($query, array($resource->uriResource, $table));
				while($row = $result->fetchRow()){
					$rows[] = $row;
				}
			}
			$returnValue = (bool) $insertResult;
			
        	if($referenceClassLink){
				foreach($types as $type){
					
					$typeClass = new core_kernel_classes_Class($type->uriResource);
					if($this->isClassReferenced($typeClass)){
						
						$classLocations = $this->classLocations($typeClass);
						foreach ($classLocations as $classLocation){
							
							foreach($rows as $row){
								
								$query = "INSERT INTO resource_has_class (resource_id, class_id) VALUES (?,?)";
								$dbWrapper->execSql($query, array($row['resource_id'], $classLocation['id']));
							}
						}
					}
				}
			}
			if($returnValue && $this->instanceMode == self::CACHE_MEMORY){
				foreach($rows as $row){
					self::$_resources[] = array(
						'uri' 	=> $row['uri'],
						'table'	=> $row['table']
					);
				}
			}
        }
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:000000000000165E end

        return (bool) $returnValue;
    }

    /**
     * Short description of method unReferenceResource
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Resource resource
     * @return boolean
     */
    public function unReferenceResource( core_kernel_classes_Resource $resource)
    {
        $returnValue = (bool) false;
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001661 begin
        
        if($this->isResourceReferenced($resource)){
			$dbWrapper = core_kernel_classes_DbWrapper::singleton();
			
//			$query = "DELETE resource_to_table.*, resource_has_class.* FROM resource_to_table 
//						INNER JOIN resource_has_class ON resource_has_class.resource_id = resource_to_table.id
//						WHERE resource_to_table.uri = ?";

			$query = "DELETE resource_to_table.* FROM resource_to_table 
						WHERE resource_to_table.uri = ?";
			$result = $dbWrapper->execSql($query, array($resource->uriResource));
			if($result !== false){
				$returnValue = true;
				if($this->instanceMode == self::CACHE_MEMORY ){
					foreach( self::$_resources as $key =>  $res){
						if($res['uri'] == $resource->uriResource){
							unset(self::$_resources[$key]);
						}
					}
				}
			}
		}
        
        // section 127-0-1-1-8da8919:12f7878e80a:-8000:0000000000001661 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method resourceLocation
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Resource resource
     * @return string
     */
    public function resourceLocation( core_kernel_classes_Resource $resource)
    {
        $returnValue = (string) '';

        // section 127-0-1-1-56674b31:12fbf31d598:-8000:0000000000001505 begin
        
         if(!is_null($resource)){
			switch($this->instanceMode){
				
				case self::CACHE_NONE:
			        $dbWrapper = core_kernel_classes_DbWrapper::singleton();
			        
			        $query = "SELECT `table` FROM resource_to_table WHERE uri=? LIMIT 1";
			    	$result = $dbWrapper->execSql($query, array ($resource->uriResource));
					if($dbWrapper->dbConnector->errorNo() !== 0){
						
						throw new core_kernel_persistence_hardapi_Exception("Unable to define where is the hardified resource: " .$dbWrapper->dbConnector->errorMsg());
					} 
					else {
						if (!$result->EOF){
							$returnValue = $result->fields['table'];
						}
					}
			        break;
			
			   case self::CACHE_MEMORY:
			   		foreach( self::$_resources as $key =>  $res){
						if($res['uri'] == $resource->uriResource){
							$returnValue = $res['table'];
							break;
						}
					}
			   break;
			}
		}
        // section 127-0-1-1-56674b31:12fbf31d598:-8000:0000000000001505 end

        return (string) $returnValue;
    }

    /**
     * Short description of method classLocations
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Class class
     * @return array
     */
    public function classLocations( core_kernel_classes_Class $class)
    {
        $returnValue = array();

        // section 127-0-1-1-46522299:12fc0802dbc:-8000:00000000000016C7 begin
        
        if(!is_null($class)){
			switch($this->classMode){
				
				case self::CACHE_NONE:
			        $dbWrapper = core_kernel_classes_DbWrapper::singleton();
			        
			        $query = "SELECT id, uri, table FROM class_to_table WHERE uri=? ";
			    	$result = $dbWrapper->execSql($query, array ($class->uriResource));
					if($dbWrapper->dbConnector->errorNo() !== 0){
						throw new core_kernel_persistence_hardapi_Exception("Unable to define where is the hardified resource: " .$dbWrapper->dbConnector->errorMsg());
					} 
					else {
						while(!$result->EOF){
							$returnValue[] = $result->fields;
							$result->moveNext();
						}
					}
			        break;
			
			   case self::CACHE_MEMORY:
			   		foreach( self::$_classes as $key =>  $res){
						if($res['uri'] == $class->uriResource){
							$returnValue[] = $res;
						}
					}
			   break;
			}
		}
        
        // section 127-0-1-1-46522299:12fc0802dbc:-8000:00000000000016C7 end

        return (array) $returnValue;
    }

    /**
     * Short description of method referenceInstanceTypes
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @param  Class class
     * @return boolean
     */
    public function referenceInstanceTypes( core_kernel_classes_Class $class)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-46522299:12fc0802dbc:-8000:00000000000016C4 begin
        
        $dbWrapper = core_kernel_classes_DbWrapper::singleton();
        
        $query = "SELECT DISTINCT object FROM statements 
        			WHERE predicate = '".RDF_TYPE."' 
        			AND object != '{$class->uriResource}' 
         			AND subject IN (SELECT subject FROM statements 
        						WHERE predicate = '".RDF_TYPE."' 
        						AND object='{$class->uriResource}')";
        $result = $dbWrapper->execSql($query);
        if($dbWrapper->dbConnector->errorNo() !== 0){
        	throw new core_kernel_persistence_hardapi_Exception("Error by retrieving the other types of class {$class->uriResource}: " .$dbWrapper->dbConnector->errorMsg());
		} 
		$types = array();
        while(!$result->EOF){
        	$types[] = $result->fields['object'];
        	$result->moveNext();
        }
        
        $tableName = core_kernel_persistence_hardapi_Utils::getShortName($class);
        
        foreach($types as $type){
        	$this->referenceClass(new core_kernel_classes_Class($type), $tableName);
        }
        
        // section 127-0-1-1-46522299:12fc0802dbc:-8000:00000000000016C4 end

        return (bool) $returnValue;
    }

} /* end of class core_kernel_persistence_hardapi_ResourceReferencer */

?>