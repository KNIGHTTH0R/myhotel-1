function checkNumber(num) {
    var pattern = /^[0-9]*$/g;
    return pattern.test(num);
}

function checkEmail(str) {
	var pattern = /^[0-9a-z\.\_]*\@{1}[0-9a-z]*(\.([a-z])+)+$/g;
	return pattern.test(str);
}

function checkPhone(str) {
	var pattern = /^[0-9]*$/g;
	return pattern.test(str);
}

function addCommas(str)
{
    str = str.trim();
    str = str.replace(/\./g, '');
    if (str.length > 12)
        str = str.substring(0, 12);
    //str = Left(str,12);
    //alert(str);
    var ret = '';
    var i = str.length;
    while (i > 3) {
        ret = '.' + str.substr(i - 3, i) + ret;
        str = str.substr(0, i - 3);
        i = str.length;

    }
    if (i > 0)
        ret = '.' + str + ret;
    //echo $str;
    return ret.substr(1);

}
function removeCommas(str)
{
    return str.replace(/\./g, '');
}

function dayName(num) {
	switch (parseInt(num)) {
		case 0:
			return 'CN';
			break;
		case 1:
			return 'Hai';
			break;
		case 2:
			return 'Ba';
		case 3:
			return 'Tư';
			break;
		case 4:
			return 'Năm';
			break;
		case 5:
			return 'Sáu';
			break;	
		case 6:
			return 'Bảy';
			break;
	}
}

$(function() {
    $("#checkAll").click(function() {
        var checkStatus = this.checked;
        var checkBoxDel = $("input[type='checkbox']");
        checkBoxDel.each(function() {
            this.checked = checkStatus;
        });
    });
})

