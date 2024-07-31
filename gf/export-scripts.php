<?php
namespace FFPlugin\LeadTracker;

$form = $this->get_current_form();
$form_settings = $this->get_form_settings($form);

// $form[$this->get_slug()] = [
//     'export_columns' => $form_settings['export_columns'],
// ];
// \GFFormsModel::update_form_meta( $form['id'], $form );

// pre_debug( $form_settings );

$data = [
    'addon_slug' => $this->get_slug(),
    'form_id' => $form['id'],
    'last_export_date' => $form_settings['last_export_date'] ?? '',
];
?>
<script>
var ff_lead_tracker_data = <?php echo json_encode($data); ?>;
</script>
<?php
vite_enqueue('admin', 'src/admin/admin.js');