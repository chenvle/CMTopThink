/**

 @Name：layuiAdmin 视频管理
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL

 */


layui.define(['table', 'form','curl'], function(exports){
  const $ = layui.$
      , table = layui.table
      , curl = layui.curl
      , form = layui.form;

  //管理员管理
  table.render({
    elem: '#LAY-user-back-manage'
    ,url: '/admin/api.video/list'
    ,page:true
    ,parseData: function(res){
      res.status = 0;
      res.message = 'ok';
      return {
        "code": res.status, //解析接口状态
        "msg": res.message, //解析提示文本
        "count": res.total, //解析数据长度
        "data": res.data //解析数据列表
      };
    }
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field: 'id', width: 80, title: 'ID', sort: true}
      ,{field: 'title', title: '名称'}
      ,{field: 'cn_title', title: '中文名称'}
      ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-useradmin-admin'}
    ]]
    ,text: '对不起，加载出现异常！'
  });

  //监听工具条
  table.on('tool(LAY-user-back-manage)', function(obj){
    const data = obj.data;
    if(obj.event === 'del'){
      layer.prompt({
        formType: 1
        ,title: '1+1=?'
      }, function(value, index){
        if(value==='2') {
          layer.close(index);
          layer.confirm('确定删除此视频？', function(index){
            curl.ajax('/admin/api.video/del','POST',{id:data.id},function (res) {
              layer.msg(res.msg);
              if(res.status){
                setTimeout(function () {
                  layer.close(index);
                  table.reload('LAY-user-back-manage');
                },1000)
              }
            });
          });
        }else{
          layer.msg('你傻的吗？');
        }

      });
    }else if(obj.event === 'edit'){
      const tr = $(obj.tr);
      layer.open({
        type: 2
        ,title: '编辑会员'
        ,content: '/admin/user/edit?id='+data.id
        ,area: ['420px', '450px']
      })
    }
  });



  exports('video', {})
});
