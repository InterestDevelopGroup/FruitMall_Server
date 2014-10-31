<?php

/**
 * 客服Action
 */
class ServiceAction extends CommonAction {

    public function index() {
        // 渲染客服
        $this->render_service();
        $this->display();
    }

}
?>