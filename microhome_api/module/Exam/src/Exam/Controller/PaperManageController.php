<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-11-13
 * Time: 下午3:58
 */

namespace Exam\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class PaperManageController extends AbstractRestfulController
{
    protected $ExamContentTable;
    protected $ExamPaperTable;



    public function get($id){

        $ExamPaperTable = $this->getExamPaperTable();
        $ExamPaper = $ExamPaperTable->getById($id);

        return new JsonModel($ExamPaper);
    }

    /**
     * @return mixed|JsonModel
     * @throws \Exception
     */

    public function getList(){
        $ExamPaperTable = $this->getExamPaperTable();
        $ExamPaper = $ExamPaperTable->get();
        try{
            if ($ExamPaper !== false) {
                return new JsonModel($ExamPaper);

            } else{
                return new JsonModel(array(
                    'result'=>false,
                    'error'=>'暂无试题',
                ));
            }

        }catch(\Exception $e){
          throw new \Exception('暂无试题', 404);
        }

    }

    public function create($data){
        if(array_key_exists('data',$data)&&!array_key_exists('id',$data)){
            $result = $this->createPaper($data['data']);

        }

        if(!array_key_exists('data',$data)&&array_key_exists('id',$data)){
            $result = $this->deletePaper($data['id']);
        }

        if(array_key_exists('data',$data)&&array_key_exists('id',$data)){
            $result = $this->updatePaper($data['id'],$data['data']);
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
    public function createPaper($data)
    {
        $ExamPaperTable = $this->getExamPaperTable();

        try{
            $res = $ExamPaperTable->createPaper($data);
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
                'error'=>'insert failed'
            ));
        }
        return $result;
    }



    /**
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function deletePaper($id)
    {
        $ExamPaperTable = $this->getExamPaperTable();
        $ExamContentTable = $this->getExamContentTable();
        try{
            if( $ExamPaperTable->deletePaper($id)&&$ExamContentTable->deleteExamPaper($id)){
                $res = true;
            }else{
                $res = false;
            }
        }catch (\Exception $e){
            $res = false;
        }

        if($res==false){
            $result = new JsonModel(array(
                'result'=>false,
                'error'=>'删除失败'
            ));
        }else{

            $result = new JsonModel(array(
                'result'=>true,
            ));
        }

        return $result;
    }

    /**
     * @param mixed $id
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function updatePaper($id, $data)
    {
        $ExamPaperTable = $this->getExamPaperTable();

        try{
            $res = $ExamPaperTable->updatePaper($id,$data);
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
                'error'=>'更新失败'
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
    public function getExamPaperTable()
    {
        if(!$this->ExamPaperTable){
            $sm = $this->getServiceLocator();
            $this->ExamPaperTable = $sm->get('Exam\Model\ExamPaperTable');
        }

        return $this->ExamPaperTable;
    }
}