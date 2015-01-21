<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-17
 * Time: 下午3:05
 */

namespace MicroHome\Controller;

use Common\Transaction\Transaction;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * 点赞功能
 *
 * Class PraiseController
 * @package MicroHome\Controller
 */
class PraiseController extends AbstractRestfulController
{
    protected $praiseTable;
    protected $infoTable;
    protected $adapter;


    /**
     * 点赞，取消赞
     *
     * @param mixed $data
     * @return mixed|JsonModel
     * @throws \Exception
     */
    public function create($data)
    {
        $infoTable = $this->getInfoTable();
        $praiseTable = $this->getPraiseTable();

        $filters = $praiseTable->getInputFilter();
        $filters->setData($data);
        //事务控制
        $this->adapter = new Transaction();
        $this->adapter = $this->adapter->getAdapter();
        $transaction = $this->adapter->getDriver()->getConnection();

        if($filters->isValid()){
            $transaction->beginTransaction();
            $praise = $praiseTable->getPraiseByInfoIdAndOpenId($data['info_id'],$data['open_id'])->toArray();

            $info = $infoTable->getInfoById($data['info_id']);
            try{

                if(count($praise)>0){
                    if($praise[0]['flag']==1||$praise[0]['flag']==true){
                        $info['praise'] = $info['praise']-1;
                        $infoTable->updatePraiseById($data['info_id'],$info['praise']);
                        $result = new JsonModel(array(
                            'result'=>$praiseTable->updatePraise($data['info_id'],$data['open_id'],0),
                            'content'=>'cancel'
                        ));
                        $transaction->commit();
                    }else{
                        $info['praise'] = $info['praise']+1;
                        $infoTable->updatePraiseById($data['info_id'],$info['praise']);
                        $result = new JsonModel(array(
                            'result'=>$praiseTable->updatePraise($data['info_id'],$data['open_id'],1),
                            'content'=>'praise'
                        ));
                        $transaction->commit();
                    }
                }else{
                    $info['praise'] = $info['praise']+1;
                    $infoTable->updatePraiseById($data['info_id'],$info['praise']);

                    $res = $praiseTable->createPraise($data['open_id'],$data['info_id']);
                    $result = new JsonModel(array(
                        'result'=>$res,
                        'content'=>'praise'
                    ));
                    $transaction->commit();
                }
            }catch (\Exception $e){
                $transaction->rollback();
                throw $e;
            }
        }else{
            $result = new JsonModel(array(
                'result'=>false,
                'errors' => $filters->getMessages()
            ));

        }

        return $result;
    }


    /**
     * 工厂类 表的实例化
     *
     * @return array|object
     */
    public function getPraiseTable()
    {
        if(!$this->praiseTable){
            $sm = $this->getServiceLocator();
            $this->praiseTable = $sm->get('MicroHome\Model\PraiseTable');
        }
        return $this->praiseTable;
    }

    public function getInfoTable()
    {
        if(!$this->infoTable){
            $sm = $this->getServiceLocator();
            $this->infoTable = $sm->get('MicroHome\Model\InfoTable');
        }
        return $this->infoTable;
    }
}