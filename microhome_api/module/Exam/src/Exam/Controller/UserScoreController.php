<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-11-18
 * Time: 上午9:13
 */

namespace Exam\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Helper\ViewModel;
use Zend\View\Model\JsonModel;

class UserScoreController extends AbstractRestfulController
{

    protected $userScoreTable;

    public function get($id)
    {
        $userScoreTable= $this->getUserScoreTable();
        $userScore = $userScoreTable->getById($id);


        if($userScore!==false){
            return new JsonModel($userScore);
        }else{
            return new JsonModel(array(
                'result'=>false,
                'error'=>'暂无成绩',
            ));
        }
    }

    public function getList()
    {

        $userScoreTable= $this->getUserScoreTable();
        $userScore = $userScoreTable->get();

        if($userScore!==false){
            return new JsonModel($userScore);
        }else{
            return new JsonModel(array(
                'result'=>false,
                'error'=>'暂无成绩',
            ));
        }
    }

    public function create($data){



        if(array_key_exists('data',$data)&&!array_key_exists('id',$data)){

            $result= $this->createUserScore($data['data']);
        }

        if(!array_key_exists('data',$data)&&array_key_exists('id',$data)){
            $result=$this->deleteUserScore($data['id']);
        }

        if(array_key_exists('data',$data)&&array_key_exists('id',$data)){
            $result=$this->updateUserScore($data['id'],$data['data']);
        }

        if($result&&!empty($result)){
            return new JsonModel(array(
                'result'=>true,
                'msg'=>$result
            ));
        }else{
            return new JsonModel(array(
                'result'=>false,
                'error'=>'Invalid Service Runner!'
            ));
        }

//        return !empty($result)?$result:new JsonModel(array('result'=>false));
    }



    public function createUserScore($data)
    {

        $userScoreTable= $this->getUserScoreTable();
        try{
            $res=$userScoreTable->createUserScore($data);
        }catch (\Exception $e){
            $res=false;
        }
        if($res){
            $result = new JsonModel(array(
                'result'=>true,
            ));
        }else{
            $result = new JsonModel(array(
                'result'=>false,
                'error'=>"Insert Failed"
            ));
        }
        return $result;

    }

    public function updateUserScore($id,$data)
    {
        $userScoreTable= $this->getUserScoreTable();

        try{
            $res = $userScoreTable->updateUserScore($id,$data);
        }catch (\Exception $ve){
            $res = false;
        }
        if($res){
            $result = new JsonModel(array(
                'result'=>true,
            ));
        }else{
            $result = new JsonModel(array(
                'result'=>false,
                'error'=>'update failed!'
            ));
        }
        return $result;

    }

    public function deleteUserScore($id)
    {
        $userScoreTable= $this->getUserScoreTable();
        try{
            $res = $userScoreTable->deleteUserScore($id);
        }catch (\Exception $e){
            $res = false;
        }

        if($res){
            $result = new JsonModel(array(
                'result'=>true,
            ));
        }else{
            $result = new JsonModel(array(
                'result'=>false,
                'error'=>'delete failed'
            ));
        }
        return $result;

    }


    public function getUserScoreTable()
    {
        if(!$this->userScoreTable){
            $sm = $this->getServiceLocator();
            $this->userScoreTable = $sm ->get('Exam\Model\UserScoreTable');
        }
        return $this->userScoreTable;
    }
}