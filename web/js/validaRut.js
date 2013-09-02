
//valida un rut chileno, retorna true o false
$.validaRut = function(rut){
	var textoCopia = rut;
	var tmpstr = "";	
	for ( i=0; i < rut.length ; i++ )		
		if ( rut.charAt(i) != ' ' && rut.charAt(i) != '.' && rut.charAt(i) != '-' )
			tmpstr = tmpstr + rut.charAt(i);	
	rut = tmpstr;	
	largo = rut.length;	

	if ( largo < 2 )	
	{			
		return false;	
	}	

	for (i=0; i < largo ; i++ )	
	{			
		if ( rut.charAt(i) !="0" && rut.charAt(i) != "1" && rut.charAt(i) !="2" && rut.charAt(i) != "3" && rut.charAt(i) != "4" && rut.charAt(i) !="5" && rut.charAt(i) != "6" && rut.charAt(i) != "7" && rut.charAt(i) !="8" && rut.charAt(i) != "9" && rut.charAt(i) !="k" && rut.charAt(i) != "K" )
 		{				
			return false;		
		}	
	}	

	var invertido = "";	
	for ( i=(largo-1),j=0; i>=0; i--,j++ )		
		invertido = invertido + rut.charAt(i);	
	var dtexto = "";	
	dtexto = dtexto + invertido.charAt(0);	
	dtexto = dtexto + '-';	
	cnt = 0;	

	for ( i=1,j=2; i<largo; i++,j++ )	
	{	
		if ( cnt == 3 )		
		{			
			dtexto = dtexto + '.';			
			j++;			
			dtexto = dtexto + invertido.charAt(i);			
			cnt = 1;		
		}		
		else		
		{				
			dtexto = dtexto + invertido.charAt(i);			
			cnt++;		
		}	
	}	

	invertido = "";	
	for ( i=(dtexto.length-1),j=0; i>=0; i--,j++ )		
		invertido = invertido + dtexto.charAt(i);	

	textoCopia = invertido.toUpperCase()		

	if ( revisarDigito2(rut) ){
		return true;
		}

	return false;

	function revisarDigito( dvr )
	{	
		dv = dvr + ""	
		if ( dv != '0' && dv != '1' && dv != '2' && dv != '3' && dv != '4' && dv != '5' && dv != '6' && dv != '7' && dv != '8' && dv != '9' && dv != 'k'  && dv != 'K')	
		{
			return false;	
		}	
		return true;
	}

	function revisarDigito2( crut )
	{	
		largo = crut.length;	
		if ( largo < 2 )	
		{		
			return false;	
		}	
		if ( largo > 2 )		
			rut = crut.substring(0, largo - 1);	
		else		
			rut = crut.charAt(0);	
		dv = crut.charAt(largo-1);	
		revisarDigito( dv );	

		if ( rut == null || dv == null )
			return 0	

		var dvr = '0'	
		suma = 0	
		mul  = 2	

		for (i= rut.length -1 ; i >= 0; i--)	
		{	
			suma = suma + rut.charAt(i) * mul		
			if (mul == 7)			
				mul = 2		
			else    			
				mul++	
		}	
		res = suma % 11	
		if (res==1)		
			dvr = 'k'	
		else if (res==0)		
			dvr = '0'	
		else	
		{		
			dvi = 11-res		
			dvr = dvi + ""	
		}
		if ( dvr != dv.toLowerCase() )	
		{		
			return false;
		}

		return true;
	}
};

$.validaCorreo = function(rut){
	var ExpRegular = /(\w+)(\.?)(\w*)(\@{1})(\w+)(\.?)(\w*)(\.{1})(\w{2,3})/;
	if(ExpRegular.test(rut)) {
		return true; 
	}else{
		return false; 
	}
}