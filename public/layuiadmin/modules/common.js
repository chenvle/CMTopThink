/**

 @Name：layuiAdmin 公共业务
 @Author：贤心
 @Site：http://www.layui.com/admin/
 @License：LPPL

 */

layui.define('curl',function(exports){
  let $ = layui.$
  ,layer = layui.layer
  ,laytpl = layui.laytpl
  ,setter = layui.setter
  ,curl = layui.curl
  ,view = layui.view
  ,admin = layui.admin

  //退出
  admin.events.logout = function(){
    let type = $(this).data('type');
    curl.ajax('/admin/api.login/logout','post',{},function (res) {
      layer.msg(res.msg);
      if(res.status){
        setTimeout(function () {
          if(type === 'admin'){
            location.href = '/admin/login?type=admin';
          }else{
            location.href = '/admin/login';
          }
        },1000)
      }
    });
  };

  //对外暴露的接口
  exports('common', {});
});
