<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-17
 * Time: 下午4:12
 */

namespace MicroHome\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * 微信端展示的首页
 *
 * Class ShowMyPageController
 * @package MicroHome\Controller
 */
class ShowMyPageController extends AbstractRestfulController
{
    protected $infoTable;
    protected $imgTable;
    protected $userTable;
    protected $praiseTable;

    /**
     * 根据用户的OPEN_ID，查找当前用户发布的话题
     *
     * @param mixed $id
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function get($id)
    {

        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $userTable = $this->getUserTable();

        $info = $infoTable->getInfoByOpenIdAndNum($id,10)->toArray();

        foreach($info as $key=>$v){
            $img = $imgTable->getImgByInfoId($v['id'])->toArray();
            if(count($img)>0){
                $info["$key"]['img']= $img;
            }
            $user = $userTable->getUserByOpenId($id);
            $info["$key"]['username']=$user['username'];
            $info["$key"]['headurl']= $user['headurl'];
        }

        if($info!==false){
            return new JsonModel($info);
        }else{
            throw new \Exception('no status', 404);
        }

    }

    /**
     * 条件查询
     *
     * @param mixed $data
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function create($data)
    {
        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $praiseTable = $this->getPraiseTable();
        $userTable = $this->getUserTable();
        $info = $infoTable->getInfoByOpenIdAndInfoIdANdNum($data['open_id'],$data['info_id'],$data['num'])->toArray();
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
     * 以下，工厂类  表的实例化
     *
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

    public function getUserTable()
    {
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('MicroHome\Model\UserTable');
        }

        return $this->userTable;
    }
    public function getPraiseTable()
    {
        if(!$this->praiseTable){
            $sm = $this->getServiceLocator();
            $this->praiseTable = $sm->get('MicroHome\Model\PraiseTable');
        }

        return $this->praiseTable;
    }


}