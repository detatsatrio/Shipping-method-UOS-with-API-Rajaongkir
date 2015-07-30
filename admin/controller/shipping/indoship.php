<?php
class ControllerShippingIndoship extends Controller {
	private $error = array();

	public function __construct($registry) {
        parent::__construct($registry);
        $this->load->library('rajaongkir');
        //$this->indoship = new rajaOngkir();
    }

	public function index() {
		$this->load->language('shipping/indoship');

		$this->document->setTitle($this->language->get('heading_title'));
		//$this->document->addScript('view/javascript/script.js');
		
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('indoship', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_pilih_kota'] = $this->language->get('text_pilih_kota');
		$data['text_service'] = $this->language->get('text_service');

		$data['entry_indo_origin'] = $this->language->get('entry_indo_origin');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_service'] = $this->language->get('entry_service');
		$data['entry_mode_berat'] = $this->language->get('entry_mode_berat');
		$data['entry_tampilan_destinasi'] = $this->language->get('entry_tampilan_destinasi');
		$data['entry_origin'] = $this->language->get('entry_origin');

		$data['help_tampilan_destinasi'] = $this->language->get('help_tampilan_destinasi');
		$data['help_mode_berat'] = $this->language->get('help_mode_berat');
		$data['help_service'] = $this->language->get('help_service');

		$data['text_jne'] = $this->language->get('text_jne');
		$data['text_tiki'] = $this->language->get('text_tiki');
		$data['text_pos'] = $this->language->get('text_pos');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		//$data['city'] = $this->url->link('shipping/indoship/allcity', 'token=' . $this->session->data['token'], 'SSL');

		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/indoship', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/indoship', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		$rajaongkir = new rajaOngkir();
		$city = $rajaongkir->allCity();
		//convert json to array
		$cities = json_decode($city,true);
		$data['origins'] = $cities['rajaongkir']['results'];
		$data['origin'] = $this->config->get('indoship_origins');

		if (isset($this->request->post['indoship_origins'])) {
			$data['indoship_origins'] = $this->request->post['indoship_origins'];
		} else {
			$data['indoship_origins'] = $this->config->get('indoship_origins');
		}

		if (isset($this->request->post['indoship_origins_name'])) {
			$data['indoship_origins_name'] = $this->request->post['indoship_origins_name'];
		} else {
			$data['indoship_origins_name'] = $this->config->get('indoship_origins_name');
		}

		/*if (isset($this->request->post['indoship_weight'])) {
			$data['indoship_weight'] = $this->request->post['indoship_weight'];
		} else {
			$data['indoship_weight'] = $this->config->get('indoship_weight');
		}*/

		if (isset($this->request->post['indoship_tax_class_id'])) {
			$data['indoship_tax_class_id'] = $this->request->post['indoship_tax_class_id'];
		} else {
			$data['indoship_tax_class_id'] = $this->config->get('indoship_tax_class_id');
		}

		if (isset($this->request->post['indoship_destinasi'])) {
			$data['indoship_destinasi'] = $this->request->post['indoship_destinasi'];
		} else {
			$data['indoship_destinasi'] = $this->config->get('indoship_destinasi');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['indoship_geo_zone_id'])) {
			$data['indoship_geo_zone_id'] = $this->request->post['indoship_geo_zone_id'];
		} else {
			$data['indoship_geo_zone_id'] = $this->config->get('indoship_geo_zone_id');
		}

		if (isset($this->request->post['indoship_services'])) {
			$data['indoship_services'] = $this->request->post['indoship_services'];
		} else {
			$data['indoship_services'] = $this->config->get('indoship_services');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['indoship_status'])) {
			$data['indoship_status'] = $this->request->post['indoship_status'];
		} else {
			$data['indoship_status'] = $this->config->get('indoship_status');
		}

		if (isset($this->request->post['indoship_sort_order'])) {
			$data['indoship_sort_order'] = $this->request->post['indoship_sort_order'];
		} else {
			$data['indoship_sort_order'] = $this->config->get('indoship_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/indoship.tpl', $data));
	}

	public function allCity(){
		$rajaongkir = new rajaOngkir();
		$city = $rajaongkir->allCity();
		//convert json to array
		$kota = json_decode($city,true);
		print_r($kota);
	}

	public function province(){
		$rajaongkir = new rajaOngkir();
		$province = $rajaongkir->showProvince();
		echo $province;
	}

	public function hitung(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
 	 	CURLOPT_URL => "http://rajaongkir.com/api/starter/cost",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "origin=501&destination=114&weight=1700&courier=all",
		  CURLOPT_HTTPHEADER => array(
		    "content-type: application/x-www-form-urlencoded",
		    "key: d896a543bab349cf3ae0f8d41016ab0d"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		$hitung = json_decode($response,true);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  print_r($hitung);
		}


	}

	public function autocomplete() {
		$json = array();
		$rakir = new rajaOngkir(); 
		
		if (isset($this->request->get['indoship_origin'])) {
			

			if (isset($this->request->get['indoship_origin'])) {
				$indoship_origin = $this->request->get['indoship_origin'];
			} else {
				$indoship_origin = '';
			}

			$filter_data = array(
				'indoship_origin'  => $indoship_origin
			);

			$results = $rakir->allcity($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'city_name'  => strip_tags(html_entity_decode($result['rajaongkir']['results']['city_name'], ENT_QUOTES, 'UTF-8')),
					'city_id'      => $result['rajaongkir']['results']['city_id']
				);
			}
	}
	$this->response->addHeader('Content-Type: application/json');
	$this->response->setOutput(json_encode($json));
}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/indoship')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}