layui.define(['jquery','layer'], function(exports){
  const $ = layui.jquery, layer = layui.layer;
  const obj = {
    num:0,
    ajax: function (url, type, data, callback,error) {
      $.ajax({
        url: url,
        type: type,
        dataType: 'json',
        data: data,
        success: callback,
        error:error
      });
    },
    get_video: async function (field) {
      let index = layer.load(1);
      obj.ajax('/admin/api.video/get', 'GET', field, await function (res) {
        if (res.status) {
          field.pg = res.data.page;
          layer.msg(res.msg);
          obj.echo(res);
          obj.get_video(field,index);
        } else {
          layer.closeAll();
          layer.msg(res.msg);
          setTimeout(
              function () {
                parent.layui.admin.closeThisTabs();
              }, 2000
          );
        }
      });
    },
    echo: async function(res){
      $.each(res.data.info.list.video,function (key, value) {
        setTimeout(function () {
          let num = key + obj.num;
          let info;
          if (value.msg === '采集成功') {
            info = '<span style="color: green">采集成功</span>'
          } else if (value.msg === '已经采集') {
            info = '<span style="color: orange">已经采集</span>'
          } else {
            info = '<span style="color: red">采集异常</span>'
          }
          $('#getting_video').prepend('<li>' + info + num + '：' + value.name + '</li>');
        },500);
      });
      obj.num = obj.num + res.data.info.list.video.length;
    }
  };
  //输出接口
  exports('curl', obj);
});
