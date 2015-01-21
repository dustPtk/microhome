<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-18
 * Time: 下午2:55
 */

namespace MicroHome\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * 微社区后台审核功能类
 *
 * Class BackStageScrutinyController
 * @package MicroHome\Controller
 *
 */
class BackStageScrutinyController extends AbstractRestfulController
{
    protected $infoTable;
    protected $imgTable;
    protected $scrutinyTable;
    protected $userTable;
    /**
     * 审核页面提交审核的信息，做审核处理
     *
     * @param mixed $data
     * @return mixed|JsonModel
     * @throws \Exception
     *
     */
    public function create($data)
    {
        $infoTable = $this->getInfoTable();
        $scrutinyTable = $this->getScrutinyTable();


        $scrutiny = $scrutinyTable->getByInfoId($data['info_id'])->toArray();
        if(count($scrutiny)>0){
            return new JsonModel(array(
                    'result'=>false,
                    'errors'=>'this message is scrutinied！',
                )
            );

        }else{
            $scrutinyTable->createScrutiny($data['content'],$data['info_id'],$data['scrutiny_user']);
            $res = $infoTable->updateInfoById($data['info_id'],$data['info_status']);
            $result = new JsonModel(array(
                'result'=>$res
            ));

            return $result;
        }
    }


    /**
     * 审核页面的信息
     *
     * @return JsonModel|mixed
     * @throws \Exception
     */
    public function getList()
    {

        $infoTable = $this->getInfoTable();
        $imgTable = $this->getImgTable();
        $userTable = $this->getUserTable();
        $info = $infoTable->getInfoByInfoStatus(0)->toArray();

        if(count($info)>0){
            $img = $imgTable->getImgByInfoId($info[0]['id'])->toArray();
            if(count($img)>0){
                $info[0]['img']= $img;
            }

            $user = $userTable->getUserByOpenId($info[0]['open_id']);

            if(count($user)>0){
                $info[0]['user']=$user;
            }

            if($info!==false&&is_array($info)){

                return new JsonModel($info[0]);
            }else{
                return new JsonModel(array(
                    'result'=>false,
                    'errors'=>'no data!'
                ));
            }
        }else{

            return new JsonModel(
                array(
                    'result'=>false,
                    'errors'=>'no data',
                )
            );
        }



    }


    /**
     * 工厂类  表的实例化对象
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
            $this->imgTable = $sm->get('MicroHome\Model\ImgTable');

        }
        return $this->imgTable;
    }

    public function getScrutinyTable()
    {
        if(!$this->scrutinyTable){
            $sm = $this->getServiceLocator();
            $this->scrutinyTable = $sm->get('MicroHome\Model\ScrutinyTable');
        }
        return $this->scrutinyTable;
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