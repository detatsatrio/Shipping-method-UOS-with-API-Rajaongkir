<?php
class ModelShippingIndoShip extends Model {
	function getQuote($address) {
		$this->load->language('shipping/indoship');

		$rajaongkir = new rajaOngkir();
		if ($this->config->get('indoship_status')) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('indo_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");	
			if (!$this->config->get('indoship_geo_zone_id')) {
			$status = true;
			} elseif ($cost->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$this->load->library('rajaongkir');
			$rajaongkir = new rajaOngkir();

			//kondisional berat?
			$cart_weight = $this->cart->getWeight();
			//$weight = ( ($this->cart->getWeight() < 1) ? 1 : ceil($this->cart->getWeight()) );
			/*$weight = ( (float)$cart_weight >=1 ? (float)$cart_weight : 1.0);
				
			$fraction = round(($weight - (int)$weight) , 2);
			if(abs($fraction-0) > 0.3){
				$weight = round($weight);
			}else{
				$weight = floor($weight);
			}

			$weightText = ($weight >= 1 ? $weight : 1);*/
			$beratBarang = $cart_weight * 1000;
			if ($beratBarang=='0'||$beratBarang==''||$beratBarang==NULL) {
				$beratBarang = '1000';
			}
			
			//Show Destinasi/Originasi
			$addres = $rajaongkir->allcity();
			$addrss = json_decode($addres,true);
			$add = $addrss['rajaongkir']['results'];
			//print_r($address['city']);
			foreach ($add as $key) {
				if ($key['city_name'] == $address['city']){
					$desti = $address['city'];
				}else if ($key['city_id'] == $this->config->get('indoship_origins')) {
					$origi = $key['city_name'];
				}else if($key['city_id'] == $address['city']){
					$desti = $key['city_name'];
				}
			}

		   

			//Menampilkan asal dan tujuan kiriman
			$display_origin_destination = $this->config->get('indoship_destinasi');
			//$display_origin_destination = ((int)$display_origin_destination == 1 || $display_origin_destination == 'y' ? true : false);
			if($display_origin_destination){
				$mtitle = sprintf($this->language->get('text_title'), $origi, $desti, $beratBarang);
			}else{
				$mtitle = $this->language->get('text_title_no_od');
			}

			//Menghitung tarif ongkir dengan parameter origin,destinasi,courier,dan weight
			$origin = $this->config->get('indoship_origins');
			foreach ($add as $key) {
				if ($this->session->data['shipping_address']['city'] == $key['city_id']) {
					$destinasi = $this->session->data['shipping_address']['city'];
				}else{
					$destinasi = $key['city_id'];
				}
			}
			$courier = $this->config->get('indoship_services');
			$hitungongkos = $rajaongkir->hitungOngkir($origin,$destinasi,$beratBarang,$courier);
			$ongkir = json_decode($hitungongkos,true);
			$hitungOngkir = $ongkir['rajaongkir']['results'];
			//$cost = $hitungOngkir['costs'];
			//print_r($hitungOngkir);
			if (!empty($hitungOngkir)) {
				foreach ($hitungOngkir as $service) {
					if ($service['code']=='pos') {
						$kurir = $service['name'];
						//print_r($kurir);
					}elseif ($service['code']=='tiki') {
						$kurir = $service['name'];
						//print_r($kurir);
					}else{
						$kurir = $service['name'];
						//print_r($kurir);
					}
					foreach ($service['costs'] as $value) {
						$services = $value['service'];
						///print_r($services);
						$description = $value['description'];
						//print_r($description);
					
						foreach ($value['cost'] as $costs) {
							$ongkos = $costs['value'];
							//print_r($ongkos);
							if ($costs['etd']=='') {
								$etd = "-, ";
							}elseif ($costs['note']==''){
								$note = "-";
							}elseif (empty($costs['etd'])){
								$etd = "";
							}else{
								$etd = $costs['etd'].' Hari, ';
								$note = $costs['note'];
							}
								//getAllQuotes
								$title = '<b>Kurir: </b>'.$kurir.', <b>Servis: </b>'.$services.', <b>Deskripsi: </b> '.$description.', <b>Catatan: </b> '.$note;
								$rname = 'indoship'.$description;
								$quote_data[$rname] = array(
								'code'         => 'indoship.'.$rname,
								'title'        => $title,
								'cost'         => $ongkos,
								'tax_class_id' => $this->config->get('indoship_tax_class_id'),
								'text'         => '<b>'.$this->currency->format($this->tax->calculate($ongkos, $this->config->get('indoship_tax_class_id'), $this->config->get('config_tax'))).'</b>'
								);
								//return $quote_data;
								//print_r($quote_data);
						}
					}
				}
				//echo($cart_weight);
			}
			if(count($quote_data) < 1){
				//if($Jne->invalid_dest_code){
				//    $error_msg = $this->language->get('error_destination_city');
				//}else{
				//if($enable_inform_later){
				$cost = 0;
				$quote_data['indoship_origins_inform_later'] = array(
							'code'           => 'indoship.indoship_origins_inform_later',
							'title'        => $this->language->get('text_description_indoship_inform_later'),
							'cost'         => $cost,
							'tax_class_id'  => $this->config->get('indoship_tax_class_id'),
							'text'         => $this->language->get('text_cost_inform_later'),
				);
				
				//}else{
				//    $error_msg = $this->language->get('error_destination_city');
				//}
				//}
			}

		
		$method_data = array(
				'id'       => 'indoship',
				'code'       => 'indoship',
				'title'      => $mtitle,
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('indoship_sort_order'),
				'error'      => false
			);
		}
		//print_r($method_data);
		return $method_data;
	}
}