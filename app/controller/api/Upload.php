<?php


namespace app\controller\api;

use app\middleware\Auth;
use app\Request;
use think\facade\Filesystem;
use app\BaseController;
use think\db\exception\DbException;

class Upload extends BaseController
{
    /*
    * 中间件
    *
    * */
    protected $middleware = [Auth::class];

    public function img(Request $request)
    {
        try {
            $file = request()->file('file');
            $saveName = Filesystem::putFile( '/upload/', $file);
            return echo_upload_api('/'.$saveName);
        } catch (DbException $e) {
            return msg_error($e);
        }

//
//        if ($request->method() == 'POST') {
//            $file = request()->file();
//            $fileinfo = $file['file']->getInfo();
//
//            $fileurl = $fileinfo['tmp_name'];
//            $filetype = explode('/', $fileinfo['type']);
//            $filetype = $filetype[1];
//            $filename = time() . rand(0, 9) . '.' . $filetype;
//
//            $dir = 'uploads/' . date('Ymd', time());
//            if (!is_dir($dir)) {
//                mkdir($dir, 0777, true);
//            }
//            $copyurl = $dir . '/' . $filename;
//            copy($fileurl, $copyurl);
//
//            $imgrl = array();
//            $imgrl['code'] = $fileinfo['error'];
//            $imgrl['msg'] = 00;
//            $imgrl['data']['src'] = $this->request->scheme().'://'.$_SERVER['HTTP_HOST'].'/' . $copyurl;
//            $imgrl['data']['title'] = $fileinfo['name'];
////           img_mini('/' . $copyurl,80);//上传图片压缩
//            $img = json_encode($imgrl);
//            return $img;
//        }
    }
}