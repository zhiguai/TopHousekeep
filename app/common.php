<?php
//公共类
namespace app\common;

//TP类
use think\Facade;
use think\Response;

//TP门面类
use think\facade\Request;
use think\facade\Db;
use think\facade\Cookie;
use think\facade\Config;
use think\facade\Session;
use think\facade\Cache;

class Common extends Facade
{

    /**
     * @description: 版本信息
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:48:48
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function systemVer()
    {
        return [
            'Name' => '完美家政',
            'Url' => '#',
            'VerS' => '1.0.0',
            'Ver' => '1.0',
            'GithubUrl' => '#',
            'QGroupUrl' => '#',
        ];
    }

    /**
     * @description: 取system数据
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2023-01-18 18:10:57
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function systemData()
    {
        $result = Db::table('system')->select()->toArray();
        return array_column($result, 'value', 'name');
    }

    /**
     * @description: 前端跳转
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:55:49
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     * @param {*} $url
     * @param {*} $msg
     */
    protected static function jumpUrl($url, $msg = 'undefined')
    {
        // 写人Msg信息
        Cookie::forever('msg', $msg);
        // 跳转至网页（记得前端显示完就删除）
        return "<script>window.location.replace('" . $url . "')</script>";
    }

    /**
     * @description: 获取IP
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:56:28
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     * @param {*} $type
     */
    protected static function getIp($type = 0)
    {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = ip2long($ip);
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    /**
     * @description: Admin前端Coockie验证uuid
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:56:45
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function validateViewAuth()
    {
        //整理数据
        $uuid = Cookie::get('uuid');
        if (empty($uuid)) {
            return array(false, '请先登入');
        }
        //查询数据
        $result = Db::table('admin')
            ->where('uuid', $uuid)
            ->find();
        //判断数据是否存在
        if (empty($result)) {
            return array(false, '当前uuid已失效请重新登入');
        } else {
            //返回用户数据
            return array(true, $result);
        }
    }

    /**
     * @description: AdminAPI验证uuid并获取当前用户数据
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:57:00
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function validateAuth()
    {
        //整理数据
        $uuid = Request::param('uuid');
        if (empty($uuid)) {
            return array(400, '缺少uuid');
        }
        //查询数据
        $result = Db::table('admin')
            ->where('uuid', $uuid)
            ->find();
        //判断数据是否存在
        if (empty($result)) {
            return array(401, '当前uuid已失效请重新登入');
        } else {
            //返回用户数据
            return $result;
        }
    }

    /**
     * @description: API格式输出方法
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:57:16
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     * @param {*} $data
     * @param {string} $msg
     * @param {int} $code
     * @param {string} $type
     */
    protected static function create($data, string $msg = '', int $code = 200, string $type = 'json'): Response
    {
        $result = [
            //状态码
            'ec' => $code,
            //消息
            'msg' => $msg,
            //数据
            'data' => $data
        ];

        //返回API接口
        return Response::create($result, $type);
    }

    /**
     * @description: 生成UUID
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:57:34
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function get_uuid()
    {
        $charid = md5(uniqid(mt_rand(), true));
        $hyphen = chr(45); // "-"
        $uuid = //chr(123) "{"
            substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        //.chr(125); "}"
        return $uuid;
    }

    //获取模板目录
    protected static function get_templateDirectory()
    {
        $d = Config::get('lovecards.template_directory', 'index');
        $r = Is_dir('./view/index/' . $d);
        //dd($r);
        if ($r) {
            //当目录存在时
            $r = $d;
        } else {
            $r = 'index';
        }
        //dd($r, $d);
        return [$r, $d];
    }

    //编辑配置文件
    protected static function extraconfig($arr = [], $file, $user = 'admin')
    {
        if (is_array($arr)) {
            $filename = $file;
            //文件位置，根据你之前的去修改
            $filepath = '../config/' . $filename . ".php";
            if (!file_exists($filepath)) {
                if (!fopen($filepath, "w")) {
                    return 'PermissionError1';
                }
            }
            if (!is_writable($filepath)) {
                return 'PermissionError2';
            }
            $conf = array();
            foreach ($arr as $key => $value) {
                $conf[$key] = $value;
            }
            $time = date('Y/m/d H:i:s');
            $str = "<?php\r\n/**\r\n * 由" . $user . "建立.\r\n * $time\r\n */\r\nreturn [\r\n";
            foreach ($conf as $key => $value) {
                if (is_array($value)) {
                    $str .= "\t'$key'=>[";
                    foreach ($value as $r) {
                        $str .= "'$r',";
                    }
                    $str = rtrim($str, ',');
                    $str .= ']' . "\r\n";
                } else {
                    $str .= "\t'$key' => '$value',";
                    $str .= "\r\n";
                }
            }
            $str .= '];';
            $result = file_put_contents($filepath, $str);
            if ($result) {
                return 'success';
            } else {
                return $result;
            }
        } else {
            return 'error';
        }
    }

    //防手抖
    protected static function preventClicks($setName, $time = 6)
    {
        if (strtotime(date("Y-m-d H:i:s")) > strtotime(Session::get($setName))) {
            //符合要求
            $result = [true];
        } else {
            $result = [false, '您的操作太快了，稍后再试试试吧'];
        }
        //设置上次时间
        Session::set($setName, date("Y-m-d H:i:s", strtotime('+' . $time . ' second')));
        //返回结果
        return $result;
    }

