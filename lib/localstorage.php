<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
	$(document).ready(function() {
		saveStore();
	})


	/* save session['store'] in localstorage AND set session['store'] from localstorage when open browser */
	let saveStore = () =>{
		var all = {
			...localStorage
		};
		// Returns path only (/path/example.html)
		var pathname = window.location.pathname; 
		var err = getUrlParameter('err') ?? '';
		var url = getUrlParameter('url') ?? '';

		//get session['store'] stored in function UpdateCookie()
		var session = <?= json_encode(@$_SESSION['store']) ?> ?? "";
		session = JSON.parse(JSON.stringify(session));
		if (Object.keys(session).length !== 0 && JSON.stringify(session) !== JSON.stringify(all)) {
			setValueInLocalstorage(session);
			all = {...localStorage};
		}

		// check expire localstorage
		if(!checkExpire()){
			all = {...localStorage.clear()}; // clear all data
		}
		
		//flag reload page, [isReloaded != 1 => reload]
		let isReloaded = sessionStorage.getItem("isReloaded");

		// set session for php when open browser
		if((Object.keys(all).length !== 0 && Object.keys(all).length !== Object.keys(session).length && parseInt(isReloaded) != 1) || pathname == '/drm/loginchkdrm.php'){
			$.ajax({
				// async: false,
				type: "POST",
				// url: pathname,
				url: '/lib/common.php',
				// url: '/lib/localstorage.php',
				data: {
					store: "store",
					all: all,
					pathname: pathname,
					err: err,
					url: url
				},
				beforeSend: function () {
					// $('#scLoading').show();
				},
				dataType: "json",
				success: function(response) {
					console.log((response));
					if(response.path != "drm"){
					// ↓↓　<2022/31/08> <KhanhDinh> <overwrite html when php finished rendering>
						// $('#general').html(response.header);
						// if($('.info.service.user').html() === undefined){
						// 	$('.senryu-mvp.community').after(response.service);
						// }
					// ↑↑　<2022/31/08> <KhanhDinh> <overwrite html when php finished rendering>
						sessionStorage.setItem("isReloaded", 1);
            			// location.reload();
            			window.location.reload(true);
					}else if(response.path == "drm"){
						window.location.href = response.drm.url;
					}
				},
				complete: function () {
				}
			});
		}
	}

	/**
	* set value in localstorage
	*
	* @param session: get from PHP
	* 
	* @author Khanh
	*/ 
	let setValueInLocalstorage = (session) =>{
		Object.entries(session).forEach(([key, value]) => {
				//encode value and save in localstorage
				localStorage.setItem(key, encodeURIComponent(value));
				// localStorage.setItem(key, (value));
		})
	};

	/**
	* check expire localstorage
	*
	* @param expire: time save in localstorage
	* 
	* @author Khanh
	* @return false if expire < now(): expire is smaller than the current time
	*/ 
	let checkExpire = (expire) => {
		var now = new Date(); //now
		now = now.getTime(); // d.getTime(): because it unit milisecond, so it should devide into 1000=> second
		var expire = localStorage.getItem('expire') ?? '';
		
		if(expire < now && expire != ''){
			// all = {...localStorage.clear()}; // clear all data
			return false;
		}
		return true;
	};


	/**
	* get param in URL
	*
	* @param sParam: URL of browser
	* 
	* @author Khanh
	* @return false if not found, return value if found
	*/ 
	let getUrlParameter = (sParam) => {
		var sPageURL = window.location.search.substring(1),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
		return false;
	};
</script>