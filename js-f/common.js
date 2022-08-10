function ch_syosai_on(img)
{
	img.src = "/images/shosai2.gif";
}

function ch_syosai_off(img)
{
	img.src = "/images/shosai1.gif";
}

function gopagewithSN(url)
{
	document.frmSendSN.action = url;
	document.frmSendSN.submit();
}

function OpenCustomWindow(url,winwidth,winheight,wintarget)
{
	var win = window.open(url,wintarget,'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,width=' + winwidth + ',height=' + winheight);
}


// ���򒥎��[�_�E�����[�h�p
// �f�ڌ`���ɂ���ĕ\�����e��ύX����
function ChangeMailInput() {
	v=0;
	for (i=0; i<2; i++) {
		if (document.emailcheck["mailmagazine"][i].checked) { v = i+1; }
	}
//	msg = v + "�Ԗڂ��`�F�b�N���ꂽ�ł���B";
//	alert(msg);

	if (v == 2) {
		ChangeEssentials('hidden');
	}
	else {
		ChangeEssentials('visible');
	}
}

// ���򒥎��[�_�E�����[�h�p
// �f�ڌ`���ɂ���ĕK�{�}�[�N�̕\����ύX����
function ChangeEssentials(param){
	document.getElementById("essentials1").style.visibility = param;	// ���[��1
	document.getElementById("essentials2").style.visibility = param;	// ���[��2
}



// ���򒥎��[�_�E�����[�h�p
function gensenFrmSubmit(){
	emailCheck(emailcheck);
//	document.emailcheck.onsubmit();
	}

// ���򒥎��[�_�E�����[�h�p
function check_sub(mytype, obj, txt) {
	if (mytype == -1) {
		obj.innerHTML = txt;
	}
	else if (mytype == 0) {
		obj.innerText = txt;
	}
	else {
		obj.textContent = txt;
	}
}


// ���[�U�[�o�^�Ɠ����d�g�݂��g�p����
// 2009/06/05 Oniki add
// �S�p�E���p�`�F�b�N
function checkString(str, checkType){
	switch(checkType){
		case "HANKAKU":
			for(i=0; i < str.length; i++){
				//�S�p(���p�J�^�J�i��)���܂܂�Ă�����false��Ԃ�
				var c = str.charCodeAt(i);
				if(!((c>=0x0 && c<0x81) || (c==0xf8f0) || (c>=0xff61 && c<0xffa0) || (c>=0xf8f1 && c<0xf8f4))){
					return false;
				}
			}
			break;
		case "ZENKAKU":
			for(i=0; i < str.length; i++){
				var c = str.charCodeAt(i);
				//���p���܂܂�Ă�����false��Ԃ�
				if((c>=0x0 && c<0x81) || (c==0xf8f0) || (c>=0xff61 && c<0xffa0) || (c>=0xf8f1 && c<0xf8f4)){
					return false;
				}
			}
			break;
	}
return true;
}



