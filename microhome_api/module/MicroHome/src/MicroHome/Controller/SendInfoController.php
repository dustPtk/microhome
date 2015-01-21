<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-17
 * Time: 上午9:29
 */

namespace MicroHome\Controller;

use Common\Logs\OperationLogger;
use Common\Transaction\Transaction;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * 发布话题的控制器
 *
 * Class SendInfoController
 * @package MicroHome\Controller
 *
 */
class SendInfoController extends AbstractRestfulController
{
    protected $infoTable;
    protected $imgTable;
    protected $userTable;
    protected $adapter;
    protected $logger;

    /**
     * 发布话题
     *
     * @param mixed $data
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function create($data)
    {
        $infoTable = $this->getInfoTable();
        $userTable = $this->getUserTable();
        $imgTable = $this->getImgTable();

        /**
         * 实例化一个adapter,用来做数据库数据回滚
         */
        $this->adapter = new Transaction();
        $this->adapter = $this->adapter->getAdapter();
        $transaction = $this->adapter->getDriver()->getConnection();

        //日志记录操作过程
        $operationlogger = new OperationLogger();
        $this->logger = $operationlogger->getLogger();
        /**
         * 实例化一个过滤器，对插入的数据进行验证
         */
        $filters = $infoTable->getInputFilter();
        $filters->setData($data);
        $create_at = time();
        if($filters->isValid()){
            $data = $filters->getValues();
            try{
                //开启事务
                $transaction->beginTransaction();
                $userIsOrNotExist = $userTable->getUserByOpenId($data['open_id']);
                //判断用户是否存在，如果不存在，则插入
                if(count($userIsOrNotExist)==0){
                    $userTable->createUser($data['open_id'],$data['username'],$data['headurl']);
                }
                $res = $infoTable->createInfo($create_at, $data['content'],$data['number'],$data['open_id']);
                if(array_key_exists('img',$data)&&!empty($data['img'])){
                    $info=$infoTable->getInfoByOpenIdAndCrateAt($data['open_id'],$create_at);
                    foreach($data['img'] as $v){
                        try{
                            $imgres = $imgTable->createImg($v,$info['id']);
                            $result = new JsonModel(array(
                                'result'=>$imgres,
                            ));
                            //事务提交
                            $transaction->commit();
                            $this->logger->info(date('Y-M-D h:i:s',time()).'    open_id为'.$data['open_id'].'    的用户发布了一条带有图片的新消息！');
                        }catch (\Exception $e){
                            //事务回滚
                            $transaction->rollback();
                            $result = new JsonModel(array(
                                'result'=>false,
                                'errors'=>'insert img failed!'
                            ));
                        }
                    }
                }else{
                    $result = new JsonModel(
                        array(
                            'result'=>$res,
                        )
                    );
                    $transaction->commit();
                    $this->logger->info(date('Y-M-D h:i:s',time()).'   open_id为'.$data['open_id'].'    的用户发布了一条新消息！');
                }
            }catch(\Exception $e){
                $transaction->rollback();
                throw $e;
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
     * 工厂类获取每个表的表关对象
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
            $this->imgTable = $sm ->get('MicroHome\Model\ImgTable');
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
}