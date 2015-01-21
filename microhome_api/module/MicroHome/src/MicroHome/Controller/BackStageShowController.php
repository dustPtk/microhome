<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-19
 * Time: 下午2:25
 */

namespace MicroHome\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class BackStageShowController extends AbstractRestfulController
{
    protected $infoTable;
    protected $imgTable;
    protected $scrutinyTable;
    protected $userTable;

    /**
     * @return JsonModel|mixed
     * @throws \Exception
     */
    public function getList()
    {
        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $scrutinyTable = $this->getScrutinyTable();
        $userTable = $this->getUserTable();


        $info = $infoTable->getInfoAll()->toArray();

        foreach($info as $key =>$v){

            $img = $imgTable->getImgByInfoId($v['id'])->toArray();

            if(count($img)>0){
                $info["$key"]['img']=$img;
            }

            $scrutiny = $scrutinyTable->getByInfoId($v['id'])->toArray();

            if(count($scrutiny)>0){
                $info["$key"]['scrutiny']=$scrutiny[0];
            }

            $user = $userTable->getUserByOpenId($v['open_id']);
            if(count($user)>0){
                $info["$key"]['user']=$user;
            }
        }

//        usort($info, function($a, $b){
//            $timestampA = strtotime($a['create_at']);
//            $timestampB = strtotime($b['create_at']);
//            if ($timestampA == $timestampB) {
//                return 0;
//            }
//
//            return ($timestampA < $timestampB) ? -1 : 1;
//        });

        if($info!==false&&is_array($info)){

            return new JsonModel($info);
        }else{
            throw new \Exception('no data', 404);
        }
    }

    /**
     * @param mixed $id
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function get($id)
    {


        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $userTable = $this->getUserTable();

        $info = $infoTable->getInfoById($id);

        $img = $imgTable->getImgByInfoId($id)->toArray();

        $user = $userTable->getUserByOpenId($info['open_id']);
        if(count($img)>0){

            $info['img']= $img;
        }

        if(count($user)>0){
            $info['user']=$user;
        }

        if($info!==false){
            return new JsonModel($info);
        }else{
            throw new \Exception('no status', 404);
        }


    }


    public function create($data)
    {
        $imgTable = $this->getImgTable();
        $scrutinyTable = $this->getScrutinyTable();

        $sm = $this->getServiceLocator();
        $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

        $sql = "select i.id,i.create_at,i.praise,i.comment_num,i.content,i.number,i.info_status,i.open_id from shujuxia_microhome_info i,shujuxia_microhome_scrutiny s,shujuxia_microhome_user u where i.id = s.info_id and i.open_id=u.open_id ";

        if(array_key_exists('id',$data)&&$data['id']!==null&&$data['id']!==""){
            $sql = $sql.' and i.id='.$data['id'];
        }

        if(array_key_exists('info_status',$data)&&$data['info_status']!==null&&$data['info_status']!==""){
            $sql = $sql.' and i.info_status = '.$data['info_status'];
        }

        if(array_key_exists('open_id',$data)&&$data['open_id']!==null&&$data['open_id']!==""){
            $sql = $sql.' and i.open_id = '.$data['open_id'];
        }

        if(array_key_exists('username',$data)&&$data['username']!==null&&$data['username']!==""){
            $sql = $sql.' and u.username = '.$data['username'];
        }
        if(array_key_exists('scrutiny_user',$data)&&$data['scrutiny_user']!==null&&$data['scrutiny_user']!==""){
            $sql = $sql.' and s.scrutiny_user = '.$data['scrutiny_user'];
        }

        $info = $dbAdapter->query($sql)->toArray();

        if(count($info)>0){
            foreach($info as $key=>$v){

                $img = $imgTable->getImgByInfoId($v['id'])->toArray();

                if(count($img)>0){
                    $info["$key"]['img']=$img;
                }

                $scrutiny = $scrutinyTable->getByInfoId($v['id'])->toArray();

                if(count($scrutiny)>0){
                    $info["$key"]['scrutiny']=$scrutiny;
                }
            }
             return new JsonModel($info);
        }else{
            return new JsonModel(
                array(
                    'result'=>false,
                    'errors'=>'no data',
                )
            );
        }
    }

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

    public function getScrutinyTable()
    {
        if(!$this->scrutinyTable){
            $sm = $this->getServiceLocator();
            $this->scrutinyTable = $sm->get('MicroHome\Model\ScrutinyTable');
        }
        return $this->scrutinyTable;
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

