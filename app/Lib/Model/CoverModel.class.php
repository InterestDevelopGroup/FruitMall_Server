<?php

/**
 * tea_cover 表模型
 *
 * @author lzjjie
 * @version 1.0.0
 * @since 1.0.0
 */
class CoverModel extends Model {

    /**
     * 添加或更新APP封面
     *
     * @param string $image
     *            封面图片
     * @return array
     */
    public function addOrUpdateCover($image) {
        // 开启事务
        $this->startTrans();
        if ($this->count()) {
            $id = $this->field(array(
                'id'
            ))->order("id DESC")->find();
            if ($this->where(array(
                'id' => $id['id']
            ))->save(array(
                'image' => $image,
                'update_time' => time()
            ))) {
                // 更新成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '更新封面成功'
                );
            } else {
                // 更新失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '更新封面失败'
                );
            }
        } else {
            if ($this->add(array(
                'image' => $image,
                'add_time' => time()
            ))) {
                // 添加成功，提交事务
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '添加封面成功'
                );
            } else {
                // 添加失败，回滚事务
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '添加封面失败'
                );
            }
        }
    }

}