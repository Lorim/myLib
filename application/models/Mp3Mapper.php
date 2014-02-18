<?php

class Application_Model_Mp3Mapper {

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
            $this->setDbTable('Application_Model_DbTable_Mp3');
        }
        return $this->_dbTable;
    }

    public function save(Application_Model_Mp3 $mp3) {

        $data = array(
            'album' => $mp3->getAlbum(),
            'artist' => $mp3->getArtist(),
            'comment' => $mp3->getComment(),
            'filename' => $mp3->getFilename(),
            'genre' => $mp3->getGenre(),
            'hash' => $mp3->getHash(),
            'id' => $mp3->getId(),
            'path' => $mp3->getPath(),
            'title' => $mp3->getTitle(),
            'track' => $mp3->getTrack(),
            'year' => $mp3->getYear()
        );
        if (null === ($id = $mp3->getId())) {
            unset($data['id']);
            try {
                $this->getDbTable()->insert($data);
            } catch (Exception $ex) {
                
            }
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
    
    public function searchfile($path, $filename) {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()
                ->select()
                ->where("path = ? ", $path)
                ->where("filename = ?", $filename)
                );
        $row = $resultSet->current();
        if(!$row) return new Application_Model_Mp3();
        $oEntry = new Application_Model_Mp3();
        $oEntry->setAlbum($row->album)
                ->setArtist($row->artist)
                ->setComment($row->comment)
                ->setFilename($row->filename)
                ->setGenre($row->genre)
                ->setHash($row->hash)
                ->setId($row->id)
                ->setPath($row->path)
                ->setTitle($row->title)
                ->setTrack($row->track)
                ->setYear($row->year);
        return $oEntry;
    }
    
    public function fetchOne($id) {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()
                ->select()
                ->where("id = ? ", $id)
                );
        $row = $resultSet->current();
        $oEntry = new Application_Model_Mp3();
        $oEntry->setAlbum($row->album)
                ->setArtist($row->artist)
                ->setComment($row->comment)
                ->setFilename($row->filename)
                ->setGenre($row->genre)
                ->setHash($row->hash)
                ->setId($row->id)
                ->setPath($row->path)
                ->setTitle($row->title)
                ->setTrack($row->track)
                ->setYear($row->year);
        return $oEntry;
    }
    public function fetchAlben() {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()
                ->select()
                ->group('album')
                );
        $entries = array();
        foreach($resultSet as $row) {
            $entries[] = array(
                'album' => $row->album,
                'artist' => $row->artist,
            );
        }
        return $entries;
    }
    public function fetchAlbum($sAlbum) {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()
                ->select()
                ->where('album=?', $sAlbum)
                );
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Mp3();
            $entry->setAlbum($row->album)
                    ->setArtist($row->artist)
                    ->setComment($row->comment)
                    ->setFilename($row->filename)
                    ->setGenre($row->genre)
                    ->setHash($row->hash)
                    ->setId($row->id)
                    ->setPath($row->path)
                    ->setTitle($row->title)
                    ->setTrack($row->track)
                    ->setYear($row->year);
                $entries[] = $entry;
        }
        return $entries;
    }
    public function fetchAll() {
        $resultSet = $this->getDbTable()->fetchAll(
                $this->getDbTable()->select()
                );
        $entries = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Mp3();
            $entry->setAlbum($row->album)
                    ->setArtist($row->artist)
                    ->setComment($row->comment)
                    ->setFilename($row->filename)
                    ->setGenre($row->genre)
                    ->setHash($row->hash)
                    ->setId($row->id)
                    ->setPath($row->path)
                    ->setTitle($row->title)
                    ->setTrack($row->track)
                    ->setYear($row->year);
                $entries[] = $entry;
        }
        return $entries;
    }
}
