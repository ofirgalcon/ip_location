<div id="ip-location-cnt"></div>

<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/ip_location/ip_location" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/ip_location/ip_location" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2><i class="fa fa-map-marker"></i> <span data-i18n="ip_location.title"></span> <span id="country-flag"></span></h2>

<table id="ip_location-tab-table"><tbody></tbody></table>

<script>
$(document).on('appReady', function(){
    const $ipLocationCnt = $('#ip-location-cnt');
    const $countryFlag = $('#country-flag');
    
    // Set blank badge initially
    $ipLocationCnt.empty();
    
    // Function to get country flag emoji from country code
    function getCountryFlag(countryCode) {
        if (!countryCode) return '';
        // Convert country code to regional indicator symbols
        const offset = 127397; // Regional indicator symbols start at this offset
        const flag = countryCode.toUpperCase()
            .split('')
            .map(char => String.fromCodePoint(char.charCodeAt(0) + offset))
            .join('');
        return flag;
    }
    
    // Create a map to convert country codes to country names
    let countryMap = {};
    
    // Load the ISO 3166-1 alpha-2 CSV file
    $.ajax({
        url: appUrl + '/local/iso_3166-1-alpha-2.csv',
        async: false,
        dataType: 'text',
        success: function(data) {
            // Parse CSV data
            const lines = data.split('\n');
            // Skip header line
            for (let i = 1; i < lines.length; i++) {
                if (!lines[i].trim()) continue; // Skip empty lines
                const parts = lines[i].split(',');
                if (parts.length >= 2) {
                    countryMap[parts[0]] = parts[1];
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading CSV:', status, error);
        }
    });
    
    $.getJSON(appUrl + '/module/ip_location/get_data/' + serialNumber, function(data){
        var table = $('#ip_location-tab-table');
        $.each(data, function(key,val){
            var th = $('<th>').text(i18n.t('ip_location.column.' + key));
            var td = $('<td>');
            
            // For country field, add country name in brackets after the code
            if (key === 'country' && val) {
                const countryName = countryMap[val] || '';
                if (countryName) {
                    td.text(val + ' (' + countryName + ')');
                } else {
                    td.text(val);
                }
            } else {
                td.text(val);
            }
            
            // Add flag to header when we find the country
            if (key === 'country' && val) {
                // Get country name from country code
                const countryName = countryMap[val] || val;
                $countryFlag.html('<span style="font-size: 1.1em; position: relative; top: 2px;" title="' + countryName + '">' + getCountryFlag(val) + '</span>');
            }
            
            table.append($('<tr>').append(th, td));
            
            // Update badge with city if available
            if (key === 'city' && val) {
                $ipLocationCnt.text(val);
            }
        });
    });
});
</script>