    /**
     * @description: User前端Coockie验证uuid
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:56:45
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function validateViewUserAuth()
    {
        //整理数据
        $uuid = Cookie::get('user_uuid');
        if (empty($uuid)) {
            return array(false, '请先登入');
        }

        //查询数据
        $user_id = Cache::store('redis')->get($uuid);
        if ($user_id) {
            $result = Db::table('user')
                ->where('id', $user_id)
                ->find();
            //判断数据是否存在
            if (empty($result)) {
                //用户数据不存在
                return array(false, '未查询到用户信息');
            } else {
                //返回用户数据
                return array(true, $result);
            }
        }
        return array(false, '当前uuid已失效请重新登入');
    }

    /**
     * @description: UserAPI验证uuid并获取当前用户数据
     * @return {*}
     * @Author: github.com/zhiguai
     * @Date: 2022-12-29 18:57:00
     * @LastEditTime: Do not edit
     * @LastEditors: github.com/zhiguai
     */
    protected static function validateUserAuth()
    {
        //整理数据
        $uuid = Request::param('user_uuid');
        if (empty($uuid)) {
            return array(400, '缺少user_uuid');
        }

        //查询数据
        $user_id = Cache::store('redis')->get($uuid);
        if ($user_id) {
            $result = Db::table('user')
                ->where('id', $user_id)
                ->find();
            //判断数据是否存在
            if (empty($result)) {
                //返回用户数据
                return array(500, '未查询到用户信息');
            } else {
                //返回用户数据
                return $result;
            }
        }
        return array(401, '当前uuid已失效请重新登入');
    }

    //生成订单号
    protected static function generateOrderNumber() {
        $prefix = "ORD"; // 前缀
        $date = date("YmdHis"); // 当前时间，格式为年月日时分秒
        $random = mt_rand(1000, 9999); // 随机数，范围为 1000 到 9999
    
        $orderNumber = $prefix . $date . $random;
        return $orderNumber;
    }

}

class File extends Facade
{
    /*
		@function  		创建目录
		
		@var:$filename  目录名
			
		@return:   		true
	*/

    protected static function mk_dir($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        if (!is_dir($dir)) {
            if (mkdir($dir, 0700) == false) {
                return false;
            }
            return true;
        }
        return true;
    }


    /*
		@function  		读取文件内容
		
		@var:$filename  文件名
			
		@return:   		文件内容
	*/

    protected static function read_file($filename)
    {
        $content = '';
        if (function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if (@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }

    /*
		@function  		写文件
		
		@var:$filename  文件名
		
		@var:$writetext 文件内容
		
		@var:$openmod 	打开方式
			
		@return:   		成功=true
	*/

    protected static function write_file($filename, $writetext, $openmod = 'w')
    {
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }


    /*
		@function  		删除目录
		
		@var:$dirName  	原目录
			
		@return:   		成功=true
	*/

    protected static function del_dir($dirName)
    {
        if (!file_exists($dirName)) {
            return false;
        }

        $dir = opendir($dirName);
        while ($fileName = readdir($dir)) {
            $file = $dirName . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file)) {
                    self::del_dir($file);
                } else {
                    unlink($file);
                }
            }
        }
        closedir($dir);
        return rmdir($dirName);
    }

    /*
		@function  		复制目录
		
		@var:$surDir  	原目录
		
		@var:$toDir  	目标目录
		
		@return:   		true
	*/

    protected static function copy_dir($surDir, $toDir)
    {
        $surDir = rtrim($surDir, '/') . '/';
        $toDir = rtrim($toDir, '/') . '/';
        if (!file_exists($surDir)) {
            return  false;
        }

        if (!file_exists($toDir)) {
            self::mk_dir($toDir);
        }
        $file = opendir($surDir);
        while ($fileName = readdir($file)) {
            $file1 = $surDir . '/' . $fileName;
            $file2 = $toDir . '/' . $fileName;
            if ($fileName != '.' && $fileName != '..') {
                if (is_dir($file1)) {
                    self::copy_dir($file1, $file2);
                } else {
                    copy($file1, $file2);
                }
            }
        }
        closedir($file);
        return true;
    }


    /*
		@function  列出目录
		
		@var:$dir  目录名
		
		@return:   目录数组
		
		列出文件夹下内容，返回数组 $dirArray['dir']:存文件夹；$dirArray['file']：存文件
	*/

    protected static function get_dirs($dir)
    {
        $dir = rtrim($dir, '/') . '/';
        $dirArray[][] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            $j = 0;
            while (false !== ($file = readdir($handle))) {
                if (is_dir($dir . $file)) { //判断是否文件夹
                    $dirArray['dir'][$i] = $file;
                    $i++;
                } else {
                    $dirArray['file'][$j] = $file;
                    $j++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
    }

    /*
		@function  统计文件夹大小
		
		@var:$dir  目录名
		
		@return:   文件夹大小(单位 B)
	*/

    protected static function get_size($dir)
    {
        $dirlist = opendir($dir);
        $dirsize = 0;
        while (false !==  ($folderorfile = readdir($dirlist))) {
            if ($folderorfile != "." && $folderorfile != "..") {
                if (is_dir("$dir/$folderorfile")) {
                    $dirsize += self::get_size("$dir/$folderorfile");
                } else {
                    $dirsize += filesize("$dir/$folderorfile");
                }
            }
        }
        closedir($dirlist);
        return $dirsize;
    }

    /*
		@function  检测是否为空文件夹
		
		@var:$dir  目录名
		
		@return:   存在则返回true
	*/

    protected static function empty_dir($dir)
    {
        return (($files = @scandir($dir)) && count($files) <= 2);
    }
}
