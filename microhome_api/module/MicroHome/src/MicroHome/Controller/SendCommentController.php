<?php
/**
 * Created by PhpStorm.
 * User:  flame
 * Date: 14-12-17
 * Time: 上午10:45
 */
namespace MicroHome\Controller;

use Common\Logs\OperationLogger;
use Common\Transaction\Transaction;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Db\Adapter\Driver\Pdo;


/**
 * 发表评论控制器
 *
 * Class SendCommentController
 * @package MicroHome\Controller
 */
class SendCommentController extends AbstractRestfulController
{
    protected $commentTable;
    protected $infoTable;
    protected $adapter;
    protected $logger;
    //发表评论
    public function create($data)
    {
        $commentTable = $this->getCommentTable();
        $infoTable = $this->getInfoTable();

        //事务控制实例化对象
        $this->adapter = new Transaction();
        $this->adapter=$this->adapter->getAdapter();
        $transaction = $this->adapter->getDriver()->getConnection();

        //日志记录操作
        $logger = new OperationLogger();
        $this->logger = $logger->getLogger();

        //过滤器，对传入的参数进行过滤和验证
        $filters = $commentTable->getInputFilter();
        $filters->setData($data);
        if($filters->isValid()){
            $data = $filters->getValues();
            $time = time();
            try{
                //开启事务
                $transaction->beginTransaction();
                $temp = $commentTable->createComment($data['content'],$data['open_id'],$data['info_id'],$time);
                if($temp!==false&& $temp!=null){
                    $info = $infoTable->getInfoById($data['info_id']);
                    $res = $infoTable->updateCommentNumById($data['info_id'],$info['comment_num']+1);
//                    if($res!==false && $res !=null){
                        $result = new JsonModel(array(
                                'result'=>$res,
                            )
                        );
//                    }else{
//                        $comment = $commentTable->getCommentByContent($data['content'],$data['open_id'],$data['info_id']);
//                        $commentTable->deleteCommentById($comment['id']);
//                    }
                    //事务提交
                    $transaction->commit();
                    $this->logger->info(date('Y-M-D h:i:s',time()).'    open_id为'.$data['open_id'].'    的用户为编号为'.$data['info_id'].'   添加了一条评论！');
                }else{
                    //事务回滚
                    $transaction->rollback();
                    $result = new JsonModel(array(
                        'result'=>false,
                        'errors'=>'comment failed!',
                    ));
                }
            }catch (\Exception $e){
                //事务回滚
                $transaction->rollback();
                $result = new JsonModel(array(
                    'result'=>false,
                    'errors'=>$e->getMessage(),
                ));
            }
        }else{
            $result = new JsonModel(array(
                'result' => false,
                'errors' => $filters->getMessages()
            ));
        }
        return $result;
    }


    /**
     * 以下是工厂类 表的实例化
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
    public function getCommentTable()
    {
        if(!$this->commentTable){
            $sm = $this->getServiceLocator();
            $this->commentTable = $sm->get('MicroHome\Model\CommentTable');
        }

        return $this->commentTable;
    }
}