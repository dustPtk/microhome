<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-11-13
 * Time: 下午3:58
 */

namespace Exam\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class TopicManageController extends AbstractRestfulController
{
    protected $ExamContentTable;
    protected $ExamTopicTable;

    /**
     * @return mixed|JsonModel
     * @throws \Exception
     */

    public function get($id){

        $ExamTopicTable = $this->getExamTopicTable();
        $ExamTopic = $ExamTopicTable->getById($id);
        return new JsonModel($ExamTopic);

    }
    public function getList()
    {
        $ExamTopicTable = $this->getExamTopicTable();
        $ExamTopic = $ExamTopicTable->get();


        if ($ExamTopic !== false) {
            return new JsonModel($ExamTopic);
        } else {
            throw new \Exception('no data', 404);
        }


    }

    public function create($data){



        if(array_key_exists('data',$data)&&!array_key_exists('id',$data)){

            $result = $this->createTopic($data['data']);

        }

        if(!array_key_exists('data',$data)&&array_key_exists('id',$data)){
            $result = $this->deleteTopic($data['id']);
        }

        if(array_key_exists('data',$data)&&array_key_exists('id',$data)){

            $result = $this->updateTopic($data['id'],$data['data']);
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
    }

    /**
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function createTopic($data)
    {
        $ExamTopicTable = $this->getExamTopicTable();
        try{
            $res = $ExamTopicTable->createTopic($data);
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
                'error'=>'insert failed!'
            ));
        }
        return $result;
    }
    /**
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function deleteTopic($id)
    {
        $ExamTopicTable = $this->getExamTopicTable();
        $ExamContentTable = $this->getExamContentTable();

        if($ExamTopicTable->deleteTopic($id)&&$ExamContentTable->deleteExamTopic($id)){
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

    /**
     * @param mixed $id
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function updateTopic($id, $data)
    {
        $ExamTopicTable = $this->getExamTopicTable();

        try{
            $res = $ExamTopicTable->updateTopic($id,$data);
        }catch (\Exception $e){
            $res =false;
        }
        if($res){
            $result = new JsonModel(array(
                'result'=>true,
            ));
        }else{
            $result = new JsonModel(array(
                'result'=>false,
                'error'=>'Update Failed!'
            ));
        }
        return $result;
    }

    /**
     * @return array|object
     */
    public function getExamContentTable()
    {
        if(!$this->ExamContentTable){
            $sm = $this->getServiceLocator();
            $this->ExamContentTable = $sm->get('Exam\Model\ExamContentTable');
        }
        return $this->ExamContentTable;
    }


    /**
     * @return array|object
     */
    public function getExamTopicTable()
    {
        if(!$this->ExamTopicTable){
            $sm = $this->getServiceLocator();
            $this->ExamTopicTable = $sm->get('Exam\Model\ExamTopicTable');
        }

        return $this->ExamTopicTable;
    }
}