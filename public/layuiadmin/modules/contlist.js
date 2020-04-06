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

  //文章管理
  table.render({
    elem: '#LAY-app-content-list'
    ,url: layui.setter.base + 'json/content/list.js' //模拟接口
    ,rowDrag: {/*trigger: 'row',*/ done: function(obj) {
        // 完成时（松开时）触发
        // 如果拖动前和拖动后无变化，则不会触发此方法
        console.log(obj.row) // 当前行数据
        console.log(obj.cache) // 改动后全表数据
        console.log(obj.oldIndex) // 原来的数据索引
        console.log(obj.newIndex) // 改动后数据索引
      }}
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field: 'id', width: 100, title: '文章ID', sort: true}
      ,{field: 'label', title: '文章标签', minWidth: 100}
      ,{field: 'title', title: '文章标题'}
      ,{field: 'author', title: '作者'}
      ,{field: 'uploadtime', title: '上传时间', sort: true}
      ,{field: 'status', title: '发布状态', templet: '#buttonTpl', minWidth: 80, align: 'center'}
      ,{title: '操作', minWidth: 150, align: 'center', fixed: 'right', toolbar: '#table-content-list'}
    ]]
    ,page: true
    ,limit: 10
    ,limits: [10, 15, 20, 25, 30]
    ,text: '对不起，加载出现异常！'
    ,done: function() {
      // 在 done 中开启
      soulTable.render(this)
    }
  });
  
  //监听工具条
  table.on('tool(LAY-app-content-list)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
      layer.confirm('确定删除此文章？', function(index){
        obj.del();
        layer.close(index);
      });
    } else if(obj.event === 'edit'){
      layer.open({
        type: 2
        ,title: '编辑文章'
        ,content: '../../../views/app/content/listform.html?id='+ data.id
        ,maxmin: true
        ,area: ['550px', '550px']
        ,btn: ['确定', '取消']
        ,yes: function(index, layero){
          var iframeWindow = window['layui-layer-iframe'+ index]
          ,submit = layero.find('iframe').contents().find("#layuiadmin-app-form-edit");

          //监听提交
          iframeWindow.layui.form.on('submit(layuiadmin-app-form-edit)', function(data){
            var field = data.field; //获取提交的字段
            
            //提交 Ajax 成功后，静态更新表格中的数据
            //$.ajax({});              
            obj.update({
              label: field.label
              ,title: field.title
              ,author: field.author
              ,status: field.status
            }); //数据更新
            
            form.render();
            layer.close(index); //关闭弹层
          });  
          
          submit.trigger('click');
        }
      });
    }
  });

  //分类管理
  table.render({
    elem: '#LAY-app-content-tags'
    ,url: '/admin/api.column/list' //模拟接口
    ,drag: {toolbar: true}
    /*,rowDrag: {trigger: 'row', done: function(obj) {
        // 完成时（松开时）触发
        // 如果拖动前和拖动后无变化，则不会触发此方法
        // console.log(obj.row) // 当前行数据
        // console.log(obj.cache) // 改动后全表数据
        console.log(obj.oldIndex) // 原来的数据索引
        console.log(obj.newIndex) // 改动后数据索引
      }}*/
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

  //评论管理
  table.render({
    elem: '#LAY-app-content-comm'
    ,url: layui.setter.base + 'json/content/comment.js' //模拟接口
    ,cols: [[
      {type: 'checkbox', fixed: 'left'}
      ,{field: 'id', width: 100, title: 'ID', sort: true}
      ,{field: 'reviewers', title: '评论者', minWidth: 100}
      ,{field: 'content', title: '评论内容', minWidth: 100}
      ,{field: 'commtime', title: '评论时间', minWidth: 100, sort: true}
      ,{title: '操作', width: 150, align: 'center', fixed: 'right', toolbar: '#table-content-com'}
    ]]
    ,page: true
    ,limit: 10
    ,limits: [10, 15, 20, 25, 30]
    ,text: '对不起，加载出现异常！'
  });
  
  //监听工具条
  table.on('tool(LAY-app-content-comm)', function(obj){
    var data = obj.data;
    if(obj.event === 'del'){
      layer.confirm('确定删除此条评论？', function(index){
        obj.del();
        layer.close(index);
      });
    } else if(obj.event === 'edit') {
      layer.open({
        type: 2
        ,title: '编辑评论'
        ,content: '../../../views/app/content/contform.html'
        ,area: ['450px', '300px']
        ,btn: ['确定', '取消']
        ,yes: function(index, layero){
          var iframeWindow = window['layui-layer-iframe'+ index]
          ,submitID = 'layuiadmin-app-comm-submit'
          ,submit = layero.find('iframe').contents().find('#'+ submitID);

          //监听提交
          iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
            var field = data.field; //获取提交的字段
            
            //提交 Ajax 成功后，静态更新表格中的数据
            //$.ajax({});
            table.reload('LAY-app-content-comm'); //数据刷新
            layer.close(index); //关闭弹层
          });  
          
          submit.trigger('click');
        }
        ,success: function(layero, index){
          
        }
      });
    }
  });

  exports('contlist', {})
});