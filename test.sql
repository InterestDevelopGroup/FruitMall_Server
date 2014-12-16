-- 获取所有订单商品
SELECT
	goods_id,
	amount,
	o.order_id,
	o.add_time AS order_time
FROM
	fruit_order AS o
LEFT JOIN
	fruit_order_goods AS og
ON
	o.order_id = og.order_id
WHERE
	o.status = 2 AND
	o.update_time > 0;


-- 获取所有订单套餐商品
SELECT
	pg.goods_id,
	(op.amount * pg.amount) as amount,
	o.order_id,
	o.add_time AS order_time
FROM
	fruit_order AS o
LEFT JOIN
	fruit_order_package AS op
ON
	o.order_id = op.order_id
left JOIN
	fruit_package_goods AS pg
ON
	pg.package_id = op.package_id
WHERE
	o.`status` = 2 AND
	o.update_time > 0;
