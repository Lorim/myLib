<?php

class Application_Model_AlbumMapper {

    protected $_dbTable;

    public function setDbTable($dbTable) {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Ungültiges Table Data Gateway angegeben');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Album');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Album $album) {
        $data = array(
            'album' => $album->getAlbum(),
            'artist' => $album->getArtist(),
            'id' => $album->getId(),
            'mbid' => $album->getMbid()
        );
        if (null === ($id = $album->getId())) {
            unset($data['id']);
            try {
                $this->getDbTable()->insert($data);
            } catch (Exception $ex) {
                
            }
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    public function fetchOneByName($album, $artist) {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()
                ->select()
                ->where("album = ? ", $album)
                ->where("artist = ?", $artist)
                );
        $row = $resultSet->current();
        
        $oEntry = new Application_Model_Album();
        if(!$row) {
            return $oEntry;
        }
        $oEntry->setAlbum($row->album)
                ->setArtist($row->artist)
                ->setId($row->id)
                ->setMbid($row->mbid);
        return $oEntry;
    }
    public function fetchOne($id) {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()
                ->select()
                ->where("id = ? ", $id)
                );
        $row = $resultSet->current();
        
        $oEntry = new Application_Model_Album();
        if(!$row) {
            return $oEntry;
        }
        $oEntry->setAlbum($row->album)
                ->setArtist($row->artist)
                ->setId($row->id)
                ->setMbid($row->mbid);
        return $oEntry;
    }
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()->select()
                );
        $entries = array();
        foreach ($resultSet as $row) {
            $oEntry = new Application_Model_Album();
            $oEntry->setAlbum($row->album)
                    ->setArtist($row->artist)
                    ->setId($row->id)
                    ->setMbid($row->mbid);
                $entries[] = $oEntry;
        }
        return $entries;
    }
}
