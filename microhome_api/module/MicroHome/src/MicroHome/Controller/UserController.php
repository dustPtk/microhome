<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 15-1-19
 * Time: 下午3:59
 */

namespace MicroHome\Controller;

use Common\Logs\OperationLogger;
use Common\Transaction\Transaction;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

/**
 * 用户的录入类
 *
 * Class UserController
 * @package MicroHome\Controller
 */
class UserController extends AbstractRestfulController
{
    protected $userTable;
    protected $adapter;
    protected $logger;
    /**
     * 录入用户的部分信息
     *
     * @param mixed $data
     * @return mixed|JsonModel
     */
    public function create($data)
    {
        $userTable = $this->getUserTable();

        //事务控制
        $this->adapter = new Transaction();
        $this->adapter = $this->adapter->getAdapter();
        $transaction = $this->adapter->getDriver()->getConnection();

        //日志记录
        $logger = new OperationLogger();
        $this->logger = $logger->getLogger();

        //过滤数据
        $inputFilters = $userTable->getInputFilter();
        $inputFilters->setData($data);

        if($inputFilters->isValid()){
            $data = $inputFilters->getValues();

            try{
                $transaction->beginTransaction();

                $res = $userTable->createUser($data['open_id'],$data['username'],$data['headurl']);
                $result = new JsonModel(array(
                    'result'=>$res
                ));
                $transaction->commit();
                $this->logger->info('添加新用户,用户open_id为'.$data['open_id']);
            }catch (\Exception $e){
                $transaction->rollback();
                $result = new JsonModel(array(
                    'result'=>false,
                    'errors'=>$e->getMessage(),
                ));
            }
        }else{
            $result = new JsonModel(array(
                'result'=> false,
                'errors'=>$inputFilters->getMessages(),
            ));
        }
        return $result;
    }

    //工厂  表的实例化对象
    public function getUserTable()
    {
        if(!$this->userTable){
            $sm = $this->getServiceLocator();
            $this->userTable = $sm ->get('MicroHome\Model\UserTable');
        }
        return $this->userTable;
    }
}