<script type="text/javascript">
  /*
  *
  * Define global javascript variables
  *
  */
  var base_url = "<?php echo base_url(); ?>"; // Base URL
  var site_url = "<?php echo site_url(); ?>"; // Site URL
  var icon_dot_url = "<?php echo base_url();?>assets/images/dot.png";
  var option_map_tile_server_copyright = '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>';
  var option_map_tile_subdomains = '<?php echo $this->optionslib->get_option('option_map_tile_subdomains') ?? 'abc';?>';
  var lang_general_gridsquares = "<?= __("Gridsquares"); ?>";
</script>

<!-- General JS Files used across Wavelog -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.qrb.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.geodesic.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/darkmodehelpers.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrapdialog/js/bootstrap-dialog.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/easyprint.js"></script>

<!-- DATATABLES LANGUAGE -->
<?php
$local_code = $language['locale'];
$lang_code = $language['code'];
$file_path = base_url() . "assets/json/datatables_languages/" . $local_code . ".json";

// Check if the file exists
if ($lang_code != 'en' && !file_exists(FCPATH . "assets/json/datatables_languages/" . $local_code . ".json")) {
    $datatables_language_url = '';
} else {
    $datatables_language_url = $file_path;
}
?>

<script type="text/javascript">
    function getDataTablesLanguageUrl() {
        locale = "<?php echo $local_code ?>";
        lang_code = "<?php echo $lang_code; ?>";
        datatables_language_url = "<?php echo $datatables_language_url; ?>";

        // if language is set to english we don't need to load any language files
        if (lang_code != 'en') {
            if (datatables_language_url !== '') {
                return datatables_language_url;
            } else {
                console.error("Datatables language file does not exist for locale: " + locale);
                return null;
            }
        }
    }
</script>
<!-- DATATABLES LANGUAGE END -->

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip()
      });

        <?php if(isset($qra) && $qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        <?php if(isset($slug)) { ?>
        var qso_loc = '<?php echo site_url('visitor/map/'.$slug.'/'.$this->uri->segment(3));?>';
        <?php } ?>
        var q_zoom = 3;

      $(document).ready(function(){
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
            <?php } else { ?>
              var grid = "No";
            <?php } ?>
            <?php if ($this->uri->segment(2) != "search" && $this->uri->segment(2) != "satellites") { ?>
            initmap(grid);
            <?php } ?>

      });

      </script>

<?php if ($this->uri->segment(2) == "satellites") { ?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.MaidenheadColoured.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sections/gridmap.js?"></script>

<script>

  // auto setting of gridmap height
  function set_map_height() {

      //header menu
      var headerNavHeight = $('nav').outerHeight();
      // console.log('nav: ' + headerNavHeight);

      // line with coordinates
      // var coordinatesHeight = $('.coordinates').outerHeight();
      // console.log('.coordinates: ' + coordinatesHeight);

      // form for gridsquare map
      var gridsquareFormHeight = $('.gridsquare_map_form').outerHeight();
      // console.log('.gridsquare_map_form: ' + gridsquareFormHeight);

      // calculate correct map height
      var gridsquareMapHeight = window.innerHeight - headerNavHeight - gridsquareFormHeight - 8;

      // and set it
      $('#gridsquare_map').css('height', gridsquareMapHeight + 'px');
      // console.log('#gridsquare_map: ' + gridsquareMapHeight);
  }
</script>

<script>

  var layer = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server');?>', {
    maxZoom: 18,
    attribution: option_map_tile_server_copyright,
    id: 'mapbox.streets'
  });

  var map = L.map('gridsquare_map', {
    layers: [layer],
    center: [19, 0],
    zoom: 2,
    fullscreenControl: true,
        fullscreenControlOptions: {
          position: 'topleft'
        },
  });

  var printer = L.easyPrint({
        tileLayer: layer,
        sizeModes: ['Current'],
        filename: 'myMap',
        exportOnly: true,
        hideControlContainer: true
    }).addTo(map);

  var grid_two = <?php echo $grid_2char; ?>;
  var grid_four = <?php echo $grid_4char; ?>;
  var grid_six = <?php echo $grid_6char; ?>;

  var grid_two_count = grid_two.length;
  var grid_four_count = grid_four.length;
  var grid_six_count = grid_six.length;


  var grid_two_confirmed = <?php echo $grid_2char_confirmed; ?>;
  var grid_four_confirmed = <?php echo $grid_4char_confirmed; ?>;
  var grid_six_confirmed = <?php echo $grid_6char_confirmed; ?>;

  var grid_two_confirmed_count = grid_two_confirmed.length;
  var grid_four_confirmed_count = grid_four_confirmed.length;
  var grid_six_confirmed_count = grid_six_confirmed.length;

  var maidenhead = L.maidenhead().addTo(map);

