<?php
/**
 * Created by PhpStorm.
 * User:    flame
 * Date: 14-12-17
 * Time: 下午12:02
 */
namespace MicroHome\Controller;


use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * 这个类主要是展示微信端的首页
 * getList是获取第一次读取的十条按时间排序的信息
 * get($id)是获取的每次下划的新的几条内容
 *
 * Class ShowPageController
 * @package MicroHome\Controller
 */
class ShowPageController extends AbstractRestfulController
{
    protected $infoTable;
    protected $imgTable;
    protected $commentTable;
    protected $praiseTable;
    protected $userTable;
    protected $logger;
    /**
     * 获取所有的信息
     *
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function getList()
    {

        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $praiseTable = $this->getPraiseTable();
        $userTable = $this->getUserTable();
        $info = $infoTable->getInfoShow()->toArray();

        foreach($info as $key=>$v){
            $user = $userTable->getUserByOpenId($v['open_id']);
            if(count($user)>0){
                $info["$key"]['username']=$user['username'];
                $info["$key"]['headurl']= $user['headurl'];
            }
            $img = $imgTable->getImgByInfoId($v['id'])->toArray();

            if(count($img)>0){
                $info["$key"]['img']=$img;
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
        if($info!==false){
            return new JsonModel($info);
        }else{
            throw new \Exception('no status', 404);
        }

    }


    /**
     * 根据当前页的最后一条的ID去查询下面的十条信息
     *
     * @param mixed $id
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function get($id)
    {
        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $commentTable = $this->getCommentTable();
        $userTable = $this->getUserTable();

        $info = $infoTable->getInfoById($id);
        $img = $imgTable->getImgByInfoId($id)->toArray();
        $comment = $commentTable->getCommentByInfoId($id)->toArray();
        $user = $userTable->getUserByOpenId($info['open_id']);

        $info['username']=$user['username'];
        $info['headurl']=$user['headurl'];
        if(count($info)>0){
            if(count($img)>0){
                $info['img']= $img;
            }

            if(count($comment)>0){

                foreach($comment as $k=>$v ){
                    $user = $userTable->getUserByOpenId($v['open_id']);
                    $comment["$k"]['comment_username']=$user['username'];
                    $comment["$k"]['comment_headurl']=$user['headurl'];
                }
                $info['comment'] = $comment;
            }

        }
        if($info!==false){
            return new JsonModel($info);
        }else{
            throw new \Exception('no status', 404);
        }
    }

    /**
     * 工厂类，实例化不同表所对应的表关对象
     *
     * @return array|object
     */


    //infoTable的工厂类对象
    public function getInfoTable()
    {
        if(!$this->infoTable){
            $sm = $this->getServiceLocator();
            $this->infoTable = $sm->get('MicroHome\Model\InfoTable');
        }

        return $this->infoTable;
    }

    //imgTable的工厂类对象
    public function getImgTable()
    {
        if(!$this->imgTable){
            $sm = $this->getServiceLocator();
            $this->imgTable = $sm->get('MicroHome\Model\ImgTable');
        }

        return $this->imgTable;
    }

    //commmentTable 的工厂类对象
    public function getCommentTable()
    {
        if(!$this->commentTable){
            $sm = $this->getServiceLocator();
            $this->commentTable = $sm->get('MicroHome\Model\CommentTable');
        }

        return $this->commentTable;
    }

    //praiseTable的工厂类对象
    public function getPraiseTable(){
        if(!$this->praiseTable){
            $sm = $this->getServiceLocator();
            $this->praiseTable = $sm->get('MicroHome\Model\PraiseTable');
        }
        return $this->praiseTable;
    }

    //userTable的工厂类对象
    public function getUserTable(){
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('MicroHome\Model\UserTable');
        }
        return $this->userTable;
    }
}