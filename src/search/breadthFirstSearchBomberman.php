<?php
/**
 * 万能的搜索 -- 广度优先搜索也称为宽度优先搜索（Breadth First Search） - 炸弹人
 *
 * 设置13行*13列的迷宫，寻找哪个点放置炸弹（炸弹只能放到空地上）可以消灭最多的敌人
 *
 *
 * Created At 2018/8/1.
 * User: kaiyanh <nzing@aweb.cc>
 */

// 存储队列
$que = [
    'y' => 0, //横坐标
    'x' => 0, // 纵坐标
];

// 迷宫地图 [#-强 .-空地 G-敌人]
$n = 13; // 行
$m = 13; // 列
$map = [
    ['#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#'],
    ['#', 'G', 'G', '.', 'G', 'G', 'G', '#', 'G', 'G', 'G', '.', '#'],
    ['#', '#', '#', '.', '#', 'G', '#', 'G', '#', 'G', '#', 'G', '#'],
    ['#', '.', '.', '.', '.', '.', '.', '.', '#', '.', '.', 'G', '#'],
    ['#', 'G', '#', '.', '#', '#', '#', '.', '#', 'G', '#', 'G', '#'],
    ['#', 'G', 'G', '.', 'G', 'G', 'G', '.', '#', '.', 'G', 'G', '#'],
    ['#', 'G', '#', '.', '#', 'G', '#', '.', '#', '.', '#', '.', '#'],
    ['#', '#', 'G', '.', '.', '.', 'G', '.', '.', '.', '.', '.', '#'],
    ['#', 'G', '#', '.', '#', 'G', '#', '#', '#', '.', '#', 'G', '#'],
    ['#', '.', '.', '.', 'G', '#', 'G', 'G', 'G', '.', 'G', 'G', '#'],
    ['#', 'G', '#', '.', '#', 'G', '#', 'G', '#', '.', '#', 'G', '#'],
    ['#', 'G', 'G', '.', 'G', 'G', 'G', '#', 'G', '.', 'G', 'G', '#'],
    ['#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#', '#'],
];

// 获取所在点可以消灭的敌人的数量
function getNum(int $i, int $j)
{
    global $map;
    $sum = $x = $y = 0;
    // 向上统计
    $x = $i;
    $y = $j;
    while ($map[$x][$y] != '#') {
        // 如果当前是敌人，则进行计算
        if ($map[$x][$y] == 'G') {
            $sum++;
        }
        $x--;
    }

    // 向下统计
    $x = $i;
    $y = $j;
    while ($map[$x][$y] != '#') {
        // 如果当前是敌人，则进行计算
        if ($map[$x][$y] == 'G') {
            $sum++;
        }
        $x++;
    }

    // 向左统计
    $x = $i;
    $y = $j;
    while ($map[$x][$y] != '#') {
        // 如果当前是敌人，则进行计算
        if ($map[$x][$y] == 'G') {
            $sum++;
        }
        $y--;
    }

    // 向右统计
    $x = $i;
    $y = $j;
    while ($map[$x][$y] != '#') {
        // 如果当前是敌人，则进行计算
        if ($map[$x][$y] == 'G') {
            $sum++;
        }
        $y++;
    }

    return $sum;
}

// 预先生成20*20 的储物格 book
$book = [];
for ($i = 0; $i <= 20; $i++) {
    for ($j = 0; $j <= 20; $j++) {
        $book[$i][$j] = 0;
    }
}

// 走的方向的数组
$next = [
    [0, 1], // 向右走
    [1, 0], // 向下走
    [0, -1], // 向左走
    [-1, 0] // 向上走
];

// 相关初始化操作
$head = $tail = 1;
$startX = $startY = 3;
$sum = $max = $tx = $ty = 0;
$que[$tail]['x'] = $startX;
$que[$tail]['y'] = $startY;
$tail++;
$book[$startX][$startY] = 1;
// 目标位置
$mx = $startX; // 最大值的x坐标
$my = $startY; // 最大值的y坐标
$max = getNum($startX, $startY);


// 当队列不为空的时候循环
while ($head < $tail) {
    // 枚举4个方向
    for ($k = 0; $k <= 3; $k++) {
        // 计算下一个点的坐标
        $tx = $que[$head]['x'] + $next[$k][0];
        $ty = $que[$head]['y'] + $next[$k][1];
        // 判断是否越界
        if ($tx < 1 || $tx > $n || $ty < 1 || $ty > $m) {
            continue;
        }
        // 判断是否是障碍物或已经在路径中
        if ($map[$tx][$ty] == '.' && $book[$tx][$ty] == 0) {
            // 标记当前点已经走过
            $book[$tx][$ty] = 1;
            // 插入到新队列
            $que[$tail]['x'] = $tx;
            $que[$tail]['y'] = $ty;
            $tail++;

            // 统计当前节点可以消灭的敌人
            $sum = getNum($tx, $ty);
            if ($sum > $max) {
                $max = $sum;
                $mx = $tx;
                $my = $ty;

            }
        }
    }
    $head++; // 一个点拓展结束后，head++ 对后面的点拓展
}
printf("将炸弹放置在(%d,%d)处，可以消灭%d个敌人 \n", $mx, $my, $max);