<?php if ($this->uri->segment(1) == "gridsquares" && $this->uri->segment(2) == "band") { ?>

  var bands_available = <?php echo $bands_available; ?>;
  $('#gridsquare_bands').append('<option value="All"><?= __("All"); ?></option>');
  $.each(bands_available, function(key, value) {
     $('#gridsquare_bands')
         .append($("<option></option>")
                    .attr("value",value)
                    .text(value));
  });

  var num = "<?php echo $this->uri->segment(3);?>";
    $("#gridsquare_bands option").each(function(){
        if($(this).val()==num){ // EDITED THIS LINE
            $(this).attr("selected","selected");
        }
    });

  $(function(){
      // bind change event to select
      $('#gridsquare_bands').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = "<?php echo site_url('gridsquares/band/');?>" + url
          }
          return false;
      });
    });
<?php } ?>
<?php } ?>
    </script>
    <?php if ($public_search_enabled || $this->session->userdata('user_type') >= 2) { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetime-moment.js"></script>
    <script>
            <?php switch($this->config->item('qso_date_format')) {
               case 'd/m/y': $usethisformat = 'D/MM/YY';break;
               case 'd/m/Y': $usethisformat = 'D/MM/YYYY';break;
               case 'm/d/y': $usethisformat = 'MM/D/YY';break;
               case 'm/d/Y': $usethisformat = 'MM/D/YYYY';break;
               case 'd.m.Y': $usethisformat = 'D.MM.YYYY';break;
               case 'y/m/d': $usethisformat = 'YY/MM/D';break;
               case 'Y-m-d': $usethisformat = 'YYYY-MM-D';break;
               case 'M d, Y': $usethisformat = 'MMM D, YYYY';break;
               case 'M d, y': $usethisformat = 'MMM D, YY';break;
               default: $usethisformat = 'YYYY-MM-D';
            } ?>

            $.fn.dataTable.moment('<?php echo $usethisformat ?>');
            $.fn.dataTable.ext.buttons.clear = {
                className: 'buttons-clear',
                action: function ( e, dt, node, config ) {
                   dt.search('').draw();
                }
            };
            $('#publicsearchtable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: true,
                "scrollY":        "500px",
                "scrollCollapse": true,
                "paging":         true,
                "scrollX": true,
                "order": [ 0, 'desc' ],
                "language": {
                  url: getDataTablesLanguageUrl(),
                },
                dom: 'Bfrtip',
                buttons: [
                   {
						extend: 'csv',
						text: '<?= __("CSV"); ?>',
						className: 'mb-1 btn btn-primary', // Bootstrap classes
						init: function(api, node, config) {
							$(node).removeClass('dt-button').addClass('btn btn-primary'); // Ensure Bootstrap class applies
						},
                   },
                   {
                      extend: 'clear',
                      text: '<?= __("Clear"); ?>',
					  className: 'mb-1 btn btn-primary', // Bootstrap classes
						init: function(api, node, config) {
							$(node).removeClass('dt-button').addClass('btn btn-primary'); // Ensure Bootstrap class applies
						},
                   }
                ]
            });
            // change color of csv-button if dark mode is chosen
            if (isDarkModeTheme()) {
               $('[class*="buttons"]').css("color", "white");
            }
        </script>
        <script type="text/javascript">
            $(function () {
                $(document).on('shown.bs.tooltip', function (e) {
                    setTimeout(function () {
                        $(e.target).tooltip('hide');
                    }, 3000);
                });
            });
            function validateForm() {
                let x = document.forms["searchForm"]["callsign"].value;
                if (x.trim() == "") {
                    $('#searchcall').tooltip('show')
                    return false;
                }
            }
        </script>
    <?php } ?>
  </body>
</html>
