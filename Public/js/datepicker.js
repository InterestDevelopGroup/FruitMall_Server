jQuery(function($) {
    $.datepicker.regional['zh-CN'] = {
        monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        dateFormat: 'yy-mm-dd', firstDay: 1,
        initStatus: '请选择日期', isRTL: false}
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);
    $("#dateinput").datepicker();

});

(function($) {
    $(document).ready(function() {
        $('#begin').datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            maxDate: new Date(),
            onSelect:function(dateText,inst){  
               $("#end").datepicker("option","minDate",dateText);  
            }  
        });
        $('#end').datepicker({
            dateFormat: "yy-mm-dd",
            changeYear: true,
            changeMonth: true,
            maxDate: new Date(),
            onSelect:function(dateText,inst){ 
                $("#begin").datepicker("option","maxDate",dateText);  
            }
        });
     })
})(jQuery)