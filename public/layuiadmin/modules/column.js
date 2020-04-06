/**

 @Name：layuiAdmin 内容系统
 @Author：star1029
 @Site：http://www.layui.com/admin/
 @License：LPPL
    
 */

layui.define(['table', 'form','curl','soulTable'], function(exports){
  var $ = layui.$
  ,table = layui.table
  ,soulTable = layui.soulTable
  ,curl = layui.curl
  ,form = layui.form;

  //分类管理
  table.render({
    elem: '#LAY-app-content-tags'
    ,url: '/admin/api.column/list' //模拟接口
    ,drag: {toolbar: true}
    ,cols: [[
      {field: 'id', width: 60,align: 'center', title: 'ID',},
      {field: 'name', title: '名称', minWidth: 100},
      {field: 'description', title: '描述', width: 200},
      {field: 'type', title: '类型', width: 100},
      {field: 'sort', title: '排序', width: 100},
      {title: '操作', width: 170, align: 'center', fixed: 'right', toolbar: '#layuiadmin-app-cont-tagsbar'}
    ]]
    ,text: '对不起，加载出现异常！'
    ,done: function() {
      // 在 done 中开启
      soulTable.render(this)
    }
  });
  
  //监听工具条
  table.on('tool(LAY-app-content-tags)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
      layer.confirm('确定删除此分类？', async function(index){
        let del_data = {id:data.id};
        curl.ajax('/admin/api.column/del', 'delete', del_data, await function (res) {
          layer.msg(res.msg);
          layer.close(index);
          if (res.status) {
            table.reload('LAY-app-content-tags');
          }
        });

      });
    } else if(obj.event === 'edit'){
      var tr = $(obj.tr);
      layer.open({
        type: 2
        ,title: '编辑栏目'
        ,content: 'edit?id='+ data.id
        ,area: ['450px', '530px']
        ,btn: ['确定', '取消']
        ,yes: async function (index, layero) {
          console.log(index);
          //获取iframe元素的值
          let othis = layero.find('iframe').contents().find("#layuiadmin-app-form-tags");
          let parent_id = othis.find('select[name="parent_id"]').val();
          let name = othis.find('input[name="name"]').val();
          let type = othis.find('select[name="type"]').val();
          let sort = othis.find('input[name="sort"]').val();
          let img_url = othis.find('input[name="img_url"]').val();
          let description = othis.find('input[name="description"]').val();

          let create = {
            parent_id: parent_id,
            name: name,
            type: type,
            sort: sort,
            img_url: img_url,
            description: description,
            id:data.id
          };

          curl.ajax('/admin/api.column/update', 'post', create, await function (res) {
            layer.msg(res.msg);
            if (res.status) {
              table.reload('LAY-app-content-tags');
              layer.close(index);
            }
          });
        }
      });
    }
  });

  exports('column', {})
});