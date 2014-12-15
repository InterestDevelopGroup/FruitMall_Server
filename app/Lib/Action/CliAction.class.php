<?php

/**
 * 命令行Action
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CliAction extends Action {

    public function add_purchase() {
        $now = time();
        echo $now;
    }

}