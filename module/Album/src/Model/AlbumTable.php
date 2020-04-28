<?php
namespace Album\Model;

use Album\Model\Album;
use Laminas\Db\TableGateway\TableGatewayInterface;
use RuntimeException;

class AlbumTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAlbum($id)
    {
        $id = (int) $id;
        $formset = $this->tableGateway->select(['id' => $id]);
        $row = $formset->current();
        if (!$row) {
            throw new RuntimeException(
                sprintf("Couldn't find the record with id %d", $id)
            );
        }
        return $row;
    }
    public function saveAlbum(Album $album)
    {
        $data = [
            'artist' => $album->artist,
            'title' => $album->title,
        ];

        $id = (int) $album->id;
        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }
        try {
            $this->getAlbum($id);
        } catch (RuntimeException $e) {
             throw new RuntimeException(
                 sprintf("Can't update the Record with id %d", $id)
             );
        }
        $this->tableGateway->update($data, ['id'=>$id]);
    }
    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
