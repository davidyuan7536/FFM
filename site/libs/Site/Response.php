<?php

require_once "Smarty/Smarty.class.php";

class Response extends Smarty {
    private $template;

    public function __construct($template) {
        parent::__construct();

        $this->template = $template;

        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;

        $this->template_dir = $dir . 'templates' . DIRECTORY_SEPARATOR;
        $this->compile_dir = $dir . 'templates_c' . DIRECTORY_SEPARATOR;
        $this->cache_dir = $dir . 'cache' . DIRECTORY_SEPARATOR;

        $this->assign('V', __FFM_VERSION__);
        $this->assign('HOST', __FFM_HOST__);

        global $__FFM_LANG__;
        $this->assign('LANG', $__FFM_LANG__);

        if (isset($_SESSION['user'])) {
            $this->assign('USER', $_SESSION['user']);
        } else {
            require_once "Auth/facebook.php";
            $facebook = new Facebook(array(
                'appId' => __FFM_FBID__,
                'secret' => __FFM_FBSECRET__,
                'cookie' => true,
            ));
            $params = array(
                'next' => 'http://' . __FFM_HOST__ . '/fauth/',
                'req_perms' => 'email'
            );
            $this->assign('FB_LOGIN', $facebook->getLoginUrl($params));
        }

        require_once "Db/DbOptions.php";
        $this->assign('OPTIONS', DbOptions::getAutoOptions());
    }

    public function write() {
        $this->display($this->template);
    }
}
