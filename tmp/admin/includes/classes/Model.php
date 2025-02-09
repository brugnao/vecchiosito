<?php
class Model {
    function Model($data = array()) {
    }
    function setData($data) {
        if(is_array($data)) {
            $this->_data = $data;
        }
        return $this;
    }
    function get($key) {
        $res = false;
        if(isset($this->_data[$key])) {
            $res = $this->_data[$key];
        }
        return $res;
    }
    function set($key, $val) {
        $this->_data[$key] = $val;
        return $this;
    }
    function toArray() {
        return $this->_data;
    }
    function clear() {
        $this->_data = array();
    }
    function getName() {
        return get_class($this);
    }
    function getVars() {
        return get_class_vars($this->getName());
    }
    function getMethods() {
        return get_class_methods($this);
    }
    function destroy() {
        $vars = $this->getVars();
        foreach($vars as $key=>$vname) {
            $this->$key = null;
        }
        $vars = null;
        unset($this);
        return false;
    }
    function getTableColumns($strTable = '') {
        $sql = 'SHOW COLUMNS FROM ' . $strTable;
        $query = tep_db_query($sql);
        $arrColumns = array();
        while($v=tep_db_fetch_array($query)) {
            $arrColumns[] = $v['Field'];
        }
        return $arrColumns;
    }
    function prepareArray($arrSql, $table) {
        $arrCols = $this->getTableColumns($table);
        $arrRes = array();
        foreach($arrSql as $k=>$v) {
            if(in_array(trim($k), $arrCols)) {
                $arrRes[trim($k)]=$v;
            }
        }
        return $arrRes;
    }
}
?>