<?php

/**
 * fruit_coupon_rule_content 表模型
 *
 * @author Zonkee
 * @version 1.0.0
 * @since 1.0.0
 */
class CouponRuleContentModel extends Model {

    /**
     * 获取规则内容列表（API）
     *
     * @return array
     */
    public function _getCouponRuleContent() {
        return $this->select();
    }

    /**
     * 添加规则内容
     *
     * @param string $content
     *            规则内容
     * @param int $type
     *            类型（1：获取规则，2：使用规则）
     * @return array
     */
    public function addCouponRuleContent($content, $type) {
        $rule = $this->where(array(
            'type' => $type
        ))->find();
        if ($rule) {
            if ($this->where(array(
                'id' => $rule['id']
            ))->save(array(
                'content' => $content,
                'update_time' => time()
            ))) {
                return array(
                    'status' => true,
                    'msg' => '保存成功'
                );
            } else {
                return array(
                    'status' => false,
                    'msg' => '保存失败'
                );
            }
        } else {
            if ($this->add(array(
                'type' => $type,
                'content' => $content,
                'add_time' => time()
            ))) {
                return array(
                    'status' => true,
                    'msg' => '保存成功'
                );
            } else {
                return array(
                    'status' => false,
                    'msg' => '保存失败'
                );
            }
        }
    }

}