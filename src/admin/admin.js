import './admin.scss';

console.log('admin.js');

const site_data = ff_lead_tracker_data;

init();
function init(){

    const export_button = document.createElement('button');
    export_button.textContent = 'Generate CSV';
    export_button.classList.add('primary', 'button', 'large');
    export_button.id = 'generate_csv_button';

    document.getElementById('gform-settings-save').after(export_button);

    export_button.addEventListener('click', e=>{
        e.preventDefault();
        if( export_button.classList.contains('loading') ) return;
        export_button.classList.add('loading')
        
        let ajax_data = site_data;
        ajax_data.action = 'ff_gf_generate_csv';
        
        jQuery.post( ajaxurl, ajax_data, function(res) {
            console.log(res);
            window.open(res.file_url, '_blank');
            export_button.classList.remove('loading')
        });
    });
    
}