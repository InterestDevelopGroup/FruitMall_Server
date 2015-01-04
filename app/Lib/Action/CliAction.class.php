<?php

/**
 * 命令行Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CliAction extends Action {

    /**
     * 计划任务获取采购
     */
    public function add_purchase() {
        if ($this->isPost()) {
            D('Purchase')->addPurchase();
        } else {
            $this->redirect('/');
        }
    }

}