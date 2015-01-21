<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-11-17
 * Time: 下午5:24
 */

namespace Exam\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Update;
use Zend\Db\TableGateway\AbstractTableGateway;

class UserScoreTable extends AbstractTableGateway implements AdapterAwareInterface
{

    protected $table = 'shujuxia_exam_userscore';
    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    public function get()
    {
        return $this->select();

    }

    public function getById($id)
    {
        return $this->select(array('id'=>$id));
    }

    public function createUserScore($data)
    {

        return $this->insert($data);
    }

    public function deleteUserScore($id)
    {
        return $this->delete(array('id'=>$id));
    }

    public function updateUserScore($id,$data)
    {
        return $this->update($data,array('id'=>$id));
    }

}