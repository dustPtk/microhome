<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-18
 * Time: 上午8:30
 */

namespace MicroHome\Controller;


use Common\Logs\OperationLogger;
use Common\Transaction\Transaction;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * 删除某一条信息
 *
 * Class DeleteInfoController
 * @package MicroHome\Controller
 */
class DeleteInfoController extends AbstractRestfulController
{
    protected $infoTable;
    protected $adapter;
    protected $logger;

    /**
     * 更改某一条信息的状态为删除
     *
     * @param mixed $id
     * @return mixed|JsonModel
     */
    public function get($id)
    {
        $infoTable = $this->getInfoTable();
        //事务控制
        $this->adapter = new Transaction();
        $this->adapter = $this->adapter->getAdapter();
        $transaction = $this->adapter->getDriver()->getConnection();
        //日志记录
        $operationlogger = new OperationLogger();
        $this->logger =$operationlogger->getLogger();
        try{
            $transaction->beginTransaction();
            $info = $infoTable->deleteInfoById($id);
            //事务提交
            $transaction->commit();
            $result = new JsonModel(array(
                'result'=>$info,
            ));
            $this->logger->info(date('Y-M-D H:I:S',time()).'    更改编号为'.$id.'的状态为以删除');
        }catch (\Exception $e){
            //事务回滚
            $transaction->rollback();
            $result = new JsonModel(array(
                'result'=>false,
                'errors'=>$e->getMessage(),
            ));
        }
        return $result;
    }

    /**
     * 工厂实例化信息表的表关对象
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
}