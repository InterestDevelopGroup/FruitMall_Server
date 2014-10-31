(function($) {
        $(document).ready(function () {
           $('#keyword').focus(function(){
              if($(this).val() == '银大益'){
                $(this).val('');
              }
            }).blur(function(){
              if($(this).val() == ''){
                $(this).val('银大益');
              }
            });
            //品牌查询搜索
            $('#filter_box').click(function(){
              $('#year_show').toggle();
            });
        });
})(jQuery)