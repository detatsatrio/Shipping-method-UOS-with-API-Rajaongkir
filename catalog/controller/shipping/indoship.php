<?php
class ControllerShippingIndoship extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/indoship');
		

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/script.js');
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

		$data['jne'] = $this->language->get('jne');
		$data['tiki'] = $this->language->get('tiki');
		$data['pos'] = $this->language->get('pos');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['base'] = $this->config->get('config_url'); 

		
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


		if (isset($this->request->post['indoship_origin'])) {
			$data['indoship_origin'] = $this->request->post['indoship_origin'];
		} else {
			$data['indoship_origin'] = $this->config->get('indoship_origin');
		}

		if (isset($this->request->post['indoship_tax_class_id'])) {
			$data['indoship_tax_class_id'] = $this->request->post['indoship_tax_class_id'];
		} else {
			$data['indoship_tax_class_id'] = $this->config->get('indoship_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['indoship_geo_zone_id'])) {
			$data['indoship_geo_zone_id'] = $this->request->post['indoship_geo_zone_id'];
		} else {
			$data['indoship_geo_zone_id'] = $this->config->get('indoship_geo_zone_id');
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

	public function autocomplete(){
		$json = array();

		if (isset($this->request->get['indoship_origin'])) {
			$rajaongkir = new rajaOngkir();
			
			//echo $city;

			if (isset($this->request->get['indoship_origin'])) {
				$indoship_origin = $this->request->get['indoship_origin'];
			} else {
				$indoship_origin = '';
			}

			$filter_data = array(
				'indoship_origin' => $indoship_origin
			);

			$results = $this->$rajaongkir->allCity();

			foreach ($results as $result) {
				$json[] = array(
					//'product_id' => $result['product_id'],
					//'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					//'model'      => $result['model'],
					//'option'     => $option_data,
					//'price'      => $result['price']
					'nama_kota' => $result['rajaongkir']['results']['city_name'],
					'id_kota' => $result['rajaongkir']['results']['city_id']
				);
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$json = $this->response->setOutput(json_encode($json));
		echo $json;
		
	}

	public function allCity(){
		$rajaongkir = new rajaOngkir();
		$city = $rajaongkir->allCity();
		echo $city;

		$data['token'] = $this->session->data['token'];

		$this->response->redirect($this->url->link('shipping/indoship', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function province(){
		$rajaongkir = new rajaOngkir();
		$province = $rajaongkir->showProvince();
		echo $province;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/indoship')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}