<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-22
 * Time: 上午11:19
 */

namespace MicroHome\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * 条件查询类
 *
 * Class GetConditionInfoController
 * @package MicroHome\Controller
 */
class GetConditionInfoController extends AbstractRestfulController
{
    protected $infoTable;
    protected $imgTable;
    protected $commentTable;
    protected $praiseTable;
    protected $userTable;

    //条件查询方法，传进一个数组，里面带有要查询的参数
    public function create($data)
    {
        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $praiseTable = $this->getPraiseTable();
        $userTable = $this->getUserTable();
        $info = $infoTable->getInfoByIdAndNum($data['id'],$data['num'])->toArray();
        if(count($info)>0){
            foreach($info as $key=>$v){
                $user = $userTable->getUserByOpenId($v['open_id']);
                if(count($user)>0){
                    $info["$key"]['username']=$user['username'];
                    $info["$key"]['headurl']= $user['headurl'];
                }
                $img = $imgTable->getImgByInfoId($v['id'])->toArray();

                if(count($img)>0){
                    $info["$key"]['img']= $img;
                }
                $temp = array();
                $praise = $praiseTable->getPraiseByInfoId($v['id'])->toArray();
                foreach($praise as $k=>$i){
                    $temp["$k"] = $i['open_id'];
                }

                if(count($praise)>0){
                    $info["$key"]['praise_open_id']=$temp;
                }
            }

        }

        if($info!==false){
            return new JsonModel($info);
        }else{
            throw new \Exception('no status', 404);
        }

    }

    /**
     * 工厂类堆表的实例化
     * @return array|object
     */

    public function getInfoTable()
    {
        if(!$this->infoTable){
            $sm = $this->getServiceLocator();
            $this->infoTable = $sm->get('MicroHome\Model\InfoTable');
        }

        return $this->infoTable;
    }

    public function getImgTable()
    {
        if(!$this->imgTable){
            $sm = $this->getServiceLocator();
            $this->imgTable = $sm->get('MicroHome\Model\ImgTable');
        }

        return $this->imgTable;
    }
    public function getPraiseTable()
    {
        if(!$this->praiseTable){
            $sm = $this->getServiceLocator();
            $this->praiseTable = $sm->get('MicroHome\Model\PraiseTable');
        }

        return $this->praiseTable;
    }
    public function getUserTable()
    {
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('MicroHome\Model\UserTable');
        }

        return $this->userTable;
    }
}