<?php
/* * ********************************************************************************************
 * 								Open Real Estate
 * 								----------------
 * 	version				:	V1.23.0
 * 	copyright			:	(c) 2015 Monoray
 * 							http://monoray.net
 * 							http://monoray.ru
 *
 * 	website				:	http://open-real-estate.info/en
 *
 * 	contact us			:	http://open-real-estate.info/en/contact-us
 *
 * 	license:			:	http://open-real-estate.info/en/license
 * 							http://open-real-estate.info/ru/license
 *
 * This file is part of Open Real Estate
 *
 * ********************************************************************************************* */

class HDatabase
{

    protected $pdo;
    protected $prefix = '';

    public function __construct($config)
    {
        $this->prefix = $config['tablePrefix'];
        $dsn = "" . $config['connectionString'] . ";charset=" . $config['charset'] . "";
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], $opt);
    }

    public function getUserInfoBySessionId($sessionId)
    {
        $sql = 'SELECT u.id, u.role 
                FROM ' . $this->prefix . 'users_sessions us
                LEFT JOIN ' . $this->prefix . 'users u ON u.id=us.user_id
                WHERE us.id=:sessionId';
        $res = $this->pdo->prepare($sql);
        $res->execute(array(
            ':sessionId' => $sessionId,
        ));
        return $res->fetch(PDO::FETCH_ASSOC);
    }
}
