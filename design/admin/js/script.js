	function setFocus() {
		document.loginForm.password.select();
		document.loginForm.password.focus();
	}


	function CyrToLat(str) {
		var abc = new Array();

		abc['а']='a';
		abc['б']='b';
		abc['в']='v';
		abc['г']='g';
		abc['д']='d';
		abc['е']='e';
		abc['ё']='yo';
		abc['ж']='j';
		abc['з']='z';
		abc['и']='i';
		abc['й']='y';
		abc['к']='k';
		abc['л']='l';
		abc['м']='m';
		abc['н']='n';
		abc['о']='o';
		abc['п']='p';
		abc['р']='r';
		abc['с']='s';
		abc['т']='t';
		abc['у']='u';
		abc['ф']='f';
		abc['х']='h';
		abc['ц']='c';
		abc['ч']='ch';
		abc['ш']='sh';
		abc['щ']='sh';
//		abc['ъ']='#';
		abc['ы']='i';
//		abc['ь']='\'';
		abc['э']='e';
		abc['ю']='yu';
		abc['я']='ya';

		abc['a']='a';
		abc['b']='b';
		abc['c']='c';
		abc['d']='d';
		abc['e']='e';
		abc['f']='f';
		abc['g']='g';
		abc['h']='h';
		abc['i']='i';
		abc['j']='j';
		abc['k']='k';
		abc['l']='l';
		abc['m']='m';
		abc['n']='n';
		abc['o']='o';
		abc['p']='p';
		abc['q']='q';
		abc['r']='r';
		abc['s']='s';
		abc['t']='t';
		abc['u']='u';
		abc['v']='v';
		abc['w']='w';
		abc['x']='x';
		abc['y']='y';
		abc['z']='z';

		abc['1']='1';
		abc['2']='2';
		abc['3']='3';
		abc['4']='4';
		abc['5']='5';
		abc['6']='6';
		abc['7']='7';
		abc['8']='8';
		abc['9']='9';
		abc['0']='0';

		abc['-']='-';
		abc[' ']='-';
		abc['_']='-';
		abc['.']='-';
		abc[',']='-';

		var txt=str.toLowerCase();
		var txtnew='';
		var finaltxt='';
		var symb='';

		for (kk=0;kk<txt.length;kk++) {
			symb=abc[txt.substr(kk,1)]?abc[txt.substr(kk,1)]:'';
			txtnew=txtnew.substr(0,txtnew.length)+symb;
		}
		for (kk=0;kk<txtnew.length;kk++) {
			symb=txtnew.substr(kk,1);
			if (symb=='-' && txtnew.substr((kk+1),1)=='-') continue;
			finaltxt=finaltxt.substr(0,finaltxt.length)+symb;
		}
		return finaltxt;
	}


	function addAlias(str,id) {
		var el=document.getElementById(id);
		if (el.value=='') el.value=CyrToLat(str);
	}


	function checkAlias(str) {
		var abc='abcdefghijklmnopqrstuvwxyz1234567890-_';
		var txt=str.toLowerCase();
		var symb='';

		for (kk=0;kk<txt.length;kk++) {
			symb=txt.substr(kk,1);
			if (abc.indexOf(symb)==-1) return false;
		}
		return true;
	}


	function add_file_fild(group) {
		var fp=document.getElementById('files_table_'+group);
		var fn=document.getElementById('num_files_'+group);
		if (fp && fn) {
			fn.value++;

			var r=fp.insertRow(fp.rows.length);
			var ff=document.createElement('INPUT');
			ff.type='file';
			ff.name=group+fn.value;
			r.insertCell(-1).appendChild(ff);
		}
	}


	function add_some_filds(group,indexes) {
		var fp=document.getElementById('filds_table_'+group);
		var fn=document.getElementById('num_filds_'+group);
		if (fp && fn) {
			fn.value++;

			var r=fp.insertRow(fp.rows.length);
			for (var i=0;i<indexes.length;i++) {
				var ff=document.createElement('INPUT');
				ff.type='text';
				ff.name=group+'_'+indexes[i]+'_'+fn.value;
				r.insertCell(-1).appendChild(ff);
			}
		}
	}