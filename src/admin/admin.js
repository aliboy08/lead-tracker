import './admin.scss';

const container = document.getElementById('gform-settings-section-5x5-lead-tracker-settings');

const site_data = ff_lead_tracker_data;

init();
function init(){
    export_init();
    render_last_export_date();
}

function export_init(){

    const export_button = document.createElement('button');
    export_button.textContent = 'Generate CSV';
    export_button.classList.add('primary', 'button', 'large');
    export_button.id = 'generate_csv_button';

    // render beside save button
    document.getElementById('gform-settings-save').after(export_button);

    export_button.addEventListener('click', e=>{
        e.preventDefault();
        if( export_button.classList.contains('loading') ) return;
        export_button.classList.add('loading')
        
        let ajax_data = site_data;
        ajax_data.action = 'ff_gf_generate_csv';
        
        jQuery.post( ajaxurl, ajax_data, function(res) {
            console.log(res);

            // download csv file
            window.open(res.file_url, '_blank');
            
            export_button.classList.remove('loading')
        });
    });
}

function render_last_export_date(){
    console.log('render_last_export_date');
    if( !site_data.last_export_date ) return;
    let date = document.createElement('div');
    date.classList.add('last_export_date');
    date.textContent = 'Last export: ' + site_data.last_export_date;
    container.append(date);
}
