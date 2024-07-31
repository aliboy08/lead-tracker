<?php
namespace FFPlugin\LeadTracker;

$form = $this->get_current_form();
$form_settings = $this->get_form_settings($form);

$fields = [];
foreach( $form_settings['export_columns'] as $item ) {
    $fields[] = [
        'label' => $item['custom_key'],
        'field_id' => $item['value'],
    ];
}

$data = [
    'form_id' => $form['id'],
    'columns' => $fields,
];
?>
<script>
var ff_lead_tracker_data = <?php echo json_encode($data); ?>;
</script>
<?php
vite_enqueue('admin', 'src/admin/admin.js');