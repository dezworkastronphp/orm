<?php

namespace Astronphp\Orm;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class ConnectDoctrine{
    // ...
    private $connect        = null;
    // mysql
    private $engine         = 'mysql';
    // localhost
    private $host           = '';
    // 3006
    private $port           = '3306';
    // databasenome
    private $dbname         = '';
    // root
    private $username       = '';
    // 123456
    private $password       = '';
    // utf8
    private $charset        = 'utf8';
    // true | false
    private $isDevMode      = false;
    // true | false
    private $dirEntity      = '';

    public function __construct($autoConnect=true){

        $this->engine           =  \Orm::getInstance('Orm')->engine             ??  $this->engine;
        $this->host             =  \Orm::getInstance('Orm')->host               ??  $this->host;
        $this->port             =  \Orm::getInstance('Orm')->port               ??  $this->port;
        $this->dbname           =  \Orm::getInstance('Orm')->dbname             ??  $this->dbname;
        $this->username         =  \Orm::getInstance('Orm')->username           ??  $this->username;
        $this->password         =  \Orm::getInstance('Orm')->password           ??  $this->password;
        $this->charset          =  \Orm::getInstance('Orm')->charset            ??  $this->charset;
        $this->dirEntity        =  \Orm::getInstance('Orm')->dirEntity          ?? null;
        $this->isDevMode        =  \Orm::getInstance('Orm')->isDevMode          ?? false;
        $this->entityNamespace  =  \Orm::getInstance('Orm')->entityNamespace    ?? null;

        $this->connect          = $this->startConnect();
    }

    public function startConnect(){
        if(
            !empty($this->engine) &&
            !empty($this->host) &&
            !empty($this->port) &&
            !empty($this->dbname) &&
            !empty($this->username) &&
            !empty($this->password) &&
            file_exists($this->dirEntity)
        ){
            $config = Setup::createAnnotationMetadataConfiguration(
                array($this->dirEntity),
                $this->isDevMode
            );
            $config->addEntityNamespace($this->entityNamespace, 'Entity\\'.$this->entityNamespace);
            // the connection configuration
            return EntityManager::create(
                array(
                    'driver'        => $this->engine,
                    'host'          => $this->host,
                    'port'          => $this->port,
                    'user'          => $this->username,
                    'password'      => $this->password,
                    'dbname'        => $this->dbname,
                    'charset'       => $this->charset,
                    'driverOptions' => array(
                        1002   => 'SET NAMES '.$this->charset
                    )
                ),
                $config
            );
                
        }else{
            return null;
        }
    }

    public function connect() {
        try {
            if(!is_null($this->connect)){
                $a = $this->connect->getConnection()->connect();
            }
            return $this->connect;
        } catch (\Exception $e) {
            \Errors::getInstance('ErrorView')->setExeption($e)->showError();
        }
    }
}