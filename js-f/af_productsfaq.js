function showDetail(id){
    var detail = document.getElementById(id);
    if (detail.style.display == 'none') {
        //���X��'none'�i��\���j�̏ꍇ�A�\��������
        detail.style.display = 'block';
    }
}

function closeDetail(id){
    var detail = document.getElementById(id);
    if (detail.style.display == 'block') {
        //���X��'none'�i��\���j�̏ꍇ�A�\��������
        detail.style.display = 'none';
    }
}


// ���̓`�F�b�N���s�Ȃ��܂��B
// �`�F�b�N�{�b�N�X�������m�F���܂��B

function checkVote() {

	var n;
	for (i=0; i<3; i++) if (document.vote["rating"][i].checked) n = i+1;
	if (n == undefined) {
		window.alert("���M���Ă��������ꍇ�́AA�`C�̂����ꂩ�Ƀ`�F�b�N�����肢�������܂��B");
	}
	else {
		document.vote.submit();
	}

}