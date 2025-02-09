<?php
require DIR_WS_CLASSES . 'Model.php';
class Category extends Model {
    var $_data = array();
    function Category($data = array()) {
        $this->setData($data);
    }
    
    function save() {
        $arrSql = $this->prepareArray($this->toArray(), TABLE_CATEGORIES);
        tep_db_perform(TABLE_CATEGORIES, $arrSql, 'update', 'categories_id=\''.(int)$this->get('categories_id').'\'');
        return $this;
    }
    function refresh() {
        $sql = 'SELECT * FROM ' . TABLE_CATEGORIES . ' WHERE categories_id=' . (int)$this->get('categories_id');
        $this->_data = tep_db_fetch_array(tep_db_query($sql));
        return $this;
    }
    function saveDescription() {
        //TODO: finish saveDescription method
        return $this;
    }
}
?>