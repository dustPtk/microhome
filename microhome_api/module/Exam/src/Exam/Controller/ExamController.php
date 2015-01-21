<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-11-11
 * Time: 上午11:49
 */
namespace Exam\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ExamController extends AbstractRestfulController
{
    protected $ExamContentTable;
    protected $ExamPaperTable;
    protected $ExamTopicTable;

    /**
     *
     * @param mixed $paperId
     * @return mixed|JsonModel
     * @throws \Exception
     */


    public function get($paperId)
    {
        $ExamContentTable = $this->getExamContentTable();
        $ExamPaperTable = $this->getExamPaperTable();
        $ExamTopicTable = $this->getExamTopicTable();


        $info_array = array();
        $topic_array = array();
        $info_array['paper']=$ExamPaperTable->getById($paperId)->toArray();


        $ExamTopic = $ExamContentTable->getByPaperID($paperId)->toArray();
        foreach($ExamTopic as &$topicId)
        {
            $topicid=$topicId['examtopic_id'];
//            $temp_array = $ExamTopicTable->getById($topicid)->toArray();
//            $temp = new JsonModel($temp_array);
//            $topic_array[]=$temp;
            $topic_array[]=$ExamTopicTable->getById($topicid)->toArray();

//            print_r($ExamTopicTable->getById($topicid)->toArray());

        }
        $info_array['topic'] = $topic_array;
//        $wallData = $info_array->getArrayCopy();

        if ($info_array !== false) {
            return new JsonModel($info_array);
        } else {
            throw new \Exception('暂无试卷信息', 404);
        }

    }

    /**
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function create($data)
    {

        if(array_key_exists('data',$data)&&!array_key_exists('id',$data)){
            $result = $this->createExam($data['data']);

        }

        if(array_key_exists('data',$data)&&array_key_exists('id',$data)){

            $ExamContentTable = $this->getExamContentTable();

            $content = $ExamContentTable->getByPaperIdAndTopicID($data['id'],$data['data']['id'])
            ->toArray();
            $result = $this->deleteExam($content['id']);

        }

        if(!empty($result)&&$result){
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
     * @param $data
     * @return JsonModel
     */
    public function createExam($data)
    {
        $ExamContentTable = $this->getExamContentTable();
        $temp = array();
        foreach( $data['examtopic_id'] as $topic)
        {
            $temp['exampaper_id'] = $data['exampaper_id'];
            $temp['examtopic_id'] = $topic;

//            if($ExamContentTable->createPaper($temp)){
//                $result = new JsonModel(array(
//                    'result'=>true,
//                ));
//            }else{
//
//                $result = new JsonModel(array(
//                    'result'=>false,
//                    'error'=>'插入失败'
//                ));
//            }
                try{
                    $res = $ExamContentTable->createContent($temp);
                }catch (\Exception $e){
                    $res= false;
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
        }

        return $result;

    }

    /**
     * @param $id
     * @return JsonModel
     */
    public function deleteExam($id)
    {
        $ExamContentTable = $this->getExamContentTable();

        try{
            $res = $ExamContentTable->deleteExam($id);
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
                'error'=>'删除失败'
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