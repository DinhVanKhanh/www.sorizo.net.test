function CheckSenryuRegist() {
	if (document.senryu1007.P_Message.value == "") {
		window.alert("��������͂���Ă��܂���I");
	}
	else {
		flag = confirm("���̓��e�œ��e���܂��B��낵���ł����H");
		if (flag) document.senryu1007.submit();
	}
}

function SendApplause(obj) {
	obj.submit();
}

function SenryuDelete(obj) {
	flag = confirm("���̓��e���폜���Ă�낵���ł����H\n�폜�����ꍇ�́A������������������ׂď����Ă��܂��܂��B");
	if (flag) obj.submit();
}

