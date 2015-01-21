<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 15-1-16
 * Time: 下午3:51
 */

namespace Common\Transaction;

use Zend\Db\Adapter\Adapter;
use Zend\Config\Config as Zend_Config;


/**
 * 共用类，用来做事务处理
 *
 * Class Transaction
 * @package MicroHome\Model
 */
class Transaction
{
    protected $adapter;
    private $config_global_path = "config/autoload/global.php";
    private $config_local_path = "config/autoload/local.php";
    public $db_cfg;


    /**
     * 构造器，初始化adapter
     */
    public function __construct()
    {

        $config_global = new Zend_Config(include $this->config_global_path);
        $config_local = new Zend_Config(include $this->config_local_path);
        $this->adapter = new Adapter(array(
            'driver' => 'pdo',
            'dsn' => "mysql:dbname={$config_global->db->database};"
//     		        ."port={$config_local->db->port};"
                ."host={$config_global->db->hostname};port={$config_global->db->port};charset=utf8",
            'username' => $config_local->db->username,
            'password' => $config_local->db->password,
            'driver_options' => array(
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
            ),
        ));

//        if(empty($DSN))
//        {
//            $dsn = "mysql:dbname={$config_global->db->database};"
////     		        ."port={$config_local->db->port};"
//                ."host={$config_global->db->hostname};port={$config_global->db->port};charset=utf8";
//            $user=$config_local->db->username;
//            $pwd=$config_local->db->password;
//            parent::__construct($dsn,$user,$pwd);
//        }
//
//        else{
//            $dsn = "mysql:dbname={$DSN['db']};"
//                ."host={$DSN['host']};charset=utf8";
//
//            $user=$DSN['user'];
//            $pwd=$DSN['pwd'];
//
//            parent::__construct($dsn,$user,$pwd);
//        }

    }


    /**
     * 对象的get方法，获取对象的内容
     * @return Adapter
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}