// ���򒥎��[�_�E�����[�h�p
function emailCheck(obj) {

	var v=0;
	for (i=0; i<2; i++) {
		if (document.emailcheck["mailmagazine"][i].checked) { v = i+1; }
	}

	var rtn = true;
	var mytype;
	var tmp = document.getElementById("id_name");
	if((document.getElementById) && navigator.appName.indexOf("Netscape") > -1) {
		mytype = -1;
	}
	else if (typeof tmp.innerText != "undefined") {
		mytype = 0;
	}
	else {
		mytype = 1;
	}


	// ����
	tmp = document.getElementById("id_name")
	if (obj.name.value == "") {
		check_sub(mytype, tmp, "�������͕K�{���ڂł�");
		rtn = false;
	}
	else { check_sub(mytype, tmp, ""); }


	// ���[���A�h���X
	tmp = document.getElementById("id_email1")

	// ���[���̂Q�ӏ��̐�����
	if (obj.email1.value != obj.email2.value) {
		check_sub(mytype, tmp, "���m�F�pe-mail�A�h���X�ƈ�v���Ă��܂���");
		rtn = false;
	}
	else {
		if (v == 1) {
			if (obj.email1.value == "" | obj.email2.value == "") {
				check_sub(mytype, tmp, "��e-mail�A�h���X��2�ӏ��Ƃ��K�{���ڂł�\n�����[�����ɂ��āu�󂯎��v��I������Ă���ꍇ�́A���[���A�h���X�̂����͂��K�v�ł�");
				rtn = false;
			}
			else {
				// ���͌`���m�F
				// dataEmail1 = obj.email1.value.match(/^\S+@\S+\.\S+$/);
				dataEmail1 = obj.email1.value.match(/^[A-Za-z0-9]+[\w\.-]*@[\w\.-]+\.\w{2,}$/);
				if (!dataEmail1) {
					check_sub(mytype, tmp, "��e-mail�A�h���X�̓��͓��e�����m�F��������\n�����[�����ɂ��āu�󂯎��v��I������Ă���\n�@�ꍇ�́A���[���A�h���X�̂����͂��K�v�ł�");
					rtn = false;
				}
				else if (obj.email1.value.indexOf(",",0) != -1) {
					check_sub(mytype, tmp, "���J���}�͎g�p�ł��܂���\n�����[�����ɂ��āu�󂯎��v��I������Ă���\n�@�ꍇ�́A���[���A�h���X�̂����͂��K�v�ł�");
					rtn = false;
				}
				else {
					var mobileStr=["docomo.ne.jp","ezweb.ne.jp","softbank.ne.jp","vodafone.ne.jp"];
					//���g�ы֎~���[�h���w��
					for (var i=0;i<mobileStr.length;i++){
						if (obj.email1.value.indexOf(mobileStr[i]) != -1) {
							check_sub(mytype, tmp, "���p�\�R���p��e-mail�A�h���X�������͂�������\n�����[�����ɂ��āu�󂯎��v��I������Ă���\n�@�ꍇ�́A���[���A�h���X�̂����͂��K�v�ł�");
							rtn = false;
							break;
						}
						else { check_sub(mytype, tmp, ""); }
					}
				}
			}
		}
		else {
			if (obj.email1.value != "") {
				// ���͌`���m�F
				// dataEmail1 = obj.email1.value.match(/^\S+@\S+\.\S+$/);
				dataEmail1 = obj.email1.value.match(/^[A-Za-z0-9]+[\w\.-]*@[\w\.-]+\.\w{2,}$/);
				if (!dataEmail1) {
					check_sub(mytype, tmp, "��e-mail�A�h���X�̓��͓��e�����m�F��������");
					rtn = false;
				}
				else if (obj.email1.value.indexOf(",",0) != -1) {
					check_sub(mytype, tmp, "���J���}�͎g�p�ł��܂���");
					rtn = false;
				}
				else { check_sub(mytype, tmp, ""); }
			}
			else { check_sub(mytype, tmp, ""); }
		}
	}



	// ���[���}�K�W���̍w�ǁi�`�F�b�N�{�b�N�X�j
	// tmp = document.getElementById("id_mailmagazine")
	// var mmflug
	// for (i=0; i<2; i++) if (document.emailcheck["mailmagazine"][i].checked) mmflug = i+1;
	// if (mmflug == undefined) {
	//	check_sub(mytype, tmp, "�����[�����̏����ɂ��ĕK���I�����Ă�������");
	//	rtn = false;
	// }
	// else { check_sub(mytype, tmp, ""); }



	//�y�ŏI�����z ��肪�����false��Ԃ��B�Ȃ����submit����B
	if (rtn == false) {
		alert("�������܂���������x���͓��e�����m�F���������B");
		return rtn;
	}
	else {
		document.emailcheck.submit();
	}


}


