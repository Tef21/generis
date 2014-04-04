<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author "Lionel Lecaque, <lionel@taotesting.com>"
 * @license GPLv2
 * @package generis
 
 *
 */
class common_persistence_sql_pdo_mysql_SchemaManager extends common_persistence_sql_pdo_SchemaManager{

    
    
    /**
     * Short description of method getIndexAlreadyExistsErrorCode
     *
     * @access public
     * @author Jerome Bogaerts, <jerome@taotesting.com>
     * @return string
     */
    public function getIndexAlreadyExistsErrorCode()
    {  
        return '42000';    
    }

    
    
    /**
     * Short description of method createIndex
     *
     * @access public
     * @author Jerome Bogaerts, <jerome@taotesting.com>
     * @param  string indexName
     * @param  string tableName
     * @param  array columns
     * @return void
     */
    public function createIndex($indexName, $tableName, $columns)
    {
        // section 10-13-1-85-69bd0289:13adae4f080:-8000:0000000000001BED begin
        $sql = 'CREATE INDEX ' . $indexName . ' ON ' . $tableName . ' (';
        $colsSql = array();
        foreach ($columns as $n => $l){
            // $n = name, $l = length
            if (!empty($l)){
                $colsSql[] =  $n . '(' . $l . ')';
            }
            else{
                $colsSql[] =  $n ;
            }
        }
    
        $colsSql = implode(',', $colsSql);
        $sql .= $colsSql . ')';
    
        $this->getDriver()->exec($sql);
        // section 10-13-1-85-69bd0289:13adae4f080:-8000:0000000000001BED end
    }
    
    

    
   
    


    

}