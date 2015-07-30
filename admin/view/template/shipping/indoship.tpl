<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-indoship" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-indoship" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input_origin"><?php echo $entry_indo_origin; ?></label>
            <div class="col-sm-10">
            <select name="indoship_origins" id="indoship_origins" class="form-control">
                <?php foreach ($origins as $asal) { ?>
                <?php if ($asal['city_id'] == $indoship_origins) { ?>
                  <?php if ($asal['type'] == 'Kabupaten') { ?>
                    <option value="<?php print_r($asal['city_id']); ?>" selected="selected"><?php print_r($asal['city_name']); ?><?php echo " (Kab)";?></option>
                  <?php } else { ?>
                    <option value="<?php print_r($asal['city_id']); ?>" selected="selected"><?php print_r($asal['city_name']); ?></option>
                  <?php }?>
                <?php } else { ?>
                  <?php if ($asal['type'] == 'Kabupaten') { ?>
                    <option value="<?php print_r($asal['city_id']); ?>"><?php print_r($asal['city_name']); ?><?php echo " (Kab)";?></option>
                  <?php } else {?>
                    <option value="<?php print_r($asal['city_id']); ?>"><?php print_r($asal['city_name']); ?></option>
                  <?php }?>
                <?php } ?>
                <?php } ?>
            </select>
            </div>
          </div>
          <!--<div class="form-group">
            <label class="col-sm-2 control-label" for="input-indoship_weight"><span data-toggle="tooltip" title="<?php echo $help_mode_berat; ?>"><?php echo $entry_mode_berat; ?></span></label>
            <div class="col-sm-10">
            <select name="indoship_weight" id="input-indoship_weight" class="form-control">
            <?php if (($indoship_weight) == 'jumlah') { ?>
              <option value="jumlah" selected="selected">Jumlah barang</option>
              <option value="berat">Berat barang</option>
            <?php } else { ?>
              <option value="jumlah">Jumlah barang</option>
              <option value="berat" selected="selected">Berat barang</option>
            <?php } ?>
            </select>
            </div>
          </div>-->
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-destinasi"><span data-toggle="tooltip" title="<?php echo $help_tampilan_destinasi; ?>"><?php echo $entry_tampilan_destinasi; ?></span></label>
            <div class="col-sm-10">
            <select name="indoship_destinasi" id="input-destinasi" class="form-control">
              <?php if ($indoship_destinasi) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
            </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-service"><?php echo $entry_service;?></label>
            <div class="col-sm-10">
              <select name="indoship_services" id="input-service" class="form-control">
              <?php if (($indoship_services) == "all") { ?>
                <option value="all" selected="selected"><?php echo $text_service; ?></option>
                <option value="jne"><?php echo $text_jne; ?></option>
                <option value="tiki"><?php echo $text_tiki; ?></option>
                <option value="pos"><?php echo $text_pos; ?></option>
              <?php } elseif (($indoship_services) == "jne") { ?>
                <option value="all"><?php echo $text_service; ?></option>
                <option value="jne" selected="selected"><?php echo $text_jne; ?></option>
                <option value="tiki"><?php echo $text_tiki; ?></option>
                <option value="pos"><?php echo $text_pos; ?></option>
              <?php } elseif (($indoship_services) == "tiki") { ?>
                <option value="all"><?php echo $text_service; ?></option>
                <option value="jne"><?php echo $text_jne; ?></option>
                <option value="tiki" selected="selected"><?php echo $text_tiki; ?></option>
                <option value="pos"><?php echo $text_pos; ?></option>
              <?php } elseif (($indoship_services) == "pos") { ?>
                <option value="all"><?php echo $text_service; ?></option>
                <option value="jne"><?php echo $text_jne; ?></option>
                <option value="tiki"><?php echo $text_tiki; ?></option>
                <option value="pos" selected="selected"><?php echo $text_pos; ?></option>
              <?php } else { ?>
                <option value="all" selected="selected"><?php echo $text_service; ?></option>
                <option value="jne"><?php echo $jne; ?></option>
                <option value="tiki"><?php echo $tiki; ?></option>
                <option value="pos"><?php echo $pos; ?></option>
              <?php } ?>
              </select>
          </div>
            </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $entry_tax_class; ?></label>
            <div class="col-sm-10">
              <select name="indoship_tax_class_id" id="input-tax-class" class="form-control">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($tax_classes as $tax_class) { ?>
                <?php if ($tax_class['tax_class_id'] == $indoship_tax_class_id) { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="indoship_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $indoship_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="indoship_status" id="input-status" class="form-control">
                <?php if ($indoship_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="indoship_sort_order" value="<?php echo $indoship_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!--<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=shipping/indoship/allcity&token=<?php echo $token; ?>&city_name=' +  encodeURIComponent(request),
      dataType: 'json',
      type:'GET',
      data:{city_name: request.term},
      success: function(data) {
        response($.map(data.rajaongkir.results, function(item) {
                    return {
                        label: item.city_name,
                        value: item.city_name
                    }
                }));
        console.log(data);
      },
    });
  },
  'select': function(item) {
    $('input[name=\'filter_name\']').val(item['label']);
  }
});
</script>-->
<?php echo $footer; ?> 