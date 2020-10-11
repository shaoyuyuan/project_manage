/**
* ajax请求状态,通过blob流下载文件
* @param url 请求完整路径
* @param title 文件名
* @param ext 文件扩展名
* @module 加载loading下载文件
* @author 邵禹源
* @ctime  20190412
*/
function downloadFiles(url, title, ext) {
  //1.创建Ajax对象
　　//js中,使用一个没有定义的变量会报错,使用一个没有定义的属性,是undefined
　　//IE6下使用没有定义的XMLHttpRequest会报错,所以当做window的一个属性使用
　　if (window.XMLHttpRequest) {
  　　//非IE6
  　　var oAjax=new XMLHttpRequest();
　　}else{
  　　//IE6
  　　var oAjax=new ActiveXObject("Microsoft.XMLHTTP");
  }
  oAjax.open('GET', url, true);
  
  oAjax.responseType = "blob";
  oAjax.onload = function (oEvent) {
      //请求成功
          var blob = new Blob([oAjax.response], { type: 'application/'+ext+',charset=UTF-8'});
        if(this.status == 200){
            if('msSaveOrOpenBlob' in navigator){
                    // Microsoft Edge and Microsoft Internet Explorer 10-11
                    window.navigator.msSaveOrOpenBlob(blob, title);
            }else{
	            var elink = document.createElement('a');
	            //name为后台返给前端的文件名，根据下载文件格式加后缀名，后缀名必须加，不然下载在本地不方便打开。
	            elink.download = title;
	            elink.style.display = 'none';
	            elink.href = URL.createObjectURL(blob);
	            document.body.appendChild(elink);
	            elink.click();
	            document.body.removeChild(elink);
                window.URL.revokeObjectURL(URL.createObjectURL(blob));
            }
            $('#loading').fadeOut();
		}else{
      alert("发生错误，"+this.status);
		  return;
		}
  }
  oAjax.send();
}
/**
* 校验身份证
*/
var vcity={ 11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",  
            21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",  
            33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",  
            42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",  
            51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",  
            63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"  
           };  
  
// checkCard = function(card)  
function checkCard(card)
{  
    //是否为空  
    if(card === '')  
    {
        // alert('请输入身份证号，身份证号不能为空');  
        return '请输入身份证号，身份证号不能为空';
    }  
    //校验长度，类型  
    if(isCardNo(card) === false)  
    {  
        // alert('您输入的身份证号码不正确，请重新输入');  
        return '您输入的身份证号码不正确，请重新输入';  
    }  
    //检查省份  
    if(checkProvince(card) === false)  
    {  
        // alert('您输入的身份证号码不正确,请重新输入');  
        return '您输入的身份证号码不正确,请重新输入';  
    }  
    //校验生日  
    if(checkBirthday(card) === false)  
    {  
        // alert('您输入的身份证号码生日不正确,请重新输入');  
        return '您输入的身份证号码生日不正确,请重新输入';  
    }  
    //检验位的检测  
    if(checkParity(card) === false)  
    {  
        // alert('您的身份证校验位不正确,请重新输入');  
        return '您的身份证错误,请重新输入';  
    }  
    // alert('OK');  
    return 'OK';  
};  
  
  
//检查号码是否符合规范，包括长度，类型  
isCardNo = function(card)  
{  
    //身份证号码为15位或者18位，15位时全为数字，18位前17位为数字，最后一位是校验位，可能为数字或字符X  
    var reg = /(^\d{15}$)|(^\d{17}(\d|X)$)/;  
    if(reg.test(card) === false)  
    {  
        return false;  
    }  
  
    return true;  
};  
  
//取身份证前两位,校验省份  
checkProvince = function(card)  
{  
    var province = card.substr(0,2);  
    if(vcity[province] == undefined)  
    {  
        return false;  
    }  
    return true;  
};  
  
//检查生日是否正确  
checkBirthday = function(card)  
{  
    var len = card.length;  
    //身份证15位时，次序为省（3位）市（3位）年（2位）月（2位）日（2位）校验位（3位），皆为数字  
    if(len == '15')  
    {  
        var re_fifteen = /^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/;   
        var arr_data = card.match(re_fifteen);  
        var year = arr_data[2];  
        var month = arr_data[3];  
        var day = arr_data[4];  
        var birthday = new Date('19'+year+'/'+month+'/'+day);  
        return verifyBirthday('19'+year,month,day,birthday);  
    }  
    //身份证18位时，次序为省（3位）市（3位）年（4位）月（2位）日（2位）校验位（4位），校验位末尾可能为X  
    if(len == '18')  
    {  
        var re_eighteen = /^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/;  
        var arr_data = card.match(re_eighteen);  
        var year = arr_data[2];  
        var month = arr_data[3];  
        var day = arr_data[4];  
        var birthday = new Date(year+'/'+month+'/'+day);  
        return verifyBirthday(year,month,day,birthday);  
    }  
    return false;  
};  
  
//校验日期  
verifyBirthday = function(year,month,day,birthday)  
{  
    var now = new Date();  
    var now_year = now.getFullYear();  
    //年月日是否合理  
    if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day)  
    {  
        //判断年份的范围（3岁到100岁之间)  
        var time = now_year - year;  
        if(time >= 3 && time <= 100)  
        {  
            return true;  
        }  
        return false;  
    }  
    return false;  
};  
  
//校验位的检测  
checkParity = function(card)  
{  
    //15位转18位  
    card = changeFivteenToEighteen(card);  
    var len = card.length;  
    if(len == '18')  
    {  
        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);   
        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');   
        var cardTemp = 0, i, valnum;   
        for(i = 0; i < 17; i ++)   
        {   
            cardTemp += card.substr(i, 1) * arrInt[i];   
        }   
        valnum = arrCh[cardTemp % 11];   
        if (valnum == card.substr(17, 1))   
        {  
            return true;  
        }  
        return false;  
    }  
    return false;  
};  
  
//15位转18位身份证号  
changeFivteenToEighteen = function(card)  
{  
    if(card.length == '15')  
    {  
        var arrInt = new Array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);   
        var arrCh = new Array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');   
        var cardTemp = 0, i;     
        card = card.substr(0, 6) + '19' + card.substr(6, card.length - 6);  
        for(i = 0; i < 17; i ++)   
        {   
            cardTemp += card.substr(i, 1) * arrInt[i];   
        }   
        card += arrCh[cardTemp % 11];   
        return card;  
    }  
    return card;  
};

//ajax请求
/**
* ajax请求弹窗确认
* url 请求路径
* msg 弹窗提示说明
*/
function ajaxUrl(url, msg) {

    layer.msg(msg, {
        time: 20000, //20s后自动关闭
        btn: ['确定', '取消'],
        yes: function(){
            $.ajax({
                type: "post",
                url: url,
                data: {},
                dataType: "json",
                success: function(data){
                    if(data.error == 0){
                        window.location.reload();
                    }else{
                        reloadTips(data.msg);
                    }
                }
            });
        },
        btn2: function(){
          layer.close();
        }
    });
}
/**
* 判断数组中是否包含某个值
* search  查询的值
* array   数组
*/
function inArray(search,array){
    for(var i in array){
        if(array[i]==search){
            return true;
        }
    }
    return false;
}

 // 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
// 例子： 
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1, //月份 
        "d+": this.getDate(), //日 
        "H+": this.getHours(), //小时 
        "m+": this.getMinutes(), //分 
        "s+": this.getSeconds(), //秒 
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
        "S": this.getMilliseconds() //毫秒 
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (var k in o)
    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
}