$(document).ready(function(){
	loadCity('#indoship_origin');
	
});

function loadCity(id){
	//$('#oricity').hide();
	//$('#descity').hide();
	$(id).html('loading...');
	$.ajax({
		url:'index.php?route=shipping/indoship/allcity&token=' + getURLVar('token'),
		dataType:'json',
		success:function(response){
			$(id).html('');
			city = '';
				$.each(response['rajaongkir']['results'], function(i,n){
					//city = 'if (('+n['type']+')=="'Kabupaten'") {';
					//city = '<option value="'+n['city_id']+'">Kabupaten'+n['city_name']+'</option>';
					//city = '} else {';
					//if ((+n['type']+)=='kabupaten') { 
					//	city = '<option value="'+n['city_name']+'">Kabupaten'+n['city_name']+'</option>';
					//} else {
						city = '<option value="'+n['city_name']+'">'+n['city_name']+'</option>';
					//}
					//city = '}';
					city = city + '';
					$(id).append(city);
				});
		},
		error:function(){
			$(id).html('<option value="error">error parse: cek koneksi anda</option>');
		}
	});